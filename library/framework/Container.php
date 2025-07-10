<?php

namespace Library\Framework;

/**
 * Core container class for dependency injection
 * 
 * Handles dependency injection, storing singleton
 * instances and binding classes for instantiation.
 */
class Container
{
    protected $bindings = [];
    protected $instances = [];

    /**
     * Bind classes or closures so that can be used
     * to recreate instances later easily.
     * 
     * Ex: 
     * bind('name', ClassName::class);
     * or
     * bind('name', function () { ... });
     * 
     * @param mixed $abstract Abstract type or identifier
     * @param mixed $concrete Class name or closure
     * @return void
     */
    public function bind($abstract, $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Create and store singleton instances.
     * 
     * For cs 28 members: Refer singleton design pattern
     * 
     * @param mixed $abstract Abstract type or identifier
     * @param mixed $concrete Class name or closure
     * @return void
     */
    public function singleton($abstract, $concrete)
    {
        $this->instances[$abstract] = $this->build($concrete);
    }

    /**
     * Retrieve or create object
     * 
     * Create objects based on bindings or retrieves
     * singleton instance
     * @param mixed $abstract
     * @return mixed Shared instance or raw class
     */
    public function make($abstract)
    {
        // Return shared instance if exists
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        
        // Resolve via binding or raw class
        $concrete = $this->bindings[$abstract] ?? $abstract;
        $object   = $this->build($concrete);
        return $object;
    }

    /**
     * Resolve nested dependencies
     * 
     * NOTE for cs 28 members: Uses advanced PHP
     * concepts such as reflection. Look up on google.
     * 
     * @param mixed $concrete
     * @return mixed|null Returns new instance of class or null
     */
    private function build($concrete)
    {
        if ($concrete instanceof \Closure) {
            return $concrete($this);
        }

        $reflector = new \ReflectionClass($concrete);
        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $concrete;
        }

        $params = $constructor->getParameters();
        $dependencies = array_map(function($param) {
            return $this->make($param->getClass()->name);
        }, $params);

        return $reflector->newInstanceArgs($dependencies);
    }
}