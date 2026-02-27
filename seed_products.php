<?php
require_once 'includes/db.php';

// 1. Update Database Schema to support new fields

// Modify categories.type to be VARCHAR(50) instead of ENUM to support new types like 'door', 'bathroom'
$alter_categories = "ALTER TABLE categories MODIFY COLUMN type VARCHAR(50) NOT NULL";
if ($conn->query($alter_categories) === TRUE) {
    echo "Updated categories table type column.\n";
} else {
    // Expected to fail if already varchar or similar, proceed anyway
    echo "Note: Categories table update (might already be updated): " . $conn->error . "\n";
}

// Add new columns to materials table if they don't exist
$new_columns = [
    "ADD COLUMN finish VARCHAR(100) AFTER price_per_sqft",
    "ADD COLUMN color VARCHAR(100) AFTER finish",
    "ADD COLUMN style VARCHAR(255) AFTER color",
    "ADD COLUMN dimensions VARCHAR(100) AFTER style" // For tiles size
];

foreach ($new_columns as $col_sql) {
    try {
        $sql = "ALTER TABLE materials " . $col_sql;
        if ($conn->query($sql) === TRUE) {
            echo "Added column.\n";
        }
    } catch (Exception $e) {
        // echo "Column likely exists. " . $e->getMessage() . "\n";
    }
}


// 2. Define Product Data with inferred types for Categories
// Mapping Category Name -> Type (floor, wall, ceiling, door, bathroom)
$category_types = [
    'Granite' => 'floor',
    'Marble' => 'floor',
    'Vitrified Tile' => 'floor',
    'Wooden Finish Tile' => 'floor',
    'Porcelain Tile' => 'floor',
    'Outdoor Tile' => 'floor',
    'False Ceiling' => 'ceiling',
    'Wooden Door' => 'door',
    'Engineered Door' => 'door',
    'Glass Door' => 'door',
    'Bathroom Basin' => 'bathroom',
    'Toilet' => 'bathroom',
    'Shower' => 'bathroom',
    'Faucet' => 'bathroom'
];

