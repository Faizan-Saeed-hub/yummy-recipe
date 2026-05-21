import json
import os

recipes_file = "models/pakistani_recipes.json"
ingredients_file = "models/pakistani_ingredients.json"
dictionary_file = "storage/app/ingredient_dictionary.json"

new_recipes = [
    {
        "name": "Kheer", "cuisine": "Pakistani", "category": "Dessert",
        "ingredients": ["milk", "rice", "sugar", "green cardamom", "almonds", "cashew nuts"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "Traditional rice pudding flavored with cardamom and nuts."
    },
    {
        "name": "Zarda", "cuisine": "Pakistani", "category": "Dessert",
        "ingredients": ["basmati rice", "sugar", "ghee", "food color", "almonds", "green cardamom"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "Sweet yellow rice cooked with nuts and aromatic spices."
    },
    {
        "name": "Haleem", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["beef", "wheat", "barley", "lentils", "onions", "ginger", "garlic", "garam masala", "lemon"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": ["heart_friendly"], "age_groups": ["adult", "all"],
        "description": "A slow-cooked stew of meat, lentils, and wheat, garnished with fried onions and lemon."
    },
    {
        "name": "Chicken Handi", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["chicken", "tomatoes", "yogurt", "cream", "ginger", "garlic", "black pepper", "coriander"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "Boneless chicken cooked in a creamy tomato base, traditionally prepared in a clay pot."
    },
    {
        "name": "Sindhi Biryani", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["mutton", "basmati rice", "potatoes", "tomatoes", "yogurt", "plums", "mint", "green chili", "garam masala"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["adult", "all"],
        "description": "Spicy and tangy meat biryani with potatoes and dried plums."
    },
    {
        "name": "Beef Pulao", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["beef", "basmati rice", "onions", "ginger", "garlic", "garam masala", "coriander"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": ["heart_friendly"], "age_groups": ["adult", "all"],
        "description": "A savory rice dish cooked in a rich beef broth."
    },
    {
        "name": "Chicken Jalfrezi", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["chicken", "capsicum", "onions", "tomatoes", "ginger", "garlic", "soy sauce", "vinegar"],
        "diet_tags": ["non_vegetarian", "high_protein", "low_fat"], "health_tags": ["diabetes_friendly", "heart_friendly"], "age_groups": ["all"],
        "description": "A stir-fry curry featuring chicken, bell peppers, and onions in a thick, spicy tomato sauce."
    },
    {
        "name": "Keema Mattar", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["beef", "peas", "onions", "tomatoes", "ginger", "garlic", "cumin", "garam masala"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": ["heart_friendly"], "age_groups": ["all"],
        "description": "Minced meat cooked with green peas and warm spices."
    },
    {
        "name": "Lassi", "cuisine": "Pakistani", "category": "Beverage",
        "ingredients": ["yogurt", "milk", "sugar"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "A classic, refreshing yogurt-based sweet drink."
    },
    {
        "name": "Gajar Ka Halwa", "cuisine": "Pakistani", "category": "Dessert",
        "ingredients": ["carrots", "milk", "sugar", "ghee", "almonds", "green cardamom"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "Rich carrot pudding slow-cooked in milk and ghee, garnished with nuts."
    },
    {
        "name": "Bun Kebab", "cuisine": "Pakistani", "category": "Fast Food",
        "ingredients": ["burger bun", "beef", "lentils", "eggs", "onions", "mint", "tamarind"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "The quintessential Pakistani street food burger with a spicy meat and lentil patty."
    },
    {
        "name": "Dahi Baray", "cuisine": "Pakistani", "category": "Snack",
        "ingredients": ["gram flour", "yogurt", "tamarind", "sugar", "red chili powder", "cumin"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "Soft lentil fritters soaked in sweet and spicy yogurt, topped with chutneys."
    },
    {
        "name": "Samosa Chaat", "cuisine": "Pakistani", "category": "Snack",
        "ingredients": ["potatoes", "wheat flour", "chickpeas", "yogurt", "tamarind", "mint", "onions", "tomatoes"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "Crushed samosas topped with spicy chickpea curry, yogurt, and chutneys."
    },
    {
        "name": "Gol Gappay", "cuisine": "Pakistani", "category": "Snack",
        "ingredients": ["semolina", "chickpeas", "potatoes", "tamarind", "mint", "red chili powder"],
        "diet_tags": ["vegetarian", "vegan", "low_fat"], "health_tags": [], "age_groups": ["all"],
        "description": "Crispy hollow shells filled with chickpeas and potatoes, served with tangy tamarind water."
    },
    {
        "name": "Chicken Shashlik", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["chicken", "capsicum", "onions", "tomatoes", "ketchup", "soy sauce", "vinegar"],
        "diet_tags": ["non_vegetarian", "high_protein", "low_fat"], "health_tags": [], "age_groups": ["all"],
        "description": "Grilled chicken skewers served in a tangy, sweet, and spicy tomato sauce."
    },
    {
        "name": "Mutton Roast", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["mutton", "yogurt", "ginger", "garlic", "lemon", "garam masala"],
        "diet_tags": ["non_vegetarian", "high_protein", "low_carb"], "health_tags": ["heart_friendly"], "age_groups": ["adult"],
        "description": "Slow-roasted leg of mutton marinated in aromatic spices."
    },
    {
        "name": "Brain Masala", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["brain", "onions", "tomatoes", "ginger", "garlic", "turmeric", "green chili"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["adult"],
        "description": "Rich and spicy curry made with cow or goat brain."
    },
    {
        "name": "Liver Masala", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["liver", "onions", "tomatoes", "garlic", "ginger", "garam masala"],
        "diet_tags": ["non_vegetarian", "high_protein", "low_carb"], "health_tags": ["anemia_friendly"], "age_groups": ["adult"],
        "description": "A quick, spicy stir-fry of beef or mutton liver."
    },
    {
        "name": "Bihari Kebab", "cuisine": "Pakistani", "category": "Snack",
        "ingredients": ["beef", "yogurt", "mustard oil", "ginger", "garlic", "garam masala"],
        "diet_tags": ["non_vegetarian", "high_protein", "low_carb"], "health_tags": [], "age_groups": ["adult"],
        "description": "Melt-in-your-mouth spicy beef strips marinated in mustard oil and raw papaya."
    },
    {
        "name": "Chicken Corn Soup", "cuisine": "Pakistani", "category": "Appetizer",
        "ingredients": ["chicken", "sweet corn", "eggs", "corn flour", "soy sauce", "black pepper"],
        "diet_tags": ["non_vegetarian", "low_fat"], "health_tags": ["heart_friendly"], "age_groups": ["all"],
        "description": "A comforting, thick soup with shredded chicken and sweet corn."
    },
    {
        "name": "Lahori Fried Fish", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["fish", "gram flour", "lemon", "ajwain", "red chili powder", "ginger", "garlic"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "Spicy, crispy battered fish fried to perfection, famous in Lahore."
    },
    {
        "name": "Chicken Manchurian", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["chicken", "ketchup", "soy sauce", "garlic", "corn flour", "capsicum"],
        "diet_tags": ["non_vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "Indo-Chinese style chicken chunks in a spicy, tangy gravy."
    },
    {
        "name": "Egg Fried Rice", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["basmati rice", "eggs", "carrots", "cabbage", "capsicum", "soy sauce"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "Wok-tossed rice with scrambled eggs and crunchy vegetables."
    },
    {
        "name": "Chicken Chow Mein", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["noodles", "chicken", "cabbage", "carrots", "capsicum", "soy sauce", "garlic"],
        "diet_tags": ["non_vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "Stir-fried noodles with chicken strips and julienned vegetables."
    },
    
    # Fast Food & Shakes
    {
        "name": "Zinger Burger", "cuisine": "Fast Food", "category": "Fast Food",
        "ingredients": ["burger bun", "chicken thigh", "wheat flour", "corn flour", "mayonnaise", "lettuce"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "Crispy, deep-fried battered chicken thigh served in a soft bun with mayonnaise."
    },
    {
        "name": "Beef Burger", "cuisine": "Fast Food", "category": "Fast Food",
        "ingredients": ["burger bun", "beef", "cheese slice", "onions", "tomatoes", "ketchup", "lettuce"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "A classic juicy beef patty burger with cheese and fresh veggies."
    },
    {
        "name": "Chicken Fajita Pizza", "cuisine": "Fast Food", "category": "Fast Food",
        "ingredients": ["pizza dough", "mozzarella cheese", "chicken", "capsicum", "onions", "pizza sauce", "olives"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "Spicy fajita chicken chunks baked on a cheesy pizza crust."
    },
    {
        "name": "Chicken Tikka Pizza", "cuisine": "Fast Food", "category": "Fast Food",
        "ingredients": ["pizza dough", "mozzarella cheese", "chicken", "onions", "pizza sauce", "garam masala"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "A fusion pizza topped with spicy Pakistani chicken tikka chunks."
    },
    {
        "name": "Banana Shake", "cuisine": "Beverage", "category": "Beverage",
        "ingredients": ["banana", "milk", "sugar"],
        "diet_tags": ["vegetarian"], "health_tags": ["kidney_friendly"], "age_groups": ["all"],
        "description": "A creamy and refreshing banana milkshake."
    },
    {
        "name": "Apple Shake", "cuisine": "Beverage", "category": "Beverage",
        "ingredients": ["apple", "milk", "sugar"],
        "diet_tags": ["vegetarian"], "health_tags": ["heart_friendly"], "age_groups": ["all"],
        "description": "A healthy and sweet apple milkshake."
    },
    {
        "name": "Strawberry Shake", "cuisine": "Beverage", "category": "Beverage",
        "ingredients": ["strawberry", "milk", "sugar", "ice cream"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "A rich and thick strawberry milkshake."
    },
    {
        "name": "Mango Shake", "cuisine": "Beverage", "category": "Beverage",
        "ingredients": ["mango", "milk", "sugar"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "The king of shakes in Pakistan, made with sweet summer mangoes."
    },
    {
        "name": "Oreo Shake", "cuisine": "Beverage", "category": "Beverage",
        "ingredients": ["oreo", "milk", "ice cream", "sugar"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "A decadent milkshake blended with chocolate cream cookies."
    },
    {
        "name": "Cold Coffee", "cuisine": "Beverage", "category": "Beverage",
        "ingredients": ["coffee", "milk", "sugar", "ice cream"],
        "diet_tags": ["vegetarian"], "health_tags": [], "age_groups": ["all"],
        "description": "Frothy, chilled blended coffee."
    },
    {
        "name": "French Fries", "cuisine": "Fast Food", "category": "Fast Food",
        "ingredients": ["potatoes", "oil", "salt"],
        "diet_tags": ["vegetarian", "vegan"], "health_tags": [], "age_groups": ["all"],
        "description": "Deep-fried, crispy potato sticks."
    },
    {
        "name": "Chicken Nuggets", "cuisine": "Fast Food", "category": "Fast Food",
        "ingredients": ["chicken", "wheat flour", "eggs", "salt", "black pepper", "oil"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "Bite-sized, breaded and fried chicken pieces."
    },
    {
        "name": "Shawarma", "cuisine": "Fast Food", "category": "Fast Food",
        "ingredients": ["bread", "chicken", "mayonnaise", "cabbage", "carrots", "garlic", "vinegar"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "Middle-Eastern inspired wrap filled with spiced chicken and garlic sauce."
    },
    {
        "name": "Fried Chicken (Broast)", "cuisine": "Fast Food", "category": "Fast Food",
        "ingredients": ["chicken", "wheat flour", "corn flour", "eggs", "garlic", "red chili powder", "oil"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "Classic bone-in crispy fried chicken."
    },
    {
        "name": "Club Sandwich", "cuisine": "Fast Food", "category": "Fast Food",
        "ingredients": ["bread", "chicken", "eggs", "mayonnaise", "lettuce", "tomatoes"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": [], "age_groups": ["all"],
        "description": "A multi-layered sandwich filled with chicken, eggs, and mayonnaise."
    },
    {
        "name": "Chicken Pulao", "cuisine": "Pakistani", "category": "Main Course",
        "ingredients": ["chicken", "basmati rice", "onions", "ginger", "garlic", "garam masala", "coriander"],
        "diet_tags": ["non_vegetarian", "high_protein"], "health_tags": ["heart_friendly"], "age_groups": ["all"],
        "description": "A light and savory rice dish cooked with chicken broth."
    }
]

new_mappings = {
    "sugar": { "usda_query": "sugar granulated", "default_grams": 20 },
    "wheat": { "usda_query": "wheat grain", "default_grams": 50 },
    "barley": { "usda_query": "barley pearled raw", "default_grams": 30 },
    "food color": { "usda_query": "spices", "default_grams": 1 },
    "capsicum": { "usda_query": "peppers sweet green raw", "default_grams": 50 },
    "mango": { "usda_query": "mango raw", "default_grams": 150 },
    "banana": { "usda_query": "banana raw", "default_grams": 120 },
    "apple": { "usda_query": "apple raw", "default_grams": 150 },
    "strawberry": { "usda_query": "strawberries raw", "default_grams": 100 },
    "oreo": { "usda_query": "cookies chocolate sandwich", "default_grams": 30 },
    "coffee": { "usda_query": "coffee instant", "default_grams": 5 },
    "ice cream": { "usda_query": "ice cream vanilla", "default_grams": 100 },
    "pizza dough": { "usda_query": "pizza crust", "default_grams": 200 },
    "mozzarella cheese": { "usda_query": "cheese mozzarella", "default_grams": 80 },
    "burger bun": { "usda_query": "hamburger bun", "default_grams": 60 },
    "mayonnaise": { "usda_query": "mayonnaise", "default_grams": 20 },
    "ketchup": { "usda_query": "catsup", "default_grams": 20 },
    "soy sauce": { "usda_query": "soy sauce", "default_grams": 15 },
    "vinegar": { "usda_query": "vinegar cider", "default_grams": 10 },
    "corn flour": { "usda_query": "cornstarch", "default_grams": 15 },
    "noodles": { "usda_query": "noodles egg raw", "default_grams": 100 },
    "bread": { "usda_query": "bread white", "default_grams": 60 },
    "chicken thigh": { "usda_query": "chicken thigh raw", "default_grams": 150 },
    "liver": { "usda_query": "beef liver raw", "default_grams": 100 },
    "brain": { "usda_query": "beef brain raw", "default_grams": 100 },
    "mustard oil": { "usda_query": "oil mustard", "default_grams": 15 },
    "sweet corn": { "usda_query": "corn sweet raw", "default_grams": 50 },
    "lettuce": { "usda_query": "lettuce raw", "default_grams": 20 },
    "cheese slice": { "usda_query": "cheese pasteurized process american", "default_grams": 21 },
    "pizza sauce": { "usda_query": "tomato sauce", "default_grams": 50 },
    "olives": { "usda_query": "olives ripe canned", "default_grams": 15 },
    "oil": { "usda_query": "oil vegetable", "default_grams": 20 },
    "ajwain": { "usda_query": "spices caraway seed", "default_grams": 2 },
    "chickpeas": { "usda_query": "chickpeas raw", "default_grams": 100 },
    "semolina": { "usda_query": "semolina", "default_grams": 30 },
    "plums": { "usda_query": "plums dried (prunes)", "default_grams": 20 }
}

def update_json_array(filepath, new_items, key_func=lambda x: x):
    try:
        with open(filepath, 'r') as f:
            data = json.load(f)
    except FileNotFoundError:
        data = []
        
    existing_keys = {key_func(item) for item in data}
    for item in new_items:
        if key_func(item) not in existing_keys:
            data.append(item)
            
    with open(filepath, 'w') as f:
        json.dump(data, f, indent=2)

def update_json_dict(filepath, new_dict):
    try:
        with open(filepath, 'r') as f:
            data = json.load(f)
    except FileNotFoundError:
        data = {}
        
    for k, v in new_dict.items():
        if k not in data:
            data[k] = v
            
    with open(filepath, 'w') as f:
        json.dump(data, f, indent=4)

if __name__ == "__main__":
    # 1. Update recipes
    update_json_array(recipes_file, new_recipes, lambda x: x["name"])
    print(f"Updated {recipes_file} with {len(new_recipes)} recipes.")
    
    # 2. Update ingredients list
    new_ingredient_names = []
    for r in new_recipes:
        for ing in r["ingredients"]:
            new_ingredient_names.append(ing)
    new_ingredient_names = list(set(new_ingredient_names))
    update_json_array(ingredients_file, new_ingredient_names, lambda x: x)
    print(f"Updated {ingredients_file} with new unique ingredients.")
    
    # 3. Update dictionary mapping
    update_json_dict(dictionary_file, new_mappings)
    print(f"Updated {dictionary_file} with {len(new_mappings)} new mappings.")
    
    print("Done!")
