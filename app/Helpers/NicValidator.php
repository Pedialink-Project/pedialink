<?php

namespace App\Helpers;

use App\Helpers\NicExtractor;
use App\Helpers\Validator;
use DateTime;

trait NicValidator
{
    /**
     * Validate NIC
     * 
     * NOTE: Needs to be improved further. Currently does
     * not strictly adhere unique constraints on NIC!
     * 
     * @param string $nic
     * @return string|null
     */
    private function validateNic(string $nic)
    {
        $error = null;

        if (!Validator::validateFieldExistence($nic)) {
            $error = "NIC field cannot be empty";
            return $error;
        }

        $nicExtractor = new NicExtractor($nic);
        $extractedResults = $nicExtractor->getExtractedNic();

        if (!$extractedResults["valid"]) {
            $error = "Invalid NIC format";
            return $error;
        }

        $dob = $extractedResults["dob"];
        $currentDate = new DateTime();
        $convertedDob = new DateTime($dob);
        $interval = $convertedDob->diff($currentDate);

        if ($interval->y < 18) {
            $error = "You must be atleast 18 years old";
            return $error;
        }

        return $error;
    }
}