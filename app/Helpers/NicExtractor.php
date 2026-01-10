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
        $doy = null;

        if ($this->isOldFormat($this->nic)) {
            $this->nicExtracted['format'] = 'old';
            $year   = (int) substr($this->nic, 0, 2);
            $doy  = (int) substr($this->nic, 2, 3);
        } else if ($this->isNewFormat($this->nic)) {
            $this->nicExtracted['format'] = 'new';
            $year = (int) substr($this->nic, 0, 4);
            $doy  = (int) substr($this->nic, 4, 3);
        } else {
            return;
        }

        if ($this->isDoyValid($doy)) {
            if ($doy > 500) {
                $this->nicExtracted["gender"] = "F";
            } else {
                $this->nicExtracted["gender"] = "M";
            }
        } else {
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