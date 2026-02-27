<?php
// Router for Interior Design Viewer

$request_uri = $_SERVER['REQUEST_URI'];
$parsed_url = parse_url($request_uri);
$path = $parsed_url['path'];

// Remove query string (handled by parse_url) and decode path
$path = urldecode($path);

// If the requested file exists (e.g. css/js/images), serve it directly
if (file_exists(__DIR__ . $path) && !is_dir(__DIR__ . $path)) {
    return false;
}

// Normalize path: ignore trailing slash unless it is root
if ($path !== '/' && substr($path, -1) === '/') {
    $path = rtrim($path, '/');
}

// Define valid routes (Virtual Paths -> Physical Files)
$routes = [
    '/' => 'pages/home.php',
    '/home' => 'pages/home.php',
    '/about' => 'pages/about.php',
    '/budget' => 'pages/budget.php',
    '/contact' => 'pages/contact.php',
    '/materials' => 'pages/materials.php',
    '/material-details' => 'pages/material-details.php',
    '/visualizer' => 'pages/visualizer.php',
    '/wishlist' => 'pages/wishlist.php',

    // Admin Routes
    '/admin' => 'admin/login.php',
    '/admin/login' => 'admin/login.php',
    '/admin/dashboard' => 'admin/dashboard.php',
    '/admin/materials' => 'admin/materials.php',
    '/admin/quotes' => 'admin/quotes.php',
    '/admin/feedback' => 'admin/feedback.php',
    '/admin/users' => 'admin/users.php',
    '/admin/logout' => 'admin/logout.php',

    // User Routes
    '/login' => 'pages/login.php',
    '/register' => 'pages/register.php',
    '/logout' => 'pages/logout.php',
    '/profile' => 'pages/profile.php',
];

// Check if route exists
if (array_key_exists($path, $routes)) {
    // Check if file exists before requiring
    if (file_exists($routes[$path])) {
        require_once $routes[$path];
    } else {
        http_response_code(500);
        echo "<h1>500 - System Error</h1>";
        echo "<p>Route defined but file not found: " . htmlspecialchars($routes[$path]) . "</p>";
    }
} else {
    // 404 Not Found
    http_response_code(404);
    // You could include a custom 404 page here
    echo "<!DOCTYPE html><html><head><title>404 Not Found</title></head><body style='background:#050505; color:white; text-align:center; padding-top:100px; font-family:sans-serif;'>";
    echo "<h1 style='color:#D4AF37;'>404</h1>";
    echo "<h2>Page Not Found</h2>";
    echo "<p>The page you requested ($path) could not be found.</p>";
    echo "<a href='/' style='color:#D4AF37; text-decoration:none; border:1px solid #D4AF37; padding:10px 20px; border-radius:5px;'>Return Home</a>";
    echo "</body></html>";
}
?>