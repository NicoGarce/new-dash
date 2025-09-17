<?php
/**
 * Data Configuration File
 * This file contains all the data for the dashboard system.
 * Update this file to change the data displayed across all dashboards.
 */

// Campus data configuration
$campusData = [
    'all_campuses' => [
        'name' => 'UNIVERSITY OF PERPETUAL HELP SYSTEM JONELTA',
        'enrollment' => [
            'current_year' => 28952,
            'previous_year' => 26848,
            'per_campus' => [
                'BIÑAN' => 4136,
                'GMA CAVITE' => 4136,
                'MANILA' => 4136,
                'PANGASINAN' => 4136,
                'ISABELA' => 4136,
                'ROXAS' => 4136,
                'MEDICAL UNIVERSITY' => 4136
            ]
        ],
        'collection' => [
            'current_year' => 2456789000,
            'previous_year' => 2123456000,
            'per_campus' => [
                'BIÑAN' => 450000000,
                'GMA CAVITE' => 380000000,
                'MANILA' => 420000000,
                'PANGASINAN' => 350000000,
                'ISABELA' => 280000000,
                'ROXAS' => 150000000,
                'MEDICAL UNIVERSITY' => 120000000
            ]
        ],
        'accounts_payable' => [
            'current_year' => 456789000,
            'previous_year' => 423456000,
            'per_campus' => [
                'BIÑAN' => 85000000,
                'GMA CAVITE' => 72000000,
                'MANILA' => 78000000,
                'PANGASINAN' => 65000000,
                'ISABELA' => 52000000,
                'ROXAS' => 28000000,
                'MEDICAL UNIVERSITY' => 22000000
            ]
        ]
    ],
    'binan' => [
        'name' => 'BIÑAN',
        'enrollment' => [
            'current_year' => 4136,
            'previous_year' => 3835,
            'per_sy' => [3500, 3700, 3800, 3835, 4136],
            'per_college' => [
                'CAS' => 376,
                'CBA' => 376,
                'CCS' => 376,
                'CEA' => 376,
                'CHM' => 376,
                'CME' => 376,
                'COED' => 376,
                'CRM' => 376,
                'GRAD' => 376,
                'LAW' => 376,
                'SOA' => 376
            ]
        ],
        'collection' => [
            'current_year' => 45000000,
            'previous_year' => 42500000,
            'monthly' => [3200000, 3800000, 4200000, 3900000, 4100000, 3800000, 3600000, 4000000, 4500000, 4200000, 3800000, 3500000]
        ],
        'accounts_payable' => [
            'current_year' => 8500000,
            'previous_year' => 7200000,
            'by_category' => [
                'Supplies' => 2500000,
                'Utilities' => 1800000,
                'Maintenance' => 1500000,
                'Equipment' => 1200000,
                'Services' => 1000000,
                'Other' => 500000
            ]
        ]
    ],
    'manila' => [
        'name' => 'MANILA',
        'enrollment' => [
            'current_year' => 4136,
            'previous_year' => 3835,
            'per_sy' => [3500, 3700, 3800, 3835, 4136],
            'per_college' => [
                'CAS' => 376,
                'CBA' => 376,
                'CCS' => 376,
                'CEA' => 376,
                'CHM' => 376,
                'CME' => 376,
                'COED' => 376,
                'CRM' => 376,
                'GRAD' => 376,
                'LAW' => 376,
                'SOA' => 376
            ]
        ],
        'collection' => [
            'current_year' => 42000000,
            'previous_year' => 39500000,
            'monthly' => [3000000, 3500000, 3800000, 3600000, 3900000, 3700000, 3500000, 3800000, 4200000, 4000000, 3600000, 3300000]
        ],
        'accounts_payable' => [
            'current_year' => 7800000,
            'previous_year' => 6900000,
            'by_category' => [
                'Supplies' => 2200000,
                'Utilities' => 1600000,
                'Maintenance' => 1400000,
                'Equipment' => 1100000,
                'Services' => 900000,
                'Other' => 500000
            ]
        ]
    ],
    'gma_cavite' => [
        'name' => 'GMA CAVITE',
        'enrollment' => [
            'current_year' => 4136,
            'previous_year' => 3835,
            'per_sy' => [3500, 3700, 3800, 3835, 4136],
            'per_college' => [
                'CAS' => 376,
                'CBA' => 376,
                'CCS' => 376,
                'CEA' => 376,
                'CHM' => 376,
                'CME' => 376,
                'COED' => 376,
                'CRM' => 376,
                'GRAD' => 376,
                'LAW' => 376,
                'SOA' => 376
            ]
        ],
        'collection' => [
            'current_year' => 38000000,
            'previous_year' => 36000000,
            'monthly' => [2800000, 3200000, 3500000, 3300000, 3600000, 3400000, 3200000, 3500000, 3800000, 3600000, 3200000, 2900000]
        ],
        'accounts_payable' => [
            'current_year' => 7200000,
            'previous_year' => 6800000,
            'by_category' => [
                'Supplies' => 2000000,
                'Utilities' => 1500000,
                'Maintenance' => 1300000,
                'Equipment' => 1000000,
                'Services' => 800000,
                'Other' => 400000
            ]
        ]
    ],
    'pangasinan' => [
        'name' => 'PANGASINAN',
        'enrollment' => [
            'current_year' => 4136,
            'previous_year' => 3835,
            'per_sy' => [3500, 3700, 3800, 3835, 4136],
            'per_college' => [
                'CAS' => 376,
                'CBA' => 376,
                'CCS' => 376,
                'CEA' => 376,
                'CHM' => 376,
                'CME' => 376,
                'COED' => 376,
                'CRM' => 376,
                'GRAD' => 376,
                'LAW' => 376,
                'SOA' => 376
            ]
        ],
        'collection' => [
            'current_year' => 35000000,
            'previous_year' => 33000000,
            'monthly' => [2600000, 3000000, 3200000, 3000000, 3300000, 3100000, 2900000, 3200000, 3500000, 3300000, 2900000, 2600000]
        ],
        'accounts_payable' => [
            'current_year' => 6500000,
            'previous_year' => 6200000,
            'by_category' => [
                'Supplies' => 1800000,
                'Utilities' => 1400000,
                'Maintenance' => 1200000,
                'Equipment' => 900000,
                'Services' => 700000,
                'Other' => 350000
            ]
        ]
    ],
    'isabela' => [
        'name' => 'ISABELA',
        'enrollment' => [
            'current_year' => 4136,
            'previous_year' => 3835,
            'per_sy' => [3500, 3700, 3800, 3835, 4136],
            'per_college' => [
                'CAS' => 376,
                'CBA' => 376,
                'CCS' => 376,
                'CEA' => 376,
                'CHM' => 376,
                'CME' => 376,
                'COED' => 376,
                'CRM' => 376,
                'GRAD' => 376,
                'LAW' => 376,
                'SOA' => 376
            ]
        ],
        'collection' => [
            'current_year' => 28000000,
            'previous_year' => 26500000,
            'monthly' => [2100000, 2400000, 2600000, 2400000, 2700000, 2500000, 2300000, 2600000, 2800000, 2600000, 2300000, 2100000]
        ],
        'accounts_payable' => [
            'current_year' => 5200000,
            'previous_year' => 4900000,
            'by_category' => [
                'Supplies' => 1500000,
                'Utilities' => 1100000,
                'Maintenance' => 950000,
                'Equipment' => 700000,
                'Services' => 550000,
                'Other' => 300000
            ]
        ]
    ],
    'roxas' => [
        'name' => 'ROXAS',
        'enrollment' => [
            'current_year' => 4136,
            'previous_year' => 3835,
            'per_sy' => [3500, 3700, 3800, 3835, 4136],
            'per_college' => [
                'CAS' => 376,
                'CBA' => 376,
                'CCS' => 376,
                'CEA' => 376,
                'CHM' => 376,
                'CME' => 376,
                'COED' => 376,
                'CRM' => 376,
                'GRAD' => 376,
                'LAW' => 376,
                'SOA' => 376
            ]
        ],
        'collection' => [
            'current_year' => 15000000,
            'previous_year' => 14000000,
            'monthly' => [1100000, 1300000, 1400000, 1300000, 1450000, 1350000, 1250000, 1400000, 1500000, 1400000, 1250000, 1100000]
        ],
        'accounts_payable' => [
            'current_year' => 2800000,
            'previous_year' => 2600000,
            'by_category' => [
                'Supplies' => 800000,
                'Utilities' => 600000,
                'Maintenance' => 500000,
                'Equipment' => 400000,
                'Services' => 300000,
                'Other' => 150000
            ]
        ]
    ],
    'med-university' => [
        'name' => 'MEDICAL UNIVERSITY',
        'enrollment' => [
            'current_year' => 4136,
            'previous_year' => 3835,
            'per_sy' => [3500, 3700, 3800, 3835, 4136],
            'per_college' => [
                'CAS' => 376,
                'CBA' => 376,
                'CCS' => 376,
                'CEA' => 376,
                'CHM' => 376,
                'CME' => 376,
                'COED' => 376,
                'CRM' => 376,
                'GRAD' => 376,
                'LAW' => 376,
                'SOA' => 376
            ]
        ],
        'collection' => [
            'current_year' => 12000000,
            'previous_year' => 11000000,
            'monthly' => [900000, 1000000, 1100000, 1000000, 1150000, 1050000, 950000, 1100000, 1200000, 1100000, 950000, 900000]
        ],
        'accounts_payable' => [
            'current_year' => 2200000,
            'previous_year' => 2000000,
            'by_category' => [
                'Supplies' => 650000,
                'Utilities' => 480000,
                'Maintenance' => 400000,
                'Equipment' => 300000,
                'Services' => 250000,
                'Other' => 120000
            ]
        ]
    ]
];

// Helper functions for data access
function getCampusData($campusKey) {
    global $campusData;
    return isset($campusData[$campusKey]) ? $campusData[$campusKey] : null;
}

function getCampusList() {
    global $campusData;
    return array_keys($campusData);
}
?>
