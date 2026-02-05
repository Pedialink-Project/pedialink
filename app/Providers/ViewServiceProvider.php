<?php

namespace App\Providers;

use Library\Framework\Core\Application;
use Library\Framework\Core\Provider;
use Library\Framework\View\View;
use App\Services\NotificationService;

class ViewServiceProvider extends Provider
{
    /**
     * @var Application
     */
    protected Application $app;

    /**
     * @param \Library\Framework\Core\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        $this->app->singleton(View::class, function () {
            return new View(
                config('view.path'),
                config('view.cache'),
                config('view.extension')
            );
        });
    }

   public function boot()
{
    $view = $this->app->make(View::class);

    $view->share('navbarData', function () {

    if (!auth()->check()) {
        return [
            'notifications' => [],
            'unreadCount' => 0
        ];
    }

    return app(NotificationService::class)
        ->getNavbarData(auth()->id());
});
}

}
