<?php

namespace App\Services\Doctor;

use App\Helpers\AppointmentConfigurationHelper;
use App\Helpers\Calculator;
use App\Helpers\IntToDayName;
use App\Helpers\Validator;
use App\Models\Appointment;
use App\Models\ClinicWeeklyAvailability;
use App\Models\DoctorWeeklyAvailability;

class AppointmentService
{
    public function getAppointmentOverviewData(
        string $search,
        array $filters = [],
        $history = false,
        array $info = [
            'type' => 'child',
            'id' => null
        ]
    )