"""
Meal Plan Engine — Pakistani Recipe Recommendation System
Uses Mifflin-St Jeor BMR, TDEE with activity multipliers,
goal-aware strict recipe filtering, and calorie-matched 7-day meal assignment.
"""

import json
import random
import time
import copy
from pathlib import Path

# ─── Per-slot calorie distribution (% of daily target) ───────────────────────
SLOT_CALORIE_SHARE = {
    "breakfast": 0.25,   # 25% of daily calories
    "lunch":     0.35,   # 35%
    "dinner":    0.30,   # 30%
    "snack":     0.10,   # 10%
}

# ─── Calorie ranges per category (used for estimation) ────────────────────────
CALORIE_MAP = {
    "Breakfast":    {"min": 240, "max": 400},
    "Main Course":  {"min": 320, "max": 560},
    "Side Dish":    {"min": 100, "max": 240},
    "Snack":        {"min": 120, "max": 280},
    "Bread":        {"min": 130, "max": 200},
    "Dessert":      {"min": 280, "max": 480},
    "Soup":         {"min": 80,  "max": 180},
    "Beverage":     {"min": 80,  "max": 340},   # wide range — shakes vs water
    "Salad":        {"min": 60,  "max": 160},
    "Appetizer":    {"min": 100, "max": 220},
    "Comfort Food": {"min": 200, "max": 380},
    "Fast Food":    {"min": 450, "max": 750},
}

# ─── Activity multipliers ─────────────────────────────────────────────────────
ACTIVITY_MULTIPLIERS = {
    "sedentary":          1.2,
    "lightly_active":     1.375,
    "moderately_active":  1.55,
    "very_active":        1.725,
    "beginner":           1.375,
    "intermediate":       1.55,
    "advanced":           1.725,
}

# ─── Health condition → required health_tags ─────────────────────────────────
HEALTH_CONDITION_TAGS = {
    "diabetes":            "diabetes_friendly",
    "diabetic":            "diabetes_friendly",
    "hypertension":        "hypertension_friendly",
    "high_blood_pressure": "hypertension_friendly",
    "low_blood_pressure":  "low_blood_pressure_friendly",
    "heart_disease":       "heart_friendly",
    "thyroid":             "thyroid_friendly",
    "pcos":                "pcos_friendly",
    "kidney_disease":      "kidney_friendly",
    "anemia":              "anemia_friendly",
    "joint_pain":          "joint_pain_friendly",
    "stomach_issue":       "stomach_friendly",
}

# ─── Goal-aware slot category mapping ────────────────────────────────────────
# Weight LOSS: no shakes/desserts/fast-food in any slot
SLOT_CATEGORIES_LOSS = {
    "breakfast": ["Breakfast", "Salad"],
    "lunch":     ["Main Course", "Side Dish", "Bread", "Soup"],
    "dinner":    ["Main Course", "Side Dish", "Soup", "Salad"],
    "snack":     ["Snack", "Salad", "Appetizer"],         # NO Beverage/Dessert
}

# Weight GAIN: higher-calorie slots allowed
SLOT_CATEGORIES_GAIN = {
    "breakfast": ["Breakfast", "Bread", "Comfort Food"],
    "lunch":     ["Main Course", "Side Dish", "Bread"],
    "dinner":    ["Main Course", "Side Dish", "Comfort Food"],
    "snack":     ["Snack", "Beverage", "Bread", "Dessert"],  # shakes/desserts OK
}

# ─── Ingredients that disqualify a recipe for WEIGHT LOSS ─────────────────────
LOSS_EXCLUDED_INGREDIENTS = {
    "cream", "heavy cream", "whipped cream",
    "sugar", "white sugar", "condensed milk",
    "butter",                                    # small amounts in bread OK → handled by category
    "ghee",
    "deep fried", "frying",
}

# ─── Categories hard-blocked for weight loss ──────────────────────────────────
LOSS_BLOCKED_CATEGORIES = {"Dessert", "Fast Food", "Beverage"}

# ─── Load recipes (make deep copy so we never mutate originals) ───────────────
_recipes_path = Path(__file__).parent / "pakistani_recipes.json"
with open(_recipes_path, "r", encoding="utf-8") as _f:
    _RAW_RECIPES = json.load(_f)


def _fresh_recipes() -> list:
    """Return a deep copy of the raw recipes so scoring never mutates originals."""
    return copy.deepcopy(_RAW_RECIPES)


