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
            this.showError('Failed to fetch data. Please check your connection.');
            return null;
        }
    }
    
    /**
     * Update dashboard with new data
     */
    async updateDashboard(campus = null) {
        if (this.isRefreshing) return;
        
        // If no campus specified, detect it dynamically
        if (!campus) {
            campus = this.getCurrentCampus();
        }
        
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
            // Silent fail
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
        
        if (!chart || !chart.data || !chart.data.datasets || !chart.data.datasets[0]) {
            return;
        }
        
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
        
        try {
            chart.update('none');
        } catch (error) {
            // Silent fail
        }
    }
    
    /**
     * Update collection chart
     */
    updateCollectionChart(data) {
        const chart = this.charts.collectionChart;
        
        if (!chart || !chart.data || !chart.data.datasets || !chart.data.datasets[0]) {
            return;
        }
        
        if (data.per_campus) {
            // All campuses view
            chart.data.labels = Object.keys(data.per_campus);
            chart.data.datasets[0].data = Object.values(data.per_campus);
        } else if (data.monthly) {
            // Individual campus view
            chart.data.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            chart.data.datasets[0].data = data.monthly;
        }
        
        try {
            chart.update('none');
        } catch (error) {
            // Silent fail
        }
    }
    
    /**
     * Update accounts payable chart
     */
    updateAccountsPayableChart(data) {
        const chart = this.charts.accountsPayableChart;
        
        if (!chart) {
            return;
        }
        
        // Validate chart structure
        if (!chart.data || !chart.data.datasets || !chart.data.datasets[0]) {
            return;
        }
        
        let labels = [];
        let values = [];
        
        if (data.per_campus) {
            // All campuses view
            labels = Object.keys(data.per_campus);
            values = Object.values(data.per_campus);
        } else if (data.by_category) {
            // Individual campus view
            labels = Object.keys(data.by_category);
            values = Object.values(data.by_category);
        } else {
            return;
        }
        
        // Validate data
        if (labels.length === 0 || values.length === 0 || labels.length !== values.length) {
            return;
        }
        
        // Ensure all values are numbers
        const numericValues = values.map(val => {
            const num = parseFloat(val);
            return isNaN(num) ? 0 : num;
        });
        
        // Update chart data
        chart.data.labels = labels;
        chart.data.datasets[0].data = numericValues;
        
        try {
            chart.update('none'); // Use 'none' instead of 'active' to avoid animation issues
        } catch (error) {
            // Try to reinitialize the chart if update fails
            try {
                chart.destroy();
                this.charts.accountsPayableChart = null;
            } catch (destroyError) {
                // Silent fail
            }
        }
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
            this.updateDashboard(); // This will auto-detect the campus
        }, this.refreshInterval);
    }
    
    /**
     * Get current campus from URL or window variable
     */
    getCurrentCampus() {
        const path = window.location.pathname;
        
        // ALWAYS prioritize URL detection over window.currentCampus
        // Check if we're on a campus page
        if (path.includes('/campuses/')) {
            const campusMatch = path.match(/\/campuses\/([^\/]+)\.php/);
            if (campusMatch) {
                const detectedCampus = campusMatch[1];
                // Always update window.currentCampus to match URL
                window.currentCampus = detectedCampus;
                return detectedCampus;
            }
        }
        
        // Check if we're on the main dashboard
        if (path.endsWith('/dashboard.php') || path.endsWith('/index.php') || path === '/' || path.endsWith('/')) {
            window.currentCampus = 'all_campuses';
            return 'all_campuses';
        }
        
        // Only use window variable if URL detection completely fails
        if (window.currentCampus) {
            return window.currentCampus;
        }
        
        // Default
        return 'all_campuses';
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
        await this.updateDashboard(); // This will auto-detect the campus
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
