<?php
require_once 'includes/db.php';


if (!function_exists('bcrypt')) {
    function bcrypt($password, $algo = PASSWORD_BCRYPT, $options = [])
    {
        return password_hash($password, $algo, $options);
    }
}

// SQL to create tables
$tables = [
    "CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100),
        phone VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        slug VARCHAR(50) NOT NULL UNIQUE,
        type ENUM('wall', 'floor', 'ceiling') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS materials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price_per_sqft DECIMAL(10, 2) NOT NULL,
        image_url VARCHAR(255),
        texture_url VARCHAR(255),
        crispy_words TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id)
    )",
    "CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        message TEXT,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS quotes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        material_id INT,
        user_name VARCHAR(100) NOT NULL,
        user_phone VARCHAR(20) NOT NULL,
        area_sqft DECIMAL(10, 2) NOT NULL,
        total_price DECIMAL(10, 2) NOT NULL,
        status ENUM('pending', 'contacted', 'completed') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (material_id) REFERENCES materials(id)
    )",
    "CREATE TABLE IF NOT EXISTS material_categories (
        material_id INT,
        category_id INT,
        PRIMARY KEY (material_id, category_id),
        FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS feedback (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100),
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

// Alter materials table to add crispy_words if it doesn't exist (for existing databases)
$check_column = "SHOW COLUMNS FROM materials LIKE 'crispy_words'";
$result = $conn->query($check_column);
if ($result->num_rows == 0) {
     $conn->query("ALTER TABLE materials ADD COLUMN crispy_words TEXT");
     echo "Added crispy_words column to materials table.\n";
}

foreach ($tables as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully\n";
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
}

// Seed Admin User (admin / password)
$admin_pass = bcrypt("password", PASSWORD_DEFAULT);
$check_admin = "SELECT * FROM admin_users WHERE username = 'admin'";
$result = $conn->query($check_admin);

if ($result->num_rows == 0) {
    $sql_admin = "INSERT INTO admin_users (username, password) VALUES ('admin', '$admin_pass')";
    if ($conn->query($sql_admin) === TRUE) {
        echo "Admin user created (admin/password)\n";
    } else {
        echo "Error creating admin user: " . $conn->error . "\n";
    }
}

// Seed Categories
$categories = [
    ['Modern Wall', 'modern-wall', 'wall'],
    ['Classic Floor', 'classic-floor', 'floor'],
    ['False Ceiling', 'false-ceiling', 'ceiling']
];

foreach ($categories as $cat) {
    $check_cat = "SELECT * FROM categories WHERE slug = '$cat[1]'";
    if ($conn->query($check_cat)->num_rows == 0) {
        $sql_cat = "INSERT INTO categories (name, slug, type) VALUES ('$cat[0]', '$cat[1]', '$cat[2]')";
        $conn->query($sql_cat);
        echo "Category $cat[0] created.\n";
    }
}

echo "Database setup completed.";
?>