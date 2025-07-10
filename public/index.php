<?php

require __DIR__ . '/../vendor/autoload.php';

/** @var \Library\Framework\Core\Application */
$app = require __DIR__ . '/../bootstrap/app.php';

$request = Library\Framework\Http\Request::capture();

/** @var \Library\Framework\Http\Response $response */
$response = $app->make(Library\Framework\Routing\Router::class)->dispatch($request);

$response->send();