# ─── BMR / TDEE ───────────────────────────────────────────────────────────────
def calculate_bmr(weight_kg: float, height_cm: float, age: int, gender: str) -> float:
    """Mifflin-St Jeor BMR formula (most accurate for general population)."""
    if gender.lower() in ("male", "m"):
        return 10 * weight_kg + 6.25 * height_cm - 5 * age + 5
    else:
        return 10 * weight_kg + 6.25 * height_cm - 5 * age - 161


def calculate_tdee(bmr: float, activity_level: str) -> float:
    key = activity_level.lower().replace(" ", "_")
    return bmr * ACTIVITY_MULTIPLIERS.get(key, 1.55)


def get_calorie_target(tdee: float, goal: str, weight_kg: float, target_weight_kg: float) -> dict:
    """
    Calorie target is scaled by how large the weight gap is.
    Weight Loss  → 500–750 kcal deficit (larger gap → bigger deficit, max 750)
    Weight Gain  → 300–500 kcal surplus  (larger gap → bigger surplus, max 500)
    """
    gap = abs(target_weight_kg - weight_kg)

    if goal == "loss":
        # More to lose → more aggressive deficit, capped at 750
        deficit = min(500 + int(gap * 10), 750)
        target = max(tdee - deficit, 1200)          # never below 1200 kcal (safety floor)
        macro_split = {"carbs": 0.38, "protein": 0.37, "fat": 0.25}
        deficit_used = deficit
    else:
        # More to gain → larger surplus, capped at 500
        surplus = min(300 + int(gap * 10), 500)
        target = tdee + surplus
        macro_split = {"carbs": 0.50, "protein": 0.30, "fat": 0.20}
        deficit_used = -surplus   # negative = surplus

    return {
        "tdee":       round(tdee),
        "target":     round(target),
        "adjustment": deficit_used,
        "macros": {
            "protein_g": round((target * macro_split["protein"]) / 4),
            "carbs_g":   round((target * macro_split["carbs"])   / 4),
            "fat_g":     round((target * macro_split["fat"])     / 9),
        },
    }


# ─── Recipe Filtering ─────────────────────────────────────────────────────────
def filter_recipes(
    health_conditions: list,
    dietary_restrictions: list,
    goal: str,
    calorie_target: int,
) -> list:
    """
    Returns a fresh, filtered & scored list of recipe copies.
    Filtering is strict for weight loss, permissive for weight gain.
    """
    recipes = _fresh_recipes()

    # Build required health tag set
    required_health_tags = set()
    for cond in health_conditions:
        tag = HEALTH_CONDITION_TAGS.get(cond.lower().strip())
        if tag:
            required_health_tags.add(tag)

    # Dietary restriction flags
    require_vegetarian = any(d.lower() in ("vegetarian", "veg") for d in dietary_restrictions)
    require_vegan       = any(d.lower() == "vegan" for d in dietary_restrictions)
    exclude_beef        = any(d.lower() in ("no_beef", "no beef") for d in dietary_restrictions)
    exclude_pork        = any(d.lower() in ("no_pork", "no pork") for d in dietary_restrictions)

    filtered = []
    for recipe in recipes:
        cat        = recipe.get("category", "")
        tags_h     = recipe.get("health_tags", [])
        tags_d     = recipe.get("diet_tags", [])
        ingredients = {i.lower() for i in recipe.get("ingredients", [])}

        # ── HARD EXCLUSIONS ───────────────────────────────────────────────────

        # Block entire categories for weight loss
        if goal == "loss" and cat in LOSS_BLOCKED_CATEGORIES:
            continue

        # Block problematic ingredients for weight loss
        if goal == "loss":
            if ingredients & LOSS_EXCLUDED_INGREDIENTS:
                continue

        # Health condition gate: recipe must carry ALL required tags
        if required_health_tags and not required_health_tags.issubset(set(tags_h)):
            continue

        # Dietary gate
        if require_vegan and "vegan" not in tags_d:
            continue
        if require_vegetarian and not require_vegan:
            if "vegetarian" not in tags_d and "vegan" not in tags_d:
                continue

        # Ingredient exclusions
        if exclude_beef and any("beef" in i for i in ingredients):
            continue
        if exclude_pork and any("pork" in i for i in ingredients):
            continue

        # ── SCORING (higher = more likely to appear) ─────────────────────────
        score = 0

        if goal == "loss":
            if "low_fat" in tags_h:      score += 3
            if "low_sugar" in tags_h:    score += 2
            if "high_protein" in tags_d: score += 2   # keeps you full
            if cat in ("Salad", "Soup"): score += 2   # low-cal categories
            if cat == "Fast Food":       score -= 10  # redundant but safe
        else:  # gain
            if "high_protein" in tags_d: score += 4
            if cat in ("Dessert", "Beverage", "Comfort Food"): score += 2
            if "low_fat" in tags_h:      score -= 1   # less preferred for gain

        recipe["_score"] = score
        filtered.append(recipe)

    # Sort by score → then shuffle within same-score groups for variety each call
    filtered.sort(key=lambda r: r["_score"], reverse=True)

    # Inject per-call randomness: shuffle within score tiers
    _shuffle_within_tiers(filtered)

    return filtered


