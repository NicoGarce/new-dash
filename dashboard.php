    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ALL CAMPUSES Dashboard</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="js/data_manager.js"></script>
        <link rel="stylesheet" href="css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <div class="sidebar">
    <h2>CAMPUSES</h2>
    <ul class="nav-list">
        <li>
            <a href="dashboard.php" class="">
                <i class="fas fa-chart-bar"></i>
                <span>JONELTA</span>
            </a>
        </li>
        <li>
            <a href="campuses/binan.php">
                <i class="fas fa-university"></i>
                <span>BIÑAN</span>
            </a>
        </li>
        <li>
            <a href="campuses/gma_cavite.php">
                <i class="fas fa-university"></i>
                <span>GMA CAVITE</span>
            </a>
        </li>
        <li>
            <a href="campuses/manila.php">
                <i class="fas fa-university"></i>
                <span>MANILA</span>
            </a>
        </li>
        <li>
            <a href="campuses/pangasinan.php">
                <i class="fas fa-university"></i>
                <span>PANGASINAN</span>
            </a>
        </li>
        <li>
            <a href="campuses/isabela.php">
                <i class="fas fa-university"></i>
                <span>ISABELA</span>
            </a>
        </li>
        <li>
            <a href="campuses/roxas.php">
                <i class="fas fa-university"></i>
                <span>ROXAS</span>
            </a>
        </li>
        <li>
            <a href="campuses/med-university.php">
                <i class="fas fa-university"></i>
                <span>MEDICAL UNIVERSITY</span>
            </a>
        </li>
    </ul>
</div>        <div class="main-content">
            <div class="header-section">
                <h1>UNIVERSITY OF PERPETUAL HELP SYSTEM JONELTA</h1>
                <div class="user-info">
                    <span class="last-update">Last updated: 2025-09-17 01:24:31</span>
                    <button class="refresh-btn" onclick="dataManager.refresh()" title="Refresh Data">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <span>Welcome, User</span>
                    <a href="logout.php" class="logout-btn">
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
                                <h5>GRAND TOTAL ENROLLEES AS OF SEPTEMBER S.Y. 2025-2026</h4>
                            <div class="big-number enrollment-current">204,534</div>
                        </div>
                        <div class="column-summary-card">
                            <h4>SUMMARY PREV S.Y.</h4>
                            <h5>GRAND TOTAL ENROLLEES S.Y. 2024-2025</h5>
                            <div class="big-number enrollment-previous">169,916</div>
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
                            <div class="big-number collection-current">₱2,456,789,000</div>
                        </div>
                        <div class="column-summary-card">
                            <h4>COLLECTION PREV S.Y.</h4>
                            <h5>TOTAL COLLECTION S.Y. 2024-2025</h5>
                            <div class="big-number collection-previous">₱2,123,456,000</div>
                            </div>
                        </div>
                        <div class="column-chart">
                            <canvas id="collectionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Accounts Payable Column -->
                <div class="column">
                    <div class="column-header">
                        <h3>ACCOUNTS PAYABLE</h3>
                    </div>
                    <div class="column-content">
                        <div class="column-summary">
                            <div class="column-summary-card">
                                <h4>ACCOUNTS PAYABLE SUMMARY</h4>
                                <h5>TOTAL OUTSTANDING PAYABLES S.Y. 2025-2026</h5>
                            <div class="big-number payables-current">₱456,789,000</div>
                        </div>
                        <div class="column-summary-card">
                            <h4>PAYABLES PREV S.Y.</h4>
                            <h5>TOTAL OUTSTANDING PAYABLES S.Y. 2024-2025</h5>
                            <div class="big-number payables-previous">₱423,456,000</div>
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
            const campusData = {"name":"ALL CAMPUSES","enrollment":{"current_year":204534,"previous_year":169916,"per_campus":{"BI\u00d1AN":45000,"GMA CAVITE":38000,"MANILA":42000,"PANGASINAN":35000,"ISABELA":28000,"ROXAS":15000,"MEDICAL UNIVERSITY":12000}},"collection":{"current_year":2456789000,"previous_year":2123456000,"per_campus":{"BI\u00d1AN":450000000,"GMA CAVITE":380000000,"MANILA":420000000,"PANGASINAN":350000000,"ISABELA":280000000,"ROXAS":150000000,"MEDICAL UNIVERSITY":120000000}},"accounts_payable":{"current_year":456789000,"previous_year":423456000,"per_campus":{"BI\u00d1AN":85000000,"GMA CAVITE":72000000,"MANILA":78000000,"PANGASINAN":65000000,"ISABELA":52000000,"ROXAS":28000000,"MEDICAL UNIVERSITY":22000000}}};
            const isAllCampuses = true;
            const currentCampus = 'all_campuses';

            // Enrollment Chart
            const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
            const enrollmentChart = new Chart(enrollmentCtx, {
                type: 'bar',
                data: {
                    labels: ["BI\u00d1AN","GMA CAVITE","MANILA","PANGASINAN","ISABELA","ROXAS","MEDICAL UNIVERSITY"],
                    datasets: [{
                        label: 'Enrollees',
                        data: [45000,38000,42000,35000,28000,15000,12000],
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
                type: 'bar',
                data: {
                    labels: ["BI\u00d1AN","GMA CAVITE","MANILA","PANGASINAN","ISABELA","ROXAS","MEDICAL UNIVERSITY"],
                    datasets: [{
                        label: 'Collection (₱)',
                        data: [450000000,380000000,420000000,350000000,280000000,150000000,120000000],
                        backgroundColor: '#204ca4',
                        borderColor: '#204ca4',
                        borderWidth: 0,
                        borderRadius: 8,
                        borderSkipped: false,
                        fill: false,
                        tension: 0,
                        pointBackgroundColor: null,
                        pointBorderColor: null,
                        pointRadius: 0,
                        pointHoverRadius: 0                    }]
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

            // Accounts Payable Chart
            const accountsPayableCtx = document.getElementById('accountsPayableChart').getContext('2d');
            const accountsPayableChart = new Chart(accountsPayableCtx, {
                type: 'bar',
                data: {
                    labels: ["BI\u00d1AN","GMA CAVITE","MANILA","PANGASINAN","ISABELA","ROXAS","MEDICAL UNIVERSITY"],
                    datasets: [{
                        label: 'Outstanding Payables (₱)',
                        data: [85000000,72000000,78000000,65000000,52000000,28000000,22000000],
                        backgroundColor: '#204ca4',
                        borderColor: '#204ca4',
                        borderWidth: 0,
                        borderRadius: 8,
                        borderSkipped: false                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
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
                    },
                    plugins: {
                        legend: {
                            display: false,
                            position: null,
                            labels: null                        }
                    }
                }
            });

            // Register charts with data manager
            dataManager.registerChart('enrollmentChart', enrollmentChart);
            dataManager.registerChart('collectionChart', collectionChart);
            dataManager.registerChart('accountsPayableChart', accountsPayableChart);

            // Initialize data manager for current campus
            dataManager.updateDashboard(currentCampus);
        </script>
    </body>
    </html>
    