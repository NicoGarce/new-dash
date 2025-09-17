<?php
/**
 * Helper Functions
 * 
 * This file contains utility functions used throughout the dashboard system.
 * These functions provide common functionality for data formatting, validation,
 * and other repetitive tasks.
 * 
 * @author Dashboard System
 * @version 1.0
 * @since 2025-09-17
 */

/**
 * Format currency values with proper formatting
 * 
 * @param int|float $amount Amount to format
 * @param string $currency Currency symbol (default: ₱)
 * @return string Formatted currency string
 */
function formatCurrency($amount, $currency = '₱') {
    return $currency . number_format($amount);
}

/**
 * Format numbers with proper comma separation
 * 
 * @param int|float $number Number to format
 * @return string Formatted number string
 */
function formatNumber($number) {
    return number_format($number);
}

/**
 * Format large numbers with K, M, B suffixes
 * 
 * @param int|float $number Number to format
 * @return string Formatted number with suffix
 */
function formatLargeNumber($number) {
    if ($number >= 1000000000) {
        return number_format($number / 1000000000, 1) . 'B';
    } elseif ($number >= 1000000) {
        return number_format($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return number_format($number / 1000, 1) . 'K';
    }
    return number_format($number);
}


/**
 * Sanitize output for HTML display
 * 
 * @param string $string String to sanitize
 * @return string Sanitized string
 */
function sanitizeOutput($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate chart configuration for different chart types
 * 
 * @param string $chartType Type of chart (enrollment, collection, payables)
 * @param array $data Chart data
 * @param bool $isAllCampuses Whether this is for all campuses view
 * @return array Chart configuration
 */
function generateChartConfig($chartType, $data, $isAllCampuses = false) {
    $baseConfig = [
        'responsive' => true,
        'maintainAspectRatio' => false,
        'plugins' => [
            'legend' => [
                'display' => false
            ]
        ]
    ];
    
    switch ($chartType) {
        case 'enrollment':
            return array_merge($baseConfig, [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'grid' => ['color' => '#f3f4f6'],
                        'ticks' => [
                            'callback' => function($value) use ($isAllCampuses) {
                                return $isAllCampuses ? ($value / 1000) . 'K' : $value;
                            },
                            'color' => '#6b7280',
                            'font' => ['size' => 10]
                        ]
                    ],
                    'x' => [
                        'grid' => ['display' => false],
                        'ticks' => [
                            'color' => '#6b7280',
                            'font' => ['size' => 9]
                        ]
                    ]
                ]
            ]);
            
        case 'collection':
            $config = array_merge($baseConfig, [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'grid' => ['color' => '#f3f4f6'],
                        'ticks' => [
                            'callback' => function($value) {
                                return '₱' . ($value / 1000000) . 'M';
                            },
                            'color' => '#6b7280',
                            'font' => ['size' => 10]
                        ]
                    ],
                    'x' => [
                        'grid' => ['color' => '#f3f4f6'],
                        'ticks' => [
                            'color' => '#6b7280',
                            'font' => ['size' => 9]
                        ]
                    ]
                ]
            ]);
            
            if (!$isAllCampuses) {
                $config['scales']['x']['grid'] = ['color' => '#f3f4f6'];
            }
            
            return $config;
            
        case 'payables':
            if ($isAllCampuses) {
                return array_merge($baseConfig, [
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true,
                            'grid' => ['color' => '#f3f4f6'],
                            'ticks' => [
                                'callback' => function($value) {
                                    return '₱' . ($value / 1000000) . 'M';
                                },
                                'color' => '#6b7280',
                                'font' => ['size' => 10]
                            ]
                        ],
                        'x' => [
                            'grid' => ['display' => false],
                            'ticks' => [
                                'color' => '#6b7280',
                                'font' => ['size' => 9]
                            ]
                        ]
                    ]
                ]);
            } else {
                return array_merge($baseConfig, [
                    'plugins' => [
                        'legend' => [
                            'display' => true,
                            'position' => 'bottom',
                            'labels' => [
                                'color' => '#6b7280',
                                'font' => ['size' => 10]
                            ]
                        ]
                    ]
                ]);
            }
            break;
        }
        
        return $baseConfig;
    }

/**
 * Generate chart dataset configuration
 * 
 * @param string $chartType Type of chart
 * @param array $data Chart data
 * @param bool $isAllCampuses Whether this is for all campuses view
 * @return array Dataset configuration
 */
function generateChartDataset($chartType, $data, $isAllCampuses = false) {
    $baseDataset = [
        'label' => ucfirst($chartType),
        'data' => $data,
        'backgroundColor' => '#204ca4',
        'borderColor' => '#204ca4',
        'borderWidth' => 0,
        'borderRadius' => 8,
        'borderSkipped' => false
    ];
    
    switch ($chartType) {
        case 'collection':
            if ($isAllCampuses) {
                return $baseDataset;
            } else {
                return [
                    'label' => 'Collection (₱)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(32, 76, 164, 0.1)',
                    'borderColor' => '#204ca4',
                    'borderWidth' => 3,
                    'borderRadius' => 0,
                    'borderSkipped' => true,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => '#204ca4',
                    'pointBorderColor' => '#204ca4',
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6
                ];
            }
            
        case 'payables':
            if ($isAllCampuses) {
                return $baseDataset;
            } else {
                return [
                    'label' => 'Outstanding Payables (₱)',
                    'data' => $data,
                    'backgroundColor' => ['#204ca4', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#dbeafe'],
                    'borderColor' => null,
                    'borderWidth' => 0,
                    'borderRadius' => 0,
                    'borderSkipped' => true
                ];
            }
            
        default:
            return $baseDataset;
    }
}

/**
 * Get current user information
 * 
 * @return array User information
 */
function getCurrentUser() {
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? 'User',
        'is_logged_in' => isset($_SESSION['user_id'])
    ];
}

/**
 * Check if user is logged in
 * 
 * @return bool True if logged in, false otherwise
 */
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current timestamp
 * 
 * @return string Current timestamp
 */
function getCurrentTimestamp() {
    return date('Y-m-d H:i:s');
}

/**
 * Generate asset path based on current location
 * 
 * @param string $asset Asset path
 * @return string Full asset path
 */
function getAssetPath($asset) {
    $isInCampuses = (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/campuses/') !== false);
    $prefix = $isInCampuses ? '../' : '';
    return $prefix . $asset;
}
?>
