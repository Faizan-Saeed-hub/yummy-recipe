import json

file_path = "models/pakistani_recipes.json"

with open(file_path, "r") as f:
    recipes = json.load(f)

for r in recipes:
    if "health_tags" not in r:
        r["health_tags"] = []
    
    tags = set(r["health_tags"])
    ings = [i.lower() for i in r["ingredients"]]
    name = r["name"].lower()
    
    # Flags
    has_sugar = any(x in ings for x in ["sugar", "jaggery", "honey", "ketchup", "sweet corn", "ice cream", "oreo"])
    has_high_fat = any(x in ings for x in ["ghee", "butter", "cheese slice", "mozzarella cheese", "mayonnaise", "cream", "brain"])
    has_red_meat = any(x in ings for x in ["beef", "mutton", "liver"])
    has_white_meat = any(x in ings for x in ["chicken", "fish"])
    has_salt_high = any(x in ings for x in ["soy sauce", "salt", "pizza sauce", "ketchup"])
    has_veg = any(x in ings for x in ["spinach", "carrots", "peas", "capsicum", "cabbage", "tomatoes", "onions"])
    has_spices = any(x in ings for x in ["green chili", "red chili powder", "garam masala", "black pepper"])
    has_dairy = any(x in ings for x in ["milk", "yogurt", "paneer", "cheese", "cream"])
    is_fried = "fried" in name or "broast" in name or "burger" in name or "pizza" in name or "samosa" in name or "gol gappay" in name or "puri" in name
    has_carbs = any(x in ings for x in ["rice", "basmati rice", "wheat", "flour", "bread", "burger bun", "pizza dough", "semolina", "potatoes", "noodles"])
    
    # 1. diabetes_friendly (No sugar, preferably low carb)
    if not has_sugar and not is_fried:
        tags.add("diabetes_friendly")
    else:
        tags.discard("diabetes_friendly")
        
    # 2. hypertension_friendly (Low salt, no fast food)
    if not has_salt_high and not is_fried:
        tags.add("hypertension_friendly")
    else:
        tags.discard("hypertension_friendly")
        
    # 3. low_blood_pressure_friendly (Has salt/spices/energy)
    if has_salt_high or is_fried or has_spices:
        tags.add("low_blood_pressure_friendly")
    
    # 4. heart_friendly (No high fat, no red meat, no fast food)
    if not has_high_fat and not is_fried and not has_red_meat:
        tags.add("heart_friendly")
    else:
        tags.discard("heart_friendly")
        
    # 5. thyroid_friendly (General cooked foods, low soy)
    if "soy sauce" not in ings:
        tags.add("thyroid_friendly")
        
    # 6. pcos_friendly (High protein, low carb, low sugar)
    if (has_red_meat or has_white_meat or "eggs" in ings) and not has_sugar and not is_fried:
        tags.add("pcos_friendly")
        
    # 7. kidney_friendly (Low sodium, low protein, low dairy if strict, but general filter)
    if not has_salt_high and not has_red_meat and not is_fried:
        tags.add("kidney_friendly")
        
    # 8. joint_pain_friendly (Anti-inflammatory, fish, healthy, no sugar)
    if not has_sugar and not is_fried and not has_red_meat:
        tags.add("joint_pain_friendly")
        
    # 9. anemia_friendly (Iron rich)
    if has_red_meat or "spinach" in ings or "liver" in ings:
        tags.add("anemia_friendly")
        
    # 10. stomach_friendly (Not too spicy, no heavy frying)
    if not has_spices and not is_fried:
        tags.add("stomach_friendly")
    else:
        tags.discard("stomach_friendly")

    # Keep original standard tags
    if not has_high_fat and not is_fried:
        tags.add("low_fat")
    if not has_salt_high:
        tags.add("low_salt")

    r["health_tags"] = list(tags)

with open(file_path, "w") as f:
    json.dump(recipes, f, indent=2)

print("Health tags mapped correctly to all 10 conditions!")
