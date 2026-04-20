<?php

function getQueryParam(string $name)
{
    // If there are no brackets, simple case.
    if (strpos($name, '[') === false) {
        return $_GET[$name] ?? null;
    }

    // Extract top key and intermediate bracket keys
    if (!preg_match('/^([^\[]+)((?:\[[^\]]*\])+)$/', $name, $m)) {
        return null;
    }

    $topKey = $m[1];
    $brackets = $m[2];


    preg_match_all('/\[([^\]]*)\]/', $brackets, $matches);
    $keys = $matches[1];

    $value = $_GET[$topKey] ?? null;

    foreach ($keys as $k) {
        if ($k === '') {
            return $value;
        }
        if (!is_array($value) || !array_key_exists($k, $value)) {
            return null;
        }
        $value = $value[$k];
    }

    return $value;
}

function isFilterChecked(string $name, $value = null): bool
{
    $param = getQueryParam($name);

    // If the parameter does not exist at all
    if ($param === null) {
        return false;
    }

    // If caller didn't supply a value, treat presence/truthiness as checked
    if ($value === null) {
        if (is_array($param)) {
            return !empty($param) ? true : false;
        }

        return (string)$param !== '' ? true : false;
    }

    // If param is an array, check membership
    if (is_array($param)) {
        foreach ($param as $p) {
            if ((string)$p === (string)$value) {
                return true;
            }
        }
        return false;
    }

    // Scalar comparison
    return ((string)$param === (string)$value) ? true : false;
}