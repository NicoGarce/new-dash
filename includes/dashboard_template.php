<?php
/**
 * Dashboard Template Generator
 * 
 * This template generates complete dashboard HTML pages with data from configuration.
 * It provides a unified interface for creating both main dashboard and individual
 * campus dashboard pages with proper responsive design and chart integration.
 * 
 * @author Dashboard System
 * @version 1.0
 * @since 2025-09-17
 */

// Include helper functions
require_once __DIR__ . '/helpers.php';

/**
 * Generate complete dashboard HTML
 * 
 * @param string $campusKey Campus identifier
 * @param array $campusData Full campus data array
 * @return string Generated HTML
 */
function generateDashboardHTML($campusKey, $campusData) {
    // If $campusData is already the specific campus data, use it directly
    // Otherwise, extract the specific campus data from the full array
    if (isset($campusData['enrollment']) && isset($campusData['collection']) && isset($campusData['accounts_payable'])) {
        $campus = $campusData;
    } else {
        $campus = $campusData[$campusKey];
    }
    $isAllCampuses = ($campusKey === 'all_campuses');
    $user = getCurrentUser();
    
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo sanitizeOutput($campus['name']); ?> Dashboard</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="<?php echo getAssetPath('js/data_manager.js'); ?>"></script>
        <link rel="stylesheet" href="<?php echo getAssetPath('css/style.css'); ?>">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <?php 
        // Include the dynamic sidebar component
        if ($isAllCampuses) {
            include __DIR__ . '/dynamic_sidebar.php';
        } else {
            include __DIR__ . '/dynamic_sidebar.php';
        }
        ?>
        <div class="main-content">
            <div class="header-section">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1><?php echo sanitizeOutput($campus['name']); ?></h1>
                <div class="user-info">
                    <span class="last-update">Last updated: <?php echo getCurrentTimestamp(); ?></span>
                    <button class="refresh-btn" onclick="dataManager.refresh()" title="Refresh Data">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <span>Welcome, <?php echo sanitizeOutput($user['username']); ?></span>
                    <a href="<?php echo getAssetPath('logout.php'); ?>" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>

            <!-- Three Column Layout -->
            <div class="three-column-layout">
                <!-- Enrollment Column -->
                <div class="column">
                    <div class="column-header">
                        <h3>ENROLLMENT</h3>
                    </div>
                    <div class="column-content">
                        <div class="column-summary">
                            <div class="column-summary-card">
                                <h4>SUMMARY</h4>
                                <h5><?php echo $isAllCampuses ? 'GRAND TOTAL ENROLLEES AS OF SEPTEMBER S.Y. 2025-2026' : 'NEW ENROLLEES SEPTEMBER S.Y. 2025-2026'; ?></h4>
                            <div class="big-number enrollment-current"><?php echo formatNumber($campus['enrollment']['current_year']); ?></div>
                        </div>
                        <div class="column-summary-card">
                            <h4>SUMMARY PREV S.Y.</h4>
                            <h5><?php echo $isAllCampuses ? 'GRAND TOTAL ENROLLEES S.Y. 2024-2025' : 'NEW ENROLLEES SEPTEMBER S.Y. 2024-2025'; ?></h5>
                            <div class="big-number enrollment-previous"><?php echo formatNumber($campus['enrollment']['previous_year']); ?></div>
                            </div>
                        </div>
                        <div class="column-chart">
                            <canvas id="enrollmentChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Collection Column -->
                <div class="column">
                    <div class="column-header">
                        <h3>COLLECTION</h3>
                    </div>
                    <div class="column-content">
                        <div class="column-summary">
                            <div class="column-summary-card">
                                <h4>COLLECTION SUMMARY</h4>
                                <h5>TOTAL COLLECTION S.Y. 2025-2026</h5>
                            <div class="big-number collection-current"><?php echo formatCurrency($campus['collection']['current_year']); ?></div>
                        </div>
                        <div class="column-summary-card">
                            <h4>COLLECTION PREV S.Y.</h4>
                            <h5>TOTAL COLLECTION S.Y. 2024-2025</h5>
                            <div class="big-number collection-previous"><?php echo formatCurrency($campus['collection']['previous_year']); ?></div>
                            </div>
                        </div>
                        <div class="column-chart">
                            <canvas id="collectionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- ACCOUNTS RECEIVABLE Column -->
                <div class="column">
                    <div class="column-header">
                        <h3>ACCOUNTS RECEIVABLE</h3>
                    </div>
                    <div class="column-content">
                        <div class="column-summary">
                            <div class="column-summary-card">
                                <h4>ACCOUNTS RECEIVABLE SUMMARY</h4>
                                <h5>TOTAL OUTSTANDING RECEIVABLES S.Y. 2025-2026</h5>
                            <div class="big-number receivables-current"><?php echo formatCurrency($campus['accounts_payable']['current_year']); ?></div>
                        </div>
                        <div class="column-summary-card">
                            <h4>RECEIVABLES PREV S.Y.</h4>
                            <h5>TOTAL OUTSTANDING RECEIVABLES S.Y. 2024-2025</h5>
                            <div class="big-number receivables-previous"><?php echo formatCurrency($campus['accounts_payable']['previous_year']); ?></div>
                            </div>
                        </div>
                        <div class="column-chart">
                            <canvas id="accountsPayableChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div class="loading-overlay" style="display: none;">
            <div class="loading-spinner">
                <i class="fas fa-sync-alt fa-spin"></i>
                <p>Updating data...</p>
            </div>
        </div>

        <!-- Error Message -->
        <div class="error-message" style="display: none;"></div>

        <script>
            // Data from PHP
            const campusData = <?php echo json_encode($campus); ?>;
            const isAllCampuses = <?php echo $isAllCampuses ? 'true' : 'false'; ?>;
            const currentCampus = '<?php echo $campusKey; ?>';
            
            // Make currentCampus globally accessible for auto-refresh
            window.currentCampus = currentCampus;

            // Enrollment Chart
            const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
            const enrollmentChart = new Chart(enrollmentCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo $isAllCampuses ? json_encode(array_keys($campus['enrollment']['per_campus'])) : json_encode(array_keys($campus['enrollment']['per_college'])); ?>,
                    datasets: [{
                        label: 'Enrollees',
                        data: <?php echo $isAllCampuses ? json_encode(array_values($campus['enrollment']['per_campus'])) : json_encode(array_values($campus['enrollment']['per_college'])); ?>,
                        backgroundColor: '#204ca4',
                        borderColor: '#204ca4',
                        borderWidth: 0,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: function(value) {
                                    return isAllCampuses ? (value / 1000).toFixed(0) + 'K' : value;
                                },
                                color: '#6b7280',
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 9
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Collection Chart
            const collectionCtx = document.getElementById('collectionChart').getContext('2d');
            const collectionChart = new Chart(collectionCtx, {
                type: <?php echo $isAllCampuses ? "'bar'" : "'line'"; ?>,
                data: {
                    labels: <?php echo $isAllCampuses ? json_encode(array_keys($campus['collection']['per_campus'])) : json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']); ?>,
                    datasets: [{
                        label: 'Collection (₱)',
                        data: <?php echo $isAllCampuses ? json_encode(array_values($campus['collection']['per_campus'])) : json_encode($campus['collection']['monthly']); ?>,
                        backgroundColor: <?php echo $isAllCampuses ? "'#204ca4'" : "'rgba(32, 76, 164, 0.1)'"; ?>,
                        borderColor: '#204ca4',
                        borderWidth: <?php echo $isAllCampuses ? '0' : '3'; ?>,
                        borderRadius: <?php echo $isAllCampuses ? '8' : '0'; ?>,
                        borderSkipped: <?php echo $isAllCampuses ? 'false' : 'true'; ?>,
                        fill: <?php echo $isAllCampuses ? 'false' : 'true'; ?>,
                        tension: <?php echo $isAllCampuses ? '0' : '0.4'; ?>,
                        pointBackgroundColor: <?php echo $isAllCampuses ? 'null' : "'#204ca4'"; ?>,
                        pointBorderColor: <?php echo $isAllCampuses ? 'null' : "'#204ca4'"; ?>,
                        pointRadius: <?php echo $isAllCampuses ? '0' : '4'; ?>,
                        pointHoverRadius: <?php echo $isAllCampuses ? '0' : '6'; ?>
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₱' + (value / 1000000).toFixed(0) + 'M';
                                },
                                color: '#6b7280',
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: '#f3f4f6'
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 9
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // ACCOUNTS RECEIVABLE Chart
            const accountsPayableCtx = document.getElementById('accountsPayableChart').getContext('2d');
            const accountsPayableChart = new Chart(accountsPayableCtx, {
                type: <?php echo $isAllCampuses ? "'bar'" : "'doughnut'"; ?>,
                data: {
                    labels: <?php echo $isAllCampuses ? json_encode(array_keys($campus['accounts_payable']['per_campus'])) : json_encode(array_keys($campus['accounts_payable']['by_category'])); ?>,
                    datasets: [{
                        label: 'Outstanding RECEIVABLES (₱)',
                        data: <?php echo $isAllCampuses ? json_encode(array_values($campus['accounts_payable']['per_campus'])) : json_encode(array_values($campus['accounts_payable']['by_category'])); ?>,
                        backgroundColor: <?php echo $isAllCampuses ? "'#204ca4'" : json_encode(['#204ca4', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#dbeafe']); ?>,
                        borderColor: <?php echo $isAllCampuses ? "'#204ca4'" : 'null'; ?>,
                        borderWidth: <?php echo $isAllCampuses ? '0' : '0'; ?>,
                        borderRadius: <?php echo $isAllCampuses ? '8' : '0'; ?>,
                        borderSkipped: <?php echo $isAllCampuses ? 'false' : 'true'; ?>
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: <?php echo $isAllCampuses ? '{
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: "#f3f4f6"
                            },
                            ticks: {
                                callback: function(value) {
                                    return "₱" + (value / 1000000).toFixed(0) + "M";
                                },
                                color: "#6b7280",
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: "#6b7280",
                                font: {
                                    size: 9
                                }
                            }
                        }
                    }' : 'null'; ?>,
                    plugins: {
                        legend: {
                            display: <?php echo $isAllCampuses ? 'false' : 'true'; ?>,
                            position: <?php echo $isAllCampuses ? 'null' : "'bottom'"; ?>,
                            labels: <?php echo $isAllCampuses ? 'null' : '{
                                color: "#6b7280",
                                font: {
                                    size: 10
                                }
                            }'; ?>
                        }
                    }
                }
            });

            // Wait for data manager to be available, then register charts and initialize
            function initializeDashboard() {
                if (typeof dataManager !== 'undefined') {
                    // Register charts with data manager
                    dataManager.registerChart('enrollmentChart', enrollmentChart);
                    dataManager.registerChart('collectionChart', collectionChart);
                    dataManager.registerChart('accountsPayableChart', accountsPayableChart);

                    // Initialize data manager - let it detect campus from URL
                    dataManager.updateDashboard();
                } else {
                    // Retry after a short delay
                    setTimeout(initializeDashboard, 100);
                }
            }
            
            // Start initialization
            initializeDashboard();
        </script>
    </body>
    </html>
    <?php
    return ob_get_clean();
}
?>
