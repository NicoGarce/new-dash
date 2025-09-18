<?php
/**
 * Data Configuration File
 * This file contains all the data for the dashboard system.
 * Update this file to change the data displayed across all dashboards.
 */

/**
 * Generate random data that sums to a specific total
 */
function generateRandomData($total, $count, $minPercent = 0.05, $maxPercent = 0.25) {
    $values = [];
    $remaining = $total;
    
    // Generate random values for first count-1 items
    for ($i = 0; $i < $count - 1; $i++) {
        $min = max(1, floor($total * $minPercent));
        $max = min($remaining - ($count - $i - 1), floor($total * $maxPercent));
        $value = rand($min, $max);
        $values[] = $value;
        $remaining -= $value;
    }
    
    // Last value gets the remainder
    $values[] = max(1, $remaining);
    
    return $values;
}

// Fixed random data that sums to grand totals (consistent across refreshes)
$campusNames = ['BIÑAN', 'GMA CAVITE', 'MANILA', 'PANGASINAN', 'ISABELA', 'ROXAS', 'MEDICAL UNIVERSITY'];
$campusKeys = ['binan', 'gma_cavite', 'manila', 'pangasinan', 'isabela', 'roxas', 'med-university'];

// Set a fixed seed for consistent random data
srand(12345);

$enrollmentData = array_combine($campusNames, generateRandomData(28952, 7, 0.08, 0.20));
$collectionData = array_combine($campusNames, generateRandomData(2456789000, 7, 0.05, 0.25));
$accountsPayableData = array_combine($campusNames, generateRandomData(456789000, 7, 0.05, 0.25));

/**
 * Generate individual campus data based on random totals
 */
function generateCampusData($campusName, $enrollmentTotal, $collectionTotal, $accountsPayableTotal) {
    // Set seed based on campus name for consistent sub-distributions
    $seed = crc32($campusName);
    srand($seed);
    
    return [
        'name' => $campusName,
        'enrollment' => [
            'current_year' => $enrollmentTotal,
            'previous_year' => round($enrollmentTotal * 0.93),
            'per_sy' => [
                round($enrollmentTotal * 0.85),
                round($enrollmentTotal * 0.89),
                round($enrollmentTotal * 0.92),
                round($enrollmentTotal * 0.93),
                $enrollmentTotal
            ],
            'per_college' => array_combine(
                ['CAS', 'CBA', 'CCS', 'CEA', 'CHM', 'CME', 'COED', 'CRM', 'GRAD', 'LAW', 'SOA'],
                generateRandomData($enrollmentTotal, 11, 0.05, 0.15)
            )
        ],
        'collection' => [
            'current_year' => $collectionTotal,
            'previous_year' => round($collectionTotal * 0.94),
            'monthly' => generateRandomData($collectionTotal, 12, 0.06, 0.12)
        ],
        'accounts_payable' => [
            'current_year' => $accountsPayableTotal,
            'previous_year' => round($accountsPayableTotal * 0.95),
            'by_category' => array_combine(
                ['Supplies', 'Utilities', 'Maintenance', 'Equipment', 'Services', 'Other'],
                generateRandomData($accountsPayableTotal, 6, 0.10, 0.30)
            )
        ]
    ];
}

// Campus data configuration
$campusData = [
    'all_campuses' => [
        'name' => 'UNIVERSITY OF PERPETUAL HELP SYSTEM JONELTA',
        'enrollment' => [
            'current_year' => 28952,
            'previous_year' => 26848,
            'per_campus' => $enrollmentData
        ],
        'collection' => [
            'current_year' => 2456789000,
            'previous_year' => 2123456000,
            'per_campus' => $collectionData
        ],
        'accounts_payable' => [
            'current_year' => 456789000,
            'previous_year' => 423456000,
            'per_campus' => $accountsPayableData
        ]
    ],
    'binan' => generateCampusData('BIÑAN', $enrollmentData['BIÑAN'], $collectionData['BIÑAN'], $accountsPayableData['BIÑAN']),
    'manila' => generateCampusData('MANILA', $enrollmentData['MANILA'], $collectionData['MANILA'], $accountsPayableData['MANILA']),
    'gma_cavite' => generateCampusData('GMA CAVITE', $enrollmentData['GMA CAVITE'], $collectionData['GMA CAVITE'], $accountsPayableData['GMA CAVITE']),
    'pangasinan' => generateCampusData('PANGASINAN', $enrollmentData['PANGASINAN'], $collectionData['PANGASINAN'], $accountsPayableData['PANGASINAN']),
    'isabela' => generateCampusData('ISABELA', $enrollmentData['ISABELA'], $collectionData['ISABELA'], $accountsPayableData['ISABELA']),
    'roxas' => generateCampusData('ROXAS', $enrollmentData['ROXAS'], $collectionData['ROXAS'], $accountsPayableData['ROXAS']),
    'med-university' => generateCampusData('MEDICAL UNIVERSITY', $enrollmentData['MEDICAL UNIVERSITY'], $collectionData['MEDICAL UNIVERSITY'], $accountsPayableData['MEDICAL UNIVERSITY'])
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
