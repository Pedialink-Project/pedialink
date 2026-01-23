<?php

namespace App\Helpers;

class FileValidator
{
    public static function isUploaded(array $file): bool
    {
        return isset($file['tmp_name']) && is_string($file['tmp_name']) && $file['tmp_name'] !== '';
    }

    public static function isFileValid(array $file): bool
    {
        return $file['error'] === UPLOAD_ERR_OK;
    }

    /**
    * Validate file size. $maxBytes should be an integer in bytes.
    */
    public static function validateSize(array $file, int $maxBytes): bool
    {
        if (!isset($file['size'])) {
            throw new \InvalidArgumentException('Missing size in uploaded file array.');
        }

        return (int)$file['size'] <= $maxBytes;
    }


    /**
    * Validate minimum file size (in bytes).
    */
    public static function validateMinSize(array $file, int $minBytes): bool
    {
        if (!isset($file['size'])) {
            throw new \InvalidArgumentException('Missing size in uploaded file array.');
        }
        return (int)$file['size'] >= $minBytes;
    }

    public static function validateMimeType(array $file, array $allowedMimeTypes): bool
    {
        $tmp = $file['tmp_name'] ?? null;
        if (!$tmp || !is_file($tmp)) {
            throw new \InvalidArgumentException('Temporary file missing when validating mime type.');
        }


        $mime = FileHandler::getMimeType($tmp);
        if ($mime === null) return false;


        return in_array($mime, $allowedMimeTypes, true);
    }
}