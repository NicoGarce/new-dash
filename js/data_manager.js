/**
 * Data Manager
 * Handles dynamic data fetching and updates for dashboards
 */

class DataManager {
    constructor() {
        // Determine the correct API path based on current location
        const currentPath = window.location.pathname;
        this.baseUrl = currentPath.includes('/campuses/') ? '../api/get_data.php' : 'api/get_data.php';
        this.refreshInterval = 30000; // 30 seconds
        this.isRefreshing = false;
        this.charts = {};
        this.lastUpdate = null;
        
        // Initialize auto-refresh
        this.startAutoRefresh();
        
        // Sidebar toggle is now handled by the simple function below
    }
    
    /**
     * Fetch data from API
     */
    async fetchData(campus = 'all_campuses', dataType = 'all') {
        try {
            const url = `${this.baseUrl}?campus=${campus}&type=${dataType}`;
            const response = await fetch(url, {
                method: 'GET',
                credentials: 'same-origin', // Include cookies for session
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            this.lastUpdate = data.timestamp;
            return data;
        } catch (error) {
            console.error('Error fetching data:', error);
            this.showError('Failed to fetch data. Please check your connection.');
            return null;
        }
    }
    
    /**
     * Update dashboard with new data
     */
    async updateDashboard(campus = 'all_campuses') {
        if (this.isRefreshing) return;
        
        this.isRefreshing = true;
        this.showLoading(true);
        
        try {
            const data = await this.fetchData(campus);
            if (data && data.success) {
                this.updateCharts(data.data);
                this.updateSummaryCards(data.data);
                this.updateLastUpdateTime(data.timestamp);
            }
        } catch (error) {
            console.error('Error updating dashboard:', error);
        } finally {
            this.isRefreshing = false;
            this.showLoading(false);
        }
    }
    
    /**
     * Update charts with new data
     */
    updateCharts(data) {
        // Update enrollment chart
        if (data.enrollment && this.charts.enrollmentChart) {
            this.updateEnrollmentChart(data.enrollment);
        }
        
        // Update collection chart
        if (data.collection && this.charts.collectionChart) {
            this.updateCollectionChart(data.collection);
        }
        
        // Update accounts payable chart
        if (data.accounts_payable && this.charts.accountsPayableChart) {
            this.updateAccountsPayableChart(data.accounts_payable);
        }
    }
    
    /**
     * Update enrollment chart
     */
    updateEnrollmentChart(data) {
        const chart = this.charts.enrollmentChart;
        
        if (data.per_campus) {
            // All campuses view
            chart.data.labels = Object.keys(data.per_campus);
            chart.data.datasets[0].data = Object.values(data.per_campus);
        } else if (data.per_college) {
            // Individual campus view
            chart.data.labels = Object.keys(data.per_college);
            chart.data.datasets[0].data = Object.values(data.per_college);
        } else if (data.per_sy) {
            // Yearly trend
            chart.data.labels = ['2021', '2022', '2023', '2024', '2025'];
            chart.data.datasets[0].data = data.per_sy;
        }
        
        chart.update('active');
    }
    
    /**
     * Update collection chart
     */
    updateCollectionChart(data) {
        const chart = this.charts.collectionChart;
        
        if (data.per_campus) {
            // All campuses view
            chart.data.labels = Object.keys(data.per_campus);
            chart.data.datasets[0].data = Object.values(data.per_campus);
        } else if (data.monthly) {
            // Individual campus view
            chart.data.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            chart.data.datasets[0].data = data.monthly;
        }
        
        chart.update('active');
    }
    
    /**
     * Update accounts payable chart
     */
    updateAccountsPayableChart(data) {
        const chart = this.charts.accountsPayableChart;
        
        if (data.per_campus) {
            // All campuses view
            chart.data.labels = Object.keys(data.per_campus);
            chart.data.datasets[0].data = Object.values(data.per_campus);
        } else if (data.by_category) {
            // Individual campus view
            chart.data.labels = Object.keys(data.by_category);
            chart.data.datasets[0].data = Object.values(data.by_category);
        }
        
        chart.update('active');
    }
    
    /**
     * Update summary cards
     */
    updateSummaryCards(data) {
        // Update enrollment summary
        if (data.enrollment) {
            this.updateElement('.enrollment-current', this.formatNumber(data.enrollment.current_year));
            this.updateElement('.enrollment-previous', this.formatNumber(data.enrollment.previous_year));
        }
        
        // Update collection summary
        if (data.collection) {
            this.updateElement('.collection-current', this.formatCurrency(data.collection.current_year));
            this.updateElement('.collection-previous', this.formatCurrency(data.collection.previous_year));
        }
        
        // Update accounts payable summary
        if (data.accounts_payable) {
            this.updateElement('.payables-current', this.formatCurrency(data.accounts_payable.current_year));
            this.updateElement('.payables-previous', this.formatCurrency(data.accounts_payable.previous_year));
        }
    }
    
    /**
     * Update DOM element
     */
    updateElement(selector, value) {
        const element = document.querySelector(selector);
        if (element) {
            element.textContent = value;
        }
    }
    
    /**
     * Format number with commas
     */
    formatNumber(num) {
        return new Intl.NumberFormat().format(num);
    }
    
    /**
     * Format currency
     */
    formatCurrency(num) {
        return '₱' + new Intl.NumberFormat().format(num);
    }
    
    /**
     * Update last update time
     */
    updateLastUpdateTime(timestamp) {
        const element = document.querySelector('.last-update');
        if (element) {
            element.textContent = `Last updated: ${timestamp}`;
        }
    }
    
    /**
     * Show loading state
     */
    showLoading(show) {
        const loadingElement = document.querySelector('.loading-overlay');
        if (loadingElement) {
            loadingElement.style.display = show ? 'flex' : 'none';
        }
    }
    
    /**
     * Show error message
     */
    showError(message) {
        const errorElement = document.querySelector('.error-message');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            
            // Hide after 5 seconds
            setTimeout(() => {
                errorElement.style.display = 'none';
            }, 5000);
        }
    }
    
    /**
     * Start auto-refresh
     */
    startAutoRefresh() {
        setInterval(() => {
            this.updateDashboard();
        }, this.refreshInterval);
    }
    
    /**
     * Stop auto-refresh
     */
    stopAutoRefresh() {
        if (this.refreshIntervalId) {
            clearInterval(this.refreshIntervalId);
        }
    }
    
    /**
     * Register chart for updates
     */
    registerChart(name, chart) {
        this.charts[name] = chart;
    }
    
    /**
     * Manual refresh
     */
    async refresh() {
        await this.updateDashboard();
    }
    
    /**
     * Update main content padding based on sidebar state and screen size
     */
    updateMainContentPadding() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (sidebar && mainContent) {
            // Desktop (992px+) - sidebar always visible
            if (window.innerWidth >= 992) {
                if (sidebar.classList.contains('collapsed')) {
                    mainContent.style.paddingLeft = '60px';
                } else {
                    mainContent.style.paddingLeft = '150px';
                }
            } 
            // Tablets and phones (991px and below) - sidebar hidden by default
            else {
                mainContent.style.paddingLeft = '0px';
            }
        }
    }

    /**
     * Initialize sidebar state based on screen size
     */
    initializeSidebarState() {
        // Sidebar state is now handled by CSS and initSimpleSidebarToggle()
        // This method is kept for compatibility but does nothing
    }

}

// Simple sidebar toggle functionality
function initSimpleSidebarToggle() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Handle mobile/tablet toggle button
    if (sidebarToggle && sidebar && sidebarOverlay) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('open');
        });
    }
    
    // Desktop toggle functionality removed - sidebar is always visible on desktop
    
    // Handle sidebar overlay click (mobile/tablet)
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('open');
        });
    }
    
    // Handle sidebar close button
    if (sidebarClose && sidebar && sidebarOverlay) {
        sidebarClose.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('open');
        });
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSimpleSidebarToggle);
} else {
    initSimpleSidebarToggle();
}

// Global data manager instance - create after DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.dataManager = new DataManager();
    });
} else {
    window.dataManager = new DataManager();
}
