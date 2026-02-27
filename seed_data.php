<?php
require_once 'includes/db.php';

$materials = [
    [
        'category_slug' => 'modern-wall',
        'name' => 'Midnight Blue Velvet',
        'description' => 'A luxurious dark blue textured wall finish that adds depth and elegance.',
        'price' => 15.50,
        'image' => 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=500&auto=format&fit=crop&q=60' // Textured blue
    ],
    [
        'category_slug' => 'modern-wall',
        'name' => 'Abstract Concrete',
        'description' => 'Industrial chic concrete finish for modern apartments.',
        'price' => 12.00,
        'image' => 'https://images.unsplash.com/photo-1560114928-40f1f1eb26a0?w=500&auto=format&fit=crop&q=60' // Concrete texture
    ],
    [
        'category_slug' => 'classic-floor',
        'name' => 'Royal Oak Harringbone',
        'description' => 'Classic wooden flooring in a timeless herringbone pattern.',
        'price' => 25.00,
        'image' => 'https://images.unsplash.com/photo-1516455590571-18256e5bb9ff?w=500&auto=format&fit=crop&q=60' // Wood pattern
    ],
    [
        'category_slug' => 'classic-floor',
        'name' => 'Italian Marble White',
        'description' => 'Premium white marble with subtle grey veining.',
        'price' => 45.00,
        'image' => 'https://images.unsplash.com/photo-1598972583279-880227184288?w=500&auto=format&fit=crop&q=60' // Marble texture
    ],
    [
        'category_slug' => 'false-ceiling',
        'name' => 'Textured Gypsum Panel',
        'description' => 'Geometric patterns for a modern ceiling look.',
        'price' => 8.50,
        'image' => 'https://images.unsplash.com/photo-1628191238198-d0692d9c1226?w=500&auto=format&fit=crop&q=60' // Just a texture example
    ]
];

foreach ($materials as $m) {
    // Get Cat ID
    $cat_res = $conn->query("SELECT id FROM categories WHERE slug = '{$m['category_slug']}'");
    if ($cat_res->num_rows > 0) {
        $cat_id = $cat_res->fetch_assoc()['id'];

        // Insert
        // Use the same image for texture_url for simplicity in this demo
        $stmt = $conn->prepare("INSERT INTO materials (category_id, name, description, price_per_sqft, image_url, texture_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdss", $cat_id, $m['name'], $m['description'], $m['price'], $m['image'], $m['image']);
        if ($stmt->execute()) {
            $mat_id = $conn->insert_id;
            $conn->query("INSERT INTO material_categories (material_id, category_id) VALUES ($mat_id, $cat_id)");
            echo "Inserted {$m['name']}\n";
        }
    }
}
echo "Sample materials seeded.";
?>