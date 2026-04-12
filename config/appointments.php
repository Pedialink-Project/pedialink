<?php

return [
    'days_ahead' => 30,
    'slot_default_capacity' => 1,
    'rebook_delay_days_after_miss' => 1,

    'child_rules' => [
        [
            'min_age_days' => 0,
            'max_age_days' => 42,
            'reason' => 'Newborn routine health check',
        ],
        [
            'min_age_days' => 43,
            'max_age_days' => 365,
            'reason' => 'Infant routine health check',
        ],
        [
            'min_age_days' => 366,
            'max_age_days' => 1825,
            'reason' => 'Early childhood routine health check',
        ],
    ],
    'child_default_reason' => 'Child routine follow-up check',

    'maternal_rules' => [
        [
            'min_gestation_weeks' => 0,
            'max_gestation_weeks' => 12,
            'reason' => 'Antenatal booking and first trimester check-up',
        ],
        [
            'min_gestation_weeks' => 13,
            'max_gestation_weeks' => 27,
            'reason' => 'Second trimester routine antenatal check-up',
        ],
        [
            'min_gestation_weeks' => 28,
            'max_gestation_weeks' => 45,
            'reason' => 'Third trimester close antenatal follow-up',
        ],
    ],
    'maternal_default_reason' => 'Maternal routine check-up',
    'maternal_postnatal_reason' => 'Postnatal routine follow-up',
];
