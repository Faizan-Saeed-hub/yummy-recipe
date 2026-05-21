import json

file_path = "models/pakistani_recipes.json"

with open(file_path, "r") as f:
    recipes = json.load(f)

for r in recipes:
    if "health_tags" not in r:
        r["health_tags"] = []
    
    # Simple logic for tags if list is empty or needs augmenting
    tags = set(r["health_tags"])
    ings = [i.lower() for i in r["ingredients"]]
    
    has_sugar = any(x in ings for x in ["sugar", "jaggery", "honey", "ketchup", "sweet corn", "ice cream", "oreo"])
    has_high_fat = any(x in ings for x in ["ghee", "butter", "cheese slice", "mozzarella cheese", "mayonnaise", "cream", "brain"])
    has_red_meat = any(x in ings for x in ["beef", "mutton", "liver"])
    has_white_meat = any(x in ings for x in ["chicken", "fish"])
    has_salt_high = any(x in ings for x in ["soy sauce", "salt", "pizza sauce"])
    has_veg = any(x in ings for x in ["spinach", "carrots", "peas", "capsicum", "cabbage", "tomatoes", "onions"])
    
    if not has_sugar and not has_high_fat:
        tags.add("diabetes_friendly")
        tags.add("heart_friendly")
    
    if has_red_meat or any(x in ings for x in ["spinach"]):
        tags.add("anemia_friendly")
        
    if not has_salt_high:
        tags.add("low_salt")
        
    if not has_high_fat and "fried" not in r["name"].lower() and "broast" not in r["name"].lower():
        tags.add("low_fat")

    if has_sugar:
        tags.discard("diabetes_friendly")
        
    if has_high_fat:
        tags.discard("heart_friendly")
        tags.discard("low_fat")

    r["health_tags"] = list(tags)

with open(file_path, "w") as f:
    json.dump(recipes, f, indent=2)

print("Health tags updated successfully!")
