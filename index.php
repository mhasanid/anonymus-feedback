<?php
// Get the URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define the route
$feedback_route = '/feedback/';

// Check if the requested URI matches the feedback route
// if (strpos($uri, $feedback_route) === 0) {
//     // Extract the ID from the URI
//     $id = substr($uri, strlen($feedback_route));

//     // Process the request
//     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//         // Handle GET request
//         echo "Received GET request with ID: " . htmlspecialchars($id);
//         // header('Location: feedback.php');
//         // exit;
//     }
// }
    // } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     // Handle POST request
    //     $data = json_decode(file_get_contents('php://input'), true);
    //     echo "Received POST request with ID: " . htmlspecialchars($id) . " and data: " . json_encode($data);
    // }
// } else {
//     // Handle 404 Not Found
//     http_response_code(404);
//     echo "404 Not Found";
// }

// <?php
// require 'helpers.php';
// require 'database.php';

// Get the URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define routes and corresponding template files
$routes = [
    '/' => 'home.php',
    '/dashboard' => 'dashboard.php',
    '/register' => 'register.php',
    '/login' => 'login.php'
];

// Check if the requested URI matches any defined route
if (array_key_exists($uri, $routes)) {
    // Include the corresponding template file
    include $routes[$uri];
} elseif (preg_match('/^\/feedback\/([a-zA-Z0-9_-]+)$/', $uri, $matches)) {
    // Handle dynamic feedback route
    $id = $matches[1];
    include 'feedback.php';
}else {
    // Handle 404 Not Found
    http_response_code(404);
    // include '404.php';
}

?>