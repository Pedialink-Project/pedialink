<?php

namespace App\Providers;

use Library\Framework\Core\Application;
use Library\Framework\Core\Provider;
use Library\Framework\Storage\Storage;

class StorageProvider extends Provider
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        $this->app->singleton(Storage::class, function () {
            return new Storage([
                'default' => config('storage.default'),
                'secret' => config('storage.secret'),
                'disks' => config('storage.disks'),
                'temp_lifetime' => config('storage.temp_lifetime')
            ]);
        });
    }

    public function boot()
    {

    }
}