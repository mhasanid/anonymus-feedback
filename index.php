<?php
// Get the URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// defining routes
$routes = [
    '/' => 'home.php',
    '/dashboard' => 'dashboard.php',
    '/register' => 'register.php',
    '/login' => 'login.php'
];

// handling routes and dynamic feedback page, also 404 page
if (array_key_exists($uri, $routes)) {
    include $routes[$uri];
} elseif (preg_match('/^\/feedback\/([a-zA-Z0-9_-]+)$/', $uri, $matches)) {
    $id = $matches[1];
    include 'feedback.php';
}else {
    http_response_code(404);
    include '404.php';
}

?>