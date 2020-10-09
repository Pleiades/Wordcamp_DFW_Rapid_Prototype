<?php

namespace RapidPrototype;

class Restaurant extends \Faker\Provider\Base
{

    protected static $foodNames = array(
        'SPAM'
    );

    protected static $beverageNames = array(
        'Coolaid'
    );

    protected static $meatNames = array(
        "Bacon", "Boudin ", "Buffalo", "Chicken", "Corned Beef", "Ham Hocks", "Kielbasa", "Pastrami", "Pork Loin ", "Pork Tenderloin", "Spare Ribs ", "Turducken", "Porchetta", "Meatloaf", "Bresaola", "Picanha", "Salami", "Pork Chop", "Meatballs", "Leberkas", "Prosciutto", "Venison", "Turkey", "Biltong", "Chislic", "Ham", "Andouille Sausage", "Beef Ribs", "Veal", "Lamb", "Duck", "Chuck Shoulder Pot Roast", "Chuck 7-Bone Pot Roast", "Cross Rib Roast", "Braising Steak", "Flat Iron Steak", "Book Steak", "Butler Steak", "Lifter Steak", "Petite Steak", "Top Chuck Steak", "Boneless, Blade Steak", "Chuck Eye Steak", "Shoulder Steak, boneless", "Cold Steak", "English Steak", "Long Broil", "Shoulder Steak Half Cut", "Arm Swiss Steak", "Chuck Steak", "Chuck Arm Steak", "Arm Swiss Steak", "Round Bone Steak", "Chuck-Eye Steak, boneless", "Boneless Chuck Fillet Steak", "Boneless Steak", "Bottom Chuck", "Boneless Chuck Slices", "Chuck Mock Tender Steak", "Chuck-Eye Steak", "Chuck Fillet Steak", "Fish Steak", "Chuck Tender Steak", "Center Chuck Steak", "Rib Roast", "Rib Eye Roast", "Standing Rib Roast", "T-Bone steak", "Porterhouse", "Tenderloin Steak", "Filet Mignon", "Fillet Steak", "Chateaubriand", "Top Loin Steak, boneless", "Strip Steak", "Kansas City Steak", "New York Strip Steak", "Ambassador Steak", "Boneless Club Steak", "Hotel-Style Steak", "Veiny Steak", "Strip Filet Steak", "Top Loin Steak, bone-in", "Strip Steak", "Sirloin Strip Steak", "Chip Club Steak", "Club Steak", "Country Club Steak", "Delmonico Steak", "Shell Steak", "Ribeye Steak", "Boneless Rib Steak", "Rib Steak", "Ribeye Cap Steak", "Tri-Tip Roast", "Sirloin Steak", "Top Sirloin Cap Steak (Coulotte Steak)", "Santa Maria Steak", "Bottom Round Roast", "Eye Round Roast", "Pikes Peak Roast", "Round Tip Roast", "Rump Roast", "Tip Roast", "Round Tip Steak, thin cut", "Ball Tip Steak", "Beef Sirloin Tip Steak", "Breakfast Steak", "Knuckle Steak", "Sandwich Steak", "Minute Steak", "Round Steak", "Top Round London Broil", "Top Round Steak", "Brisket", "Brisket Flat Cut", "Skirt Steak", "Fajita", "Flank Steak",
    );

    protected static $meatPreparation = array(
        "Pickle-Brined", "Griddled", "Smoked", "Nigerian Clay Pot", "Roasted", "Skillet-Roasted", "Fried", "Pan-Fried", "Braised", "Oven-Braised", "Brined", "Dry-Brined", "Baked", "Poached", "Grilled", "Sautéed",
    );

    protected static $meatSauces = array(
        "Asian Black Bean", "Cola BBQ", "Salted Caramel", "Sweet and Sour", "Tomato and Basil", "Schezwan", "Garlicky Tahini", "Walnut Encrusted", "Mexican Barbeque", "Bolognese",
    );

    protected static $vegetableSauce = array(
        "Hoisin Ginger Sauce", "Herbed Goat Cheese Sauce", "Mediterranean Olive Sauce", "Spicy Sriracha Peanut Sauce", "Orange Tahini Sauce", "Hollandaise Sauce", "Creamy Brown Butter Sauce", "Chimichurri Sauce", "Shallot Vinaigrette", "Walnut Dressing", "Fonduta", "Aioli Sauce", "Yogurt sauce", "Chile Butter", "Romesco", "Nuoc Cham", "Vinegar Sauce", "Sweet Mustard Sauce", "Carrot and Pepper Sauce", "B&eacute;chamel", "Veloute", "Asian Black Bean Sauce", "Cola BBQ Sauce", "Salted Caramel Sauce", "Sweet and Sour Sauce", "Tomato and Basil Sauce", "Schezwan Sauce", "Garlicky Tahini Sauce", "Walnut Sauce", "White Sauce", "Aubergine Chermoula Sauce", "Mexican Barbeque Sauce", "Bolognese Sauce Recipe",
    );

