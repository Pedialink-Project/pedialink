<?php

namespace App\Helpers;

class FileHandler
{
    public static function getMimeType(string $path): ?string
    {
        if (!is_file($path)) {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return null;
        }

        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);
        return $mime === false ? null : $mime;
    }

    public static function sanitizeFilename(string $filename): string
    {
        // remove any directory paths
        $basename = basename($filename);
        // replace whitespace and control characters
        $basename = preg_replace('/[\s\\\\\/]+/', '_', $basename);
        // strip anything that's not a reasonable filename char
        $basename = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $basename);
        // avoid dotfiles
        $basename = ltrim($basename, '.');


        if ($basename === '') {
            $basename = 'file';
        }


        return $basename;
    }


    /**
     * Generate a collision-safe filename (keeps original extension) — useful before moving the file.
     */
    public static function generateUniqueFilename(string $originalName, ?string $destinationDir = null): string
    {
        $safe = self::sanitizeFilename($originalName);
        $ext = pathinfo($safe, PATHINFO_EXTENSION);
        $base = $ext !== '' ? pathinfo($safe, PATHINFO_FILENAME) : $safe;
        $uid = uniqid((string) time() . '_', true);
        $new = $base . '_' . $uid . ($ext !== '' ? '.' . $ext : '');


        if ($destinationDir !== null) {
            return rtrim($destinationDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $new;
        }


        return $new;
    }
}