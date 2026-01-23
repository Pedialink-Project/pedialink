<?php

namespace App\Rules;

use App\Helpers\Validator;

trait DateRule
{
    /**
     * Validate a date field
     *
     * @param string $date
     * @param string $attributeName
     * @param bool $mustBeFuture
     * @return string|null
     */
    private function validateDate(string $date, string $attributeName = 'Date', bool $mustBeFuture = false)
    {
        $error = null;
        if (!Validator::validateFieldExistence($date)) {
            $error = "{$attributeName} field cannot be empty";
        }

        if (!Validator::validateDateFormat($date)) {
            $error = "{$attributeName} format is invalid (expected YYYY-MM-DD)";
        }

        if ($mustBeFuture) {
            $input = strtotime($date);
            $today = strtotime(date('Y-m-d'));

            if ($input <= $today) {
                $error = "{$attributeName} must be a future date";
            }
        }

        return $error;
    }

    /**
     * Validate a time field (HH:MM)
     */
    private function validateTime(string $time, string $attributeName = 'Time', bool $mustBeFuture = false)
    {

        $error = null;
        if (!Validator::validateFieldExistence($time)) {
            $error = "{$attributeName} field cannot be empty";
        }

        if (!Validator::validateTimeFormat($time)) {
            $error = "{$attributeName} format is invalid (expected HH:MM)";
        }

        if ($mustBeFuture) {
            $input = strtotime($time);
            $now = time();
            if ($input <= $now) {
                $error = "{$attributeName} must be a future time";
            }
        }

        return $error;
    }

    private function validateDateTime(
        string $date,
        string $time,
        string $attributeName = 'Date & Time',
        bool $mustBeFuture = false
    ) {
        $error = null;
        $dateError = $this->validateDate($date, $attributeName . " Date", false);
        if ($dateError) {
            $error = $dateError;
        }

        $timeError = $this->validateTime($time, $attributeName . " Time", false);
        if ($timeError)
            $error = $timeError;

        if ($mustBeFuture) {
            $input = strtotime($date . ' ' . $time);
            $now = time();

            if ($input <= $now) {
                $error = "{$attributeName} must be in the future";
            }
        }

        return $error;
    }
}
