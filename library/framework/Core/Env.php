<?php

namespace Library\Framework\Core;

/**
 * Env file handler
 * 
 * Handles reading and extracting key-value pairs
 * from .env files. Stores extracted key-value pairs.
 * 
 * NOTE: Do not invoke this class directly unless
 * when working with CORE components or bootstrapping logic.
 * Use the config() helper class for accessing it elsewhere.
 * 
 * NOTE for cs 28 members: Any Pull Requests using this method
 * without care will be auto rejected. Please consider this!!
 */
class Env
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param mixed $path Path to .env file
     * @throws \Exception
     */
    public function __construct($path)
    {
        if (!file_exists($path)) {
            throw new \Exception(".env file not found: $path");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            [$key, $value] = array_map('trim', explode('=', $line, 2));
            $value = trim($value, '"');
            $this->data[$key] = $value;
        }
    }

    /**
     * Retrieve the environment keys
     * @param mixed $key ENV key
     * @param mixed $default default value if ENV key is not available
     * @return string|integer|null returns key/default value or null
     */
    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
}