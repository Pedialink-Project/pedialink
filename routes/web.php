<?php

$allRoutes = [];

$routeFiles = [
    'home.php',
];

foreach ($routeFiles as $file) {
    $allRoutes = array_merge($allRoutes, require __DIR__ . "/sectors/$file");
}

/**
 * Add your common routes by uncommenting the below code
 */

// $commonRoutes = [
//     ['METHOD', 'URI', [Controller::class, 'method'], 'name', ['middlewares']]
// ];

// $allRoutes = array_merge($allRoutes, $commonRoutes);

return $allRoutes;