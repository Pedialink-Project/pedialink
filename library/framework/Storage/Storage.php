<?php

namespace Library\Framework\Storage;

use InvalidArgumentException;
use RuntimeException;

class Storage
{
    private array $config;
    private string $defaultDisk;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->defaultDisk = $config['default'] ?? array_key_first($config['disks']);
    }

    private function diskConfig(string $disk = null): array
    {
        $disk = $disk ?? $this->defaultDisk;
        if (!isset($this->config['disks'][$disk])) {
            throw new InvalidArgumentException("Disk '{$disk}' not defined.");
        }
        return $this->config['disks'][$disk];
    }

    // Make sure path is inside root and normalized (prevents ../ escapes)
    private function normalizePath(string $path, string $root): string
    {
        // remove leading slashes
        $path = ltrim($path, '/\\');

        // collapse .. and .
        $parts = array_values(
            array_filter(
                explode(
                    '/', 
                    str_replace('\\', '/', $path)),
                    fn($p) => $p !== '' && $p !== '.')
                );

        $safe = [];
        foreach ($parts as $part) {
            if ($part === '..') {
                array_pop($safe);
            } else {
                $safe[] = $part;
            }
        }
        $final = implode(DIRECTORY_SEPARATOR, $safe);
        $full = $root . DIRECTORY_SEPARATOR . $final;

        // ensure the resulting path is inside root
        $realRoot = realpath($root);
        $realFull = $this->safeRealpathAllowMissing($full, $realRoot);
        if ($realRoot === false || strpos($realFull, $realRoot) !== 0) {
            throw new RuntimeException("Path traversal detected or invalid path: $path");
        }

        return $final; // return path relative to root
    }

    // realpath but works if file doesn't exist yet
    private function safeRealpathAllowMissing(string $path, string $root): string
    {
        $dir = dirname($path);
        $realDir = realpath($dir);
        if ($realDir === false) {
            // some parents don't exist yet; compute canonicalized path manually
            $segments = explode(DIRECTORY_SEPARATOR, str_replace('\\', '/', $path));
            $stack = [];
            foreach ($segments as $seg) {
                if ($seg === '' || $seg === '.')
                    continue;
                if ($seg === '..')
                    array_pop($stack);
                else
                    $stack[] = $seg;
            }
            return $root . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, array_slice($stack, count(explode(DIRECTORY_SEPARATOR, $root)))); // best-effort
        }
        return $realDir . DIRECTORY_SEPARATOR . basename($path);
    }

    private function ensureDirectoryExists(string $fullPath)
    {
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0775, true) && !is_dir($dir)) {
                throw new RuntimeException("Unable to create directory: $dir");
            }
        }
    }

    public function put(string $disk, string $path, string $contents, ?array $options = []): bool
    {
        $cfg = $this->diskConfig($disk);
        $root = $cfg['root'];
        $rel = $this->normalizePath($path, $root);
        $full = $root . DIRECTORY_SEPARATOR . $rel;
        $this->ensureDirectoryExists($full);
        $written = file_put_contents($full, $contents);
        if ($written === false)
            return false;
        if (isset($options['visibility']) || isset($cfg['visibility'])) {
            $this->applyVisibility($full, $options['visibility'] ?? $cfg['visibility']);
        }
        return true;
    }

    // Accepts an uploaded file entry from $_FILES (associative array) or a path to a local file.
    public function putFile(string $disk, array|string $uploaded, string $destPath, ?array $options = []): bool
    {
        $cfg = $this->diskConfig($disk);
        $root = $cfg['root'];
        $rel = $this->normalizePath($destPath, $root);
        $full = $root . DIRECTORY_SEPARATOR . $rel;
        $this->ensureDirectoryExists($full);

        if (is_array($uploaded) && isset($uploaded['tmp_name'])) {
            $tmp = $uploaded['tmp_name'];
            if (!is_uploaded_file($tmp)) {
                // still allow moving from tmp if not via HTTP upload (testing)
            }
            $ok = move_uploaded_file($tmp, $full) || rename($tmp, $full) || copy($tmp, $full);
        } elseif (is_string($uploaded) && is_file($uploaded)) {
            $ok = copy($uploaded, $full);
        } else {
            throw new InvalidArgumentException("Invalid uploaded file parameter.");
        }

        if ($ok && (isset($cfg['visibility']) || isset($options['visibility']))) {
            $this->applyVisibility($full, $cfg['visibility'] ?? $options['visibility']);
        }
        return (bool) $ok;
    }

    public function get(string $disk, string $path): ?string
    {
        $cfg = $this->diskConfig($disk);
        $root = $cfg['root'];
        $rel = $this->normalizePath($path, $root);
        $full = $root . DIRECTORY_SEPARATOR . $rel;
        if (!is_file($full))
            return null;
        return file_get_contents($full);
    }

    public function exists(string $disk, string $path): bool
    {
        $cfg = $this->diskConfig($disk);
        $root = $cfg['root'];
        $rel = $this->normalizePath($path, $root);
        return is_file($root . DIRECTORY_SEPARATOR . $rel);
    }

    public function delete(string $disk, string $path): bool
    {
        $cfg = $this->diskConfig($disk);
        $root = $cfg['root'];
        $rel = $this->normalizePath($path, $root);
        $full = $root . DIRECTORY_SEPARATOR . $rel;
        if (is_file($full)) {
            return unlink($full);
        }
        return false;
    }

    private function applyVisibility(string $fullPath, string $visibility)
    {
        if ($visibility === 'public') {
            // world-readable
            @chmod($fullPath, 0644);
        } else {
            // private
            @chmod($fullPath, 0640);
        }
    }

    public function getFullPath(string $disk, string $path)
    {
        return rtrim($this->diskConfig($disk)['root'], '/') .
            '/' . ltrim($path, '/');
    }

    // Return a URL for a file. For public disks this will be the prefix + path; for private disks it will be a signed URL route.
    public function url(string $disk, string $path): string
    {
        $cfg = $this->diskConfig($disk);
        $rel = $this->normalizePath($path, $cfg['root']);
        if (($cfg['visibility'] ?? 'private') === 'public' && !empty($cfg['url_prefix'])) {
            // Ensure prefix does not have trailing slash
            return rtrim(
                $cfg['url_prefix'], '/') .
                '/' . str_replace(DIRECTORY_SEPARATOR, '/', $rel
            );
        }
        // private -> temporary signed URL
        return $this->temporaryUrl($disk, $path, $this->config['temp_lifetime']);
    }

    public function temporaryUrl(string $disk, string $path, int $seconds = null): string
    {
        $seconds = $seconds ?? ($this->config['temp_lifetime'] ?? 300);
        $cfg = $this->diskConfig($disk);
        $rel = $this->normalizePath($path, $cfg['root']);
        $expires = time() + $seconds;
        $secret = $this->config['secret'] ?? 'change_me';
        $payload = $disk . '|' . $rel . '|' . $expires;
        $sig = hash_hmac('sha256', $payload, $secret);
        // route to your controller that serves private media, 
        // e.g. /media/serve?disk=...&path=...&expires=...&sig=...
        return '/media/serve?disk=' . urlencode($disk) .
            '&path=' . urlencode($rel) .
            '&expires=' . $expires .
            '&sig=' . $sig;
    }

    // Validate signature for temporary URL
    public function validateTempUrl(string $disk, string $path, int $expires, string $sig): bool
    {
        if ($expires < time()) {
            return false;
        }

        $secret = $this->config['secret'] ?? 'change_me';
        $payload = $disk . '|' . $path . '|' . $expires;
        $expected = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expected, $sig);
    }
}