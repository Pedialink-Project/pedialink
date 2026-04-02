<?php

namespace Tests\Feature;

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class RouteRegistryFeatureTest extends TestCase
{
    public function testWebRoutesFileReturnsAConfiguredRouteList(): void
    {
        $routes = require __DIR__ . '/../../routes/web.php';

        $this->assertIsArray($routes);
        $this->assertNotEmpty($routes);
    }

    public function testHomeRouteIsRegistered(): void
    {
        $routes = require __DIR__ . '/../../routes/web.php';

        $homeRoute = null;

        foreach ($routes as $route) {
            if (($route[0] ?? null) === 'GET' && ($route[1] ?? null) === '/') {
                $homeRoute = $route;
                break;
            }
        }

        $this->assertNotNull($homeRoute);
        $this->assertSame('home', $homeRoute[3] ?? null);
    }
}
