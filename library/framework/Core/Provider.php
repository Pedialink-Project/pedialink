<?php

namespace Library\Framework\Core;

/**
 * Abstract provider class that
 * contains base methods that need to be implemeted
 * by all service provider classes.
 * 
 * Note for cs 28 members: If you are creating a new
 * service provider make sure to extend from this class.
 * This is because the service providers will be called with the
 * below methods in the bootstrap/app.php. So to avoid unwanted
 * errors and ensure consistency, please extend from this!!
 * 
 * Also, the register method should only contain logic that binds the service
 * provider to the container and the boot method should contain additional logic
 * that need to be set for a particular service provider.
 */
abstract class Provider
{
    abstract public function register();
    abstract public function boot();
}