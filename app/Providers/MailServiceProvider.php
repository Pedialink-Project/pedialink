<?php

namespace App\Providers;

use Library\Framework\Core\Application;
use Library\Framework\Core\Provider;
use Library\Framework\Mail\Mailer;
use Library\Framework\Mail\SmtpMailer;

class MailServiceProvider extends Provider
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

    /**
     * Register base session handler
     * @return void
     */
    public function register()
    {
        $smtpMailer = new SmtpMailer();
        $mailer = new Mailer($smtpMailer);

        $this->app->singleton(SmtpMailer::class, function () use ($smtpMailer) {
            return $smtpMailer;
        });

        $this->app->singleton(Mailer::class, function () use ($mailer) {
            return $mailer;
        });
    }

    public function boot()
    {
        
    }
}