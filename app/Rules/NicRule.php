<?php

namespace App\Rules;

use DateTime;

trait NicRule
{
    private function isOldFormat(string $nic)
    {
        if (preg_match('/^\d{9}[VX]$/i', $nic)) {
            return true;
        }

        return false;
    }

    private function isNewFormat(string $nic)
    {
        if (preg_match('/^\d{12}$/', $nic)) {
            return true;
        }

        return false;
    }

    private function isYearValid(int $year)
    {
        $currentYear = (int) date('Y');
        if ($year < 1900 || $year > $currentYear) {
            return false;
        }

        return true;
    }

    private function isDoyValid(int $doy)
    {
        if ($doy < 1 || $doy > 366) {
            return false;
        }

        return true;
    }

    private function isDobValid(int $doy, int $year)
    {
        $dayIndex = $doy - 1; // convert to 0-based
        $dt = DateTime::createFromFormat(
            'Y z',
            sprintf('%04d %d', $year, $dayIndex)
        );
        $errors = DateTime::getLastErrors();

        if (!$dt || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
            return false;
        }

        $calculatedDoy = (int) $dt->format('z') + 1;

        if ($calculatedDoy !== $doy) {
            return false;
        }

        return true;
    }
}