    protected static $vegetableNames = array(
        "Artichokes", "Jerusalem Artichokes", "Asian greens", "Asparagus", "Beans", "Beetroot", "Broccoli", "Brussels sprouts", "Cabbages", "Capsicums", "Carrots", "Cauliflower", "Celeriac", "Celery", "Chilli peppers", "Chokos", "Courgettes", "Scallopini", "Cucumber", "Eggplant", "Fennel", "Garlic", "Ginger", "Indian vegetables", "Kale", "Cavolo Nero", "Kohlrabi", "Kūmara", "Leeks", "Lettuce", "Melons", "Microgreens", "Mushrooms", "Okra", "Onions", "Parsnips", "Peas", "Potatoes", "purple (taewa) Potatoes", "Puha", "Pumpkin", "Radishes", "Rhubarb", "Salad greens", "Shallots", "Silverbeet", "Spinach", "Spring onions", "Sprouted beans", "Swedes", "Sweet corn", "Taro", "Tomatoes", "Turnips", "Watercress", "Witloof"
    );

    protected static $vegetablePreparation = array(
        'Boiled', 'Steamed', 'Sautéd', 'Stir-Fried', 'Braised', 'Stewed', 'Roasted', 'Pan-Roasted','Baked', 'Fried', 'Grilled', 'Pickled'
    );

    protected static $spiceNames = array(
        "allspice", "angelica", "anise", "asafoetida", "basil", "bay leaf", "bergamot", "black cumin", "black mustard", "black pepper", "borage", "brown mustard", "burnet", "caraway", "cardamom", "cassia", "catnip", "cayenne pepper", "celery seed", "chervil", "chicory", "chili pepper", "chives", "cicely", "cilantro", "cinnamon", "clove", "coriander", "costmary", "cumin", "curry", "dill", "fennel", "fenugreek", "filé", "ginger", "grains of paradise", "holy basil", "horehound", "horseradish", "hyssop", "lavender", "lemon balm", "lemon grass", "lemon verbena", "licorice", "lovage", "mace", "marjoram", "nutmeg", "oregano", "paprika", "parsley", "peppermint", "poppy seed", "rosemary", "rue", "saffron", "sage", "savory", "sesame", "sorrel", "spearmint", "star anise", "tarragon", "thyme", "turmeric", "vanilla", "wasabi", "white mustard",
    );

    /**
     * A random Food Name.
     * @return string
     */
    public function entre()
    {
        return static::randomElement(static::$foodNames);
    }

    /**
     * A random Beverage Name.
     * @return string
     */
            public function beverageName()
    {
        return static::randomElement(static::$beverageNames);
    }

    /**
     * A random Meat Name.
     * @return string
     */
    public function meatName()
    {
        return static::randomElement(static::$meatNames);
    }

    /**
     * A random Meat Preparation Method.
     * @return string
     */
    public function meatPrep()
    {
        return static::randomElement(static::$meatPreparation);
    }

    /**
     * A random Meat Sauce Name.
     * @return string
     */
    public function meatSauce()
    {
        return static::randomElement(static::$meatSauces);
    }

    /**
     * A random Vegetable Sauce Name.
     * @return string
     */
    public function vegetableSauce()
    {
        return static::randomElement(static::$vegetableSauce);
    }

    /**
     * A random Vegetable Name.
     * @return string
     */
    public function vegetableName()
    {
        return static::randomElement(static::$vegetableNames);
    }

    /**
     * A random Vegetable Preparation Method.
     * @return string
     */
    public function vegetablePrep()
    {
        return static::randomElement(static::$vegetablePreparation);
    }

    /**
     * A random Spice Name.
     * @return string
     */
    public function spiceName()
    {
        return static::randomElement(static::$spiceNames);
    }
}
// Vegetable Dish: vegetablePrep vegetableName spiceName sauceName
// Bad data
// Add full array for import into PODS as a sorted select list
// Style: American, Asian, Asian-style, Ancho