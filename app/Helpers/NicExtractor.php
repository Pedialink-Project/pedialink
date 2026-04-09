<?php

namespace App\Helpers;

use App\Rules\NicRule;

class NicExtractor
{
    use NicRule;

    private string $nic;
    private array $nicExtracted;

    public function __construct(string $nic)
    {
        $this->nic = strtoupper(
            preg_replace('/[^0-9A-Za-z]/', '', trim($nic))
        );

        $this->nicExtracted = [
            'valid'     => false,
            'format'    => null,
            'dob'       => null,
            'gender'    => null,
        ];
    }

    private function extractNic()
    {
        $year = null;
        $rawDoy = null;
        $doy = null;

        if ($this->isOldFormat($this->nic)) {
            $this->nicExtracted['format'] = 'old';
            $twoDigitYear = (int) substr($this->nic, 0, 2);
            $currentYearSuffix = (int) date('y');
            $year = $twoDigitYear <= $currentYearSuffix
                ? 2000 + $twoDigitYear
                : 1900 + $twoDigitYear;
            $rawDoy  = (int) substr($this->nic, 2, 3);
        } else if ($this->isNewFormat($this->nic)) {
            $this->nicExtracted['format'] = 'new';
            $year = (int) substr($this->nic, 0, 4);
            $rawDoy  = (int) substr($this->nic, 4, 3);
        } else {
            return;
        }

        if (!$this->isYearValid($year)) {
            return;
        }

        if ($rawDoy >= 501 && $rawDoy <= 866) {
            $this->nicExtracted["gender"] = "F";
            $doy = $rawDoy - 500;
        } else if ($rawDoy >= 1 && $rawDoy <= 366) {
            $this->nicExtracted["gender"] = "M";
            $doy = $rawDoy;
        } else {
            return;
        }

        if (!$this->isDoyValid($doy)) {
            return;
        }

        if ($this->isDobValid($doy, $year)) {
            $dayIndex = $doy - 1; // convert to 0-based
            $dt = \DateTime::createFromFormat(
                'Y z',
                sprintf('%04d %d', $year, $dayIndex)
            );

            $this->nicExtracted["dob"] = $dt->format('Y-m-d');
        } else {
            return;
        }

        $this->nicExtracted["valid"] = true;
    }

    public function getExtractedNic()
    {
        $this->extractNic();
        return $this->nicExtracted;
    }

    public function getCleanNic()
    {
        return $this->nic;
    }
}