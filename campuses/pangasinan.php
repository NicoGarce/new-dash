    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PANGASINAN Dashboard</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="../js/data_manager.js"></script>
        <link rel="stylesheet" href="../css/style.css">
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
            <a href="../dashboard.php">
                <i class="fas fa-chart-bar"></i>
                <span>JONELTA</span>
            </a>
        </li>
        <li>
            <a href="../campuses/binan.php" class="">
                <i class="fas fa-university"></i>
                <span>BIÑAN</span>
            </a>
        </li>
        <li>
            <a href="../campuses/gma_cavite.php" class="">
                <i class="fas fa-university"></i>
                <span>GMA CAVITE</span>
            </a>
        </li>
        <li>
            <a href="../campuses/manila.php" class="">
                <i class="fas fa-university"></i>
                <span>MANILA</span>
            </a>
        </li>
        <li>
            <a href="../campuses/pangasinan.php" class="">
                <i class="fas fa-university"></i>
                <span>PANGASINAN</span>
            </a>
        </li>
        <li>
            <a href="../campuses/isabela.php" class="">
                <i class="fas fa-university"></i>
                <span>ISABELA</span>
            </a>
        </li>
        <li>
            <a href="../campuses/roxas.php" class="">
                <i class="fas fa-university"></i>
                <span>ROXAS</span>
            </a>
        </li>
        <li>
            <a href="../campuses/med-university.php" class="">
                <i class="fas fa-university"></i>
                <span>MEDICAL UNIVERSITY</span>
            </a>
        </li>
    </ul>
</div>        <div class="main-content">
            <div class="header-section">
                <h1>PANGASINAN</h1>
                <div class="user-info">
                    <span class="last-update">Last updated: 2025-09-17 01:52:21</span>
                    <button class="refresh-btn" onclick="dataManager.refresh()" title="Refresh Data">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <span>Welcome, User</span>
                    <a href="../logout.php" class="logout-btn">
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
                                <h5>NEW ENROLLEES SEPTEMBER S.Y. 2025-2026</h4>
                            <div class="big-number enrollment-current">350</div>
                        </div>
                        <div class="column-summary-card">
                            <h4>SUMMARY PREV S.Y.</h4>
                            <h5>NEW ENROLLEES SEPTEMBER S.Y. 2024-2025</h5>
                            <div class="big-number enrollment-previous">330</div>
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
                            <div class="big-number collection-current">₱35,000,000</div>
                        </div>
                        <div class="column-summary-card">
                            <h4>COLLECTION PREV S.Y.</h4>
                            <h5>TOTAL COLLECTION S.Y. 2024-2025</h5>
                            <div class="big-number collection-previous">₱33,000,000</div>
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
                            <div class="big-number payables-current">₱6,500,000</div>
                        </div>
                        <div class="column-summary-card">
                            <h4>PAYABLES PREV S.Y.</h4>
                            <h5>TOTAL OUTSTANDING PAYABLES S.Y. 2024-2025</h5>
                            <div class="big-number payables-previous">₱6,200,000</div>
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
            const campusData = {"name":"PANGASINAN","enrollment":{"current_year":350,"previous_year":330,"per_sy":[300,320,340,330,350],"per_college":{"CAS":35,"CBA":30,"CCS":32,"CEA":28,"CHM":22,"CME":20,"COED":24,"CRM":13,"GRAD":10,"LAW":15,"SOA":13}},"collection":{"current_year":35000000,"previous_year":33000000,"monthly":[2600000,3000000,3200000,3000000,3300000,3100000,2900000,3200000,3500000,3300000,2900000,2600000]},"accounts_payable":{"current_year":6500000,"previous_year":6200000,"by_category":{"Supplies":1800000,"Utilities":1400000,"Maintenance":1200000,"Equipment":900000,"Services":700000,"Other":350000}}};
            const isAllCampuses = false;
            const currentCampus = 'pangasinan';

            // Enrollment Chart
            const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
            const enrollmentChart = new Chart(enrollmentCtx, {
                type: 'bar',
                data: {
                    labels: ["CAS","CBA","CCS","CEA","CHM","CME","COED","CRM","GRAD","LAW","SOA"],
                    datasets: [{
                        label: 'Enrollees',
                        data: [35,30,32,28,22,20,24,13,10,15,13],
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
                type: 'line',
                data: {
                    labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                    datasets: [{
                        label: 'Collection (₱)',
                        data: [2600000,3000000,3200000,3000000,3300000,3100000,2900000,3200000,3500000,3300000,2900000,2600000],
                        backgroundColor: 'rgba(32, 76, 164, 0.1)',
                        borderColor: '#204ca4',
                        borderWidth: 3,
                        borderRadius: 0,
                        borderSkipped: true,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#204ca4',
                        pointBorderColor: '#204ca4',
                        pointRadius: 4,
                        pointHoverRadius: 6                    }]
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
                type: 'doughnut',
                data: {
                    labels: ["Supplies","Utilities","Maintenance","Equipment","Services","Other"],
                    datasets: [{
                        label: 'Outstanding Payables (₱)',
                        data: [1800000,1400000,1200000,900000,700000,350000],
                        backgroundColor: ["#204ca4","#3b82f6","#60a5fa","#93c5fd","#bfdbfe","#dbeafe"],
                        borderColor: null,
                        borderWidth: 0,
                        borderRadius: 0,
                        borderSkipped: true                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: null,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: "#6b7280",
                                font: {
                                    size: 10
                                }
                            }                        }
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
    