def _shuffle_within_tiers(recipes: list) -> None:
    """Shuffle recipes that share the same _score so each call gives different order."""
    if not recipes:
        return
    i = 0
    while i < len(recipes):
        j = i
        while j < len(recipes) and recipes[j]["_score"] == recipes[i]["_score"]:
            j += 1
        tier = recipes[i:j]
        random.shuffle(tier)
        recipes[i:j] = tier
        i = j


# ─── Calorie Estimation ───────────────────────────────────────────────────────
def estimate_calories(recipe: dict, slot_target: int) -> int:
    """
    Estimate meal calories, biased toward the slot's calorie target.
    slot_target = fraction of daily target for this slot (e.g. 0.35 * 1500 = 525 kcal)
    """
    cat  = recipe.get("category", "Main Course")
    base = CALORIE_MAP.get(cat, {"min": 250, "max": 500})

    # Blend category range with slot target
    low  = min(base["min"], int(slot_target * 0.8))
    high = max(base["max"], int(slot_target * 1.2))
    # Clamp to reasonable per-meal bounds
    low  = max(low,  60)
    high = min(high, 900)
    if low > high:
        low, high = high - 50, high

    cal = random.randint(low, high)

    # Minor adjustments from tags
    tags_d = recipe.get("diet_tags", [])
    tags_h = recipe.get("health_tags", [])
    if "high_protein" in tags_d:  cal += 40
    if "low_fat" in tags_h:       cal -= 20
    if recipe.get("category") == "Fast Food": cal += 100

    return max(cal, 60)


# ─── 7-Day Meal Assignment ────────────────────────────────────────────────────
def build_meal_plan(filtered_recipes: list, days: int, calorie_target: int, goal: str) -> list:
    """
    Assign breakfast / lunch / dinner / snack for each day.
    - No recipe repeated within 3 days in the same slot
    - Each slot receives calorie-target-appropriate recipes
    - Uses goal-specific slot category mapping
    """
    slot_categories = SLOT_CATEGORIES_LOSS if goal == "loss" else SLOT_CATEGORIES_GAIN
    meal_slots = list(slot_categories.keys())

    # Build per-slot candidate pools (goal-filtered + shuffled)
    pools = {}
    for slot, cats in slot_categories.items():
        candidates = [r for r in filtered_recipes if r.get("category") in cats]
        random.shuffle(candidates)
        # Slot-specific fallback — never let breakfast use Main Course
        if len(candidates) < 3:
            if slot == "breakfast":
                # Breakfast fallback: use Bread and Snack items only
                candidates = [r for r in filtered_recipes if r.get("category") in ("Bread", "Snack", "Appetizer")]
                random.shuffle(candidates)
            else:
                candidates = list(filtered_recipes)
        pools[slot] = candidates

    plan = []
    recent_used = {slot: [] for slot in meal_slots}

    for day_num in range(1, days + 1):
        day = {"day": day_num, "meals": {}, "total_calories": 0, "total_protein_g": 0}
        used_today = set()   # prevent same recipe in multiple slots on same day

        for slot in meal_slots:
            slot_cal_target = int(calorie_target * SLOT_CALORIE_SHARE[slot])
            pool = pools[slot]

            # Exclude recently used (no-repeat within 3 days per slot) AND already used today
            available = [
                r for r in pool
                if r["name"] not in recent_used[slot] and r["name"] not in used_today
            ]
            if not available:
                # Relax: allow repeats from prior days but not same-day
                available = [r for r in pool if r["name"] not in used_today]
            if not available:
                available = list(pool)   # last resort

            # Pick the candidate whose category calorie range is closest to slot target
            chosen = _pick_closest_to_target(available, slot_cal_target)

            # Rotate: move to back of pool
            if chosen in pool:
                pool.remove(chosen)
            pool.append(chosen)

            cal      = estimate_calories(chosen, slot_cal_target)
            protein_g = (
                round(cal * 0.30 / 4)
                if "high_protein" in chosen.get("diet_tags", [])
                else round(cal * 0.22 / 4)
            )

            day["meals"][slot] = {
                "name":        chosen["name"],
                "category":    chosen["category"],
                "description": chosen.get("description", ""),
                "calories":    cal,
                "protein_g":   protein_g,
                "diet_tags":   chosen.get("diet_tags", []),
                "health_tags": chosen.get("health_tags", []),
                "ingredients": chosen.get("ingredients", []),
                "slot_target": slot_cal_target,
            }
            day["total_calories"]  += cal
            day["total_protein_g"] += protein_g

            # Track used names
            used_today.add(chosen["name"])
            recent_used[slot].append(chosen["name"])
            if len(recent_used[slot]) > 3:
                recent_used[slot].pop(0)

        plan.append(day)

    return plan


