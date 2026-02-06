<?php

namespace App\Helpers;

use App\Helpers\Validator;


use App\Models\Child;

trait BirthCertificateValidator
{

    public  function validateBirthCertificate(string $birthCertificate)
    {

        $error = null;

        if (!Validator::validateFieldExistence($birthCertificate)) {
            $error = "Birth certificate No field cannot be empty";
            return $error;
        }

        if (!preg_match('/^B-[0-9]{4}$/', $birthCertificate)) {
            $error = "Invalid format. Birth certificate must be in the format B-1234.";
            return $error;
        }

        $exists = Child::query()->where('birth_certificate', '=', $birthCertificate)->get();

        if ($exists) {
            $error = "This birth certificate number is already registered.";
            return $error;
        }

        return true;
    }
}