$products = [
    // 🪨 GRANITE
    [
        'name' => 'Black Galaxy Granite',
        'category' => 'Granite',
        'finish' => 'Polished',
        'color' => 'Deep black with gold specks',
        'style' => 'Premium luxury slab look',
        'price' => 250.00
    ],
    [
        'name' => 'Tan Brown Granite',
        'category' => 'Granite',
        'finish' => 'Polished',
        'color' => 'Dark brown with black & grey grains',
        'style' => 'Elegant modern countertop look',
        'price' => 180.00
    ],
    [
        'name' => 'Steel Grey Granite',
        'category' => 'Granite',
        'finish' => 'Matte / Leather finish',
        'color' => 'Medium grey with subtle texture',
        'style' => 'Contemporary minimalist slab',
        'price' => 160.00
    ],
    [
        'name' => 'Kashmir White Granite',
        'category' => 'Granite',
        'finish' => 'Polished',
        'color' => 'Off white with burgundy specks',
        'style' => 'Bright luxury interior look',
        'price' => 210.00
    ],
    [
        'name' => 'Absolute Black Granite',
        'category' => 'Granite',
        'finish' => 'High Gloss Polished',
        'color' => 'Pure deep black',
        'style' => 'Ultra modern premium slab',
        'price' => 300.00
    ],
    [
        'name' => 'Blue Pearl Granite',
        'category' => 'Granite',
        'finish' => 'Polished',
        'color' => 'Blue-grey with metallic shine',
        'style' => 'Imported high-end luxury look',
        'price' => 450.00
    ],

    // 🏔️ MARBLE
    [
        'name' => 'Makrana White Marble',
        'category' => 'Marble',
        'finish' => 'Polished',
        'color' => 'Pure white',
        'style' => 'Classic royal Indian flooring look',
        'price' => 500.00
    ],
    [
        'name' => 'Ambaji White Marble',
        'category' => 'Marble',
        'finish' => 'Polished',
        'color' => 'Milky white',
        'style' => 'Elegant bright interior look',
        'price' => 250.00
    ],
    [
        'name' => 'Statuario Marble',
        'category' => 'Marble',
        'finish' => 'High Gloss',
        'color' => 'White with bold grey veins',
        'style' => 'Italian luxury premium slab',
        'price' => 1200.00
    ],
    [
        'name' => 'Carrara White Marble',
        'category' => 'Marble',
        'finish' => 'Polished',
        'color' => 'Soft white with light grey veins',
        'style' => 'Modern luxury European look',
        'price' => 800.00
    ],
    [
        'name' => 'Calacatta Gold Marble',
        'category' => 'Marble',
        'finish' => 'Polished',
        'color' => 'White with gold & grey veins',
        'style' => 'Ultra premium designer look',
        'price' => 1500.00
    ],
    [
        'name' => 'Emperador Dark Marble',
        'category' => 'Marble',
        'finish' => 'Polished',
        'color' => 'Dark brown with light veins',
        'style' => 'Rich luxury interior style',
        'price' => 650.00
    ],

    // 🧱 TILES
    [
        'name' => 'Glossy White 600x600 Tile',
        'category' => 'Vitrified Tile',
        'finish' => 'Glossy',
        'color' => 'Pure white',
        'size' => '600x600 mm',
        'style' => 'Clean modern flooring look',
        'price' => 45.00
    ],
    [
        'name' => 'Matt Grey 800x800 Tile',
        'category' => 'Vitrified Tile',
        'finish' => 'Matte',
        'color' => 'Cement grey',
        'size' => '800x800 mm',
        'style' => 'Contemporary minimal design',
        'price' => 55.00
    ],
    [
        'name' => 'Wooden Oak Finish Tile',
        'category' => 'Wooden Finish Tile',
        'finish' => 'Matte',
        'color' => 'Natural oak brown',
        'size' => '1200x200 mm',
        'style' => 'Warm wooden flooring look',
        'price' => 70.00
    ],
    [
        'name' => 'Marble Finish Porcelain Tile',
        'category' => 'Porcelain Tile',
        'finish' => 'High Gloss',
        'color' => 'White with marble veins',
        'size' => '800x1600 mm',
        'style' => 'Luxury marble effect look',
        'price' => 90.00
    ],
    [
        'name' => 'Outdoor Anti-Skid Tile',
        'category' => 'Outdoor Tile',
        'finish' => 'Rough Anti-Skid',
        'color' => 'Stone grey',
        'size' => '600x600 mm',
        'style' => 'Exterior parking / balcony look',
        'price' => 40.00
    ],

    // 🏠 FALSE CEILING
    [
        'name' => 'Plain Gypsum Ceiling',
        'category' => 'False Ceiling',
        'finish' => 'Smooth white paint',
        'color' => 'White',
        'style' => 'Clean modern ceiling look',
        'price' => 85.00
    ],
    [
        'name' => 'Cove Lighting Gypsum Ceiling',
        'category' => 'False Ceiling',
        'finish' => 'Matte',
        'color' => 'White with warm LED lighting',
        'style' => 'Premium living room ceiling',
        'price' => 120.00
    ],
    [
        'name' => 'Wooden Finish PVC Ceiling',
        'category' => 'False Ceiling',
        'finish' => 'Matte textured',
        'color' => 'Natural wood brown',
        'style' => 'Warm decorative ceiling look',
        'price' => 95.00
    ],
    [
        'name' => 'Metal Grid Ceiling',
        'category' => 'False Ceiling',
        'finish' => 'Powder coated',
        'color' => 'White',
        'style' => 'Office modular ceiling design',
        'price' => 150.00
    ],

    // 🚪 DOORS
    [
        'name' => 'Teak Wood Main Door',
        'category' => 'Wooden Door',
        'finish' => 'Polished wood',
        'color' => 'Rich teak brown',
        'style' => 'Traditional premium entrance door',
        'price' => 15000.00 // Per piece assumed
    ],
    [
        'name' => 'Laminated Bedroom Door',
        'category' => 'Engineered Door',
        'finish' => 'Matte laminate',
        'color' => 'Walnut brown',
        'style' => 'Modern interior door look',
        'price' => 6000.00
    ],
    [
        'name' => 'Frosted Glass Door',
        'category' => 'Glass Door',
        'finish' => 'Frosted',
        'color' => 'Translucent white',
        'style' => 'Contemporary office look',
        'price' => 12000.00
    ],

    // 🛁 BATHROOM
    [
        'name' => 'Table Top Wash Basin',
        'category' => 'Bathroom Basin',
        'finish' => 'Glossy ceramic',
        'color' => 'White',
        'style' => 'Modern vanity top look',
        'price' => 3500.00
    ],
    [
        'name' => 'One Piece Western Toilet',
        'category' => 'Toilet',
        'finish' => 'Glossy ceramic',
        'color' => 'White',
        'style' => 'Contemporary bathroom design',
        'price' => 8500.00
    ],
    [
        'name' => 'Rain Shower Set',
        'category' => 'Shower',
        'finish' => 'Chrome polished',
        'color' => 'Silver',
        'style' => 'Luxury spa bathroom look',
        'price' => 5000.00
    ],
    [
        'name' => 'Designer Chrome Faucet',
        'category' => 'Faucet',
        'finish' => 'Mirror chrome',
        'color' => 'Silver',
        'style' => 'Premium modern fixture',
        'price' => 2500.00
    ]
];