def _pick_closest_to_target(candidates: list, target_cal: int) -> dict:
    """
    From candidates, pick the one whose category midpoint is nearest to target_cal.
    Adds a small random jitter so it's not always perfectly deterministic.
    """
    def midpoint(r):
        cat  = r.get("category", "Main Course")
        base = CALORIE_MAP.get(cat, {"min": 300, "max": 500})
        mid  = (base["min"] + base["max"]) / 2
        # Add ±50 jitter for variety
        return abs(mid + random.randint(-50, 50) - target_cal)

    return min(candidates, key=midpoint)


# ─── Main Entry Point ─────────────────────────────────────────────────────────
def generate_meal_plan(
    goal: str,
    weight_kg: float,
    target_weight_kg: float,
    height_cm: float,
    age: int,
    gender: str,
    activity_level: str,
    health_conditions: list,
    dietary_restrictions: list,
    duration_days: int = 7,
) -> dict:
    """
    Full meal plan generation pipeline.
    Returns structured dict ready for JSON serialisation.
    """
    # Seed random differently each call for genuine variety
    random.seed(int(time.time() * 1000) % (2**32))

    # Step 1: Calculate calorie targets (gap-adjusted)
    bmr = calculate_bmr(weight_kg, height_cm, age, gender)
    tdee = calculate_tdee(bmr, activity_level)
    calorie_info = get_calorie_target(tdee, goal, weight_kg, target_weight_kg)

    # Step 2: Filter & score recipes (returns fresh copies, no mutation of originals)
    filtered = filter_recipes(health_conditions, dietary_restrictions, goal, calorie_info["target"])

    if len(filtered) < 6:
        # Emergency fallback: use all recipes minus hard-blocked categories
        fallback = _fresh_recipes()
        if goal == "loss":
            fallback = [r for r in fallback if r.get("category") not in LOSS_BLOCKED_CATEGORIES]
        random.shuffle(fallback)
        filtered = fallback

    # Step 3: Build goal-aware, calorie-matched daily plan
    plan = build_meal_plan(filtered, duration_days, calorie_info["target"], goal)

    # Step 4: Timeline estimate (scaled to weight gap)
    weight_diff = abs(target_weight_kg - weight_kg)
    if goal == "loss":
        weekly_loss = round(calorie_info["adjustment"] / 1000 * 1.1, 2)  # ~1.1 kg per 1000 kcal deficit/week
        weekly_loss = max(weekly_loss, 0.3)
        weeks_to_goal = round(weight_diff / weekly_loss, 1)
        timeline_note = (
            f"With a {calorie_info['adjustment']} kcal/day deficit, "
            f"you may lose ~{weekly_loss:.1f} kg/week and reach your target in ~{weeks_to_goal} weeks"
        )
    else:
        surplus = abs(calorie_info["adjustment"])
        weekly_gain = round(surplus / 1000 * 0.9, 2)
        weekly_gain = max(weekly_gain, 0.15)
        weeks_to_goal = round(weight_diff / weekly_gain, 1)
        timeline_note = (
            f"With a {surplus} kcal/day surplus, "
            f"you may gain ~{weekly_gain:.1f} kg/week and reach your target in ~{weeks_to_goal} weeks"
        )

    return {
        "success":          True,
        "goal":             goal,
        "bmr":              round(bmr),
        "tdee":             calorie_info["tdee"],
        "calorie_target":   calorie_info["target"],
        "calorie_adjustment": calorie_info["adjustment"],
        "macros":           calorie_info["macros"],
        "timeline_note":    timeline_note,
        "recipes_matched":  len(filtered),
        "plan":             plan,
        "health_conditions":    health_conditions,
        "dietary_restrictions": dietary_restrictions,
    }