// Helper to convert string to slug
function slugify($text)
{
    if (empty($text))
        return 'n-a';
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'n-a';
}

echo "Starting Seed Process...\n";

foreach ($products as $prod) {
    $category_name = $prod['category'];
    $category_slug = slugify($category_name);
    $category_type = isset($category_types[$category_name]) ? $category_types[$category_name] : 'other';

    // 1. Ensure Category Exists
    $check_cat = "SELECT id FROM categories WHERE slug = '$category_slug'";
    $res = $conn->query($check_cat);

    if ($res->num_rows > 0) {
        $cat_id = $res->fetch_assoc()['id'];
    } else {
        // Insert new category
        $stmt_cat = $conn->prepare("INSERT INTO categories (name, slug, type) VALUES (?, ?, ?)");
        $stmt_cat->bind_param("sss", $category_name, $category_slug, $category_type);
        if ($stmt_cat->execute()) {
            $cat_id = $stmt_cat->insert_id;
            echo "Created Category: $category_name\n";
        } else {
            echo "Error creating category $category_name: " . $conn->error . "\n";
            continue;
        }
    }

    // 2. Insert Material (Product)
    // Check if product exists (by name)
    $check_prod = $conn->prepare("SELECT id FROM materials WHERE name = ?");
    $check_prod->bind_param("s", $prod['name']);
    $check_prod->execute();
    $prod_res = $check_prod->get_result();

    if ($prod_res->num_rows == 0) {
        // Prepare Insert
        $size = isset($prod['size']) ? $prod['size'] : '';
        // Use style as description if necessary, or combine
        $description = $prod['style']; // Using style as description
        $image_url = ''; // As requested
        $texture_url = '';

        $stmt = $conn->prepare("INSERT INTO materials (category_id, name, description, price_per_sqft, finish, color, style, dimensions, image_url, texture_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "issdssssss",
            $cat_id,
            $prod['name'],
            $description,
            $prod['price'],
            $prod['finish'],
            $prod['color'],
            $prod['style'],
            $size,
            $image_url,
            $texture_url
        );

        if ($stmt->execute()) {
            $mat_id = $stmt->insert_id;
            // Add pivot entry
            $conn->query("INSERT INTO material_categories (material_id, category_id) VALUES ($mat_id, $cat_id)");
            echo "Inserted Product: {$prod['name']}\n";
        } else {
            echo "Error inserting {$prod['name']}: " . $stmt->error . "\n";
        }
    } else {
        echo "Product {$prod['name']} already exists. Skipping.\n";
    }
}

echo "Seeding Completed.\n";
?>