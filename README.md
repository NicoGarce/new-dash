# Campus Dashboard System

A modern, responsive dashboard system for managing campus data including enrollment, collection, and accounts payable information across multiple campuses. Built with clean, maintainable code and comprehensive documentation.

## ✨ Key Features

- **🏗️ Clean Architecture** - Well-organized, documented codebase with separation of concerns
- **📱 Fully Responsive** - Optimized for desktop, tablet, and mobile devices
- **🔄 Real-time Updates** - Auto-refresh every 30 seconds with manual refresh option
- **📊 Interactive Charts** - Dynamic Chart.js visualizations with responsive design
- **🏫 Multi-Campus Support** - Individual dashboards for each campus
- **🔌 API Ready** - Easy integration with external data sources
- **🔐 Secure Authentication** - Session-based login system
- **⚡ Performance Optimized** - Caching, error handling, and efficient data management
- **📚 Comprehensive Documentation** - Detailed guides and code comments

## 🏗️ Project Structure

```
new-dash/
├── 📁 api/                          # API endpoints
│   ├── get_data.php                 # Enhanced data fetching API with validation
│   └── update_data.php              # Secure data update API with error handling
├── 📁 campuses/                     # Individual campus dashboards (standardized)
│   ├── binan.php                    # BIÑAN campus - clean template-based
│   ├── gma_cavite.php              # GMA CAVITE campus - clean template-based
│   ├── manila.php                  # MANILA campus - clean template-based
│   ├── pangasinan.php              # PANGASINAN campus - clean template-based
│   ├── isabela.php                 # ISABELA campus - clean template-based
│   ├── roxas.php                   # ROXAS campus - clean template-based
│   └── med-university.php          # MEDICAL UNIVERSITY - clean template-based
├── 📁 config/                       # Configuration files
│   ├── data_config.php             # Centralized data configuration
│   └── database.php                # Database configuration (ready for integration)
├── 📁 css/                          # Stylesheets
│   ├── style.css                   # Main dashboard styles
│   └── login.css                   # Login page styles
├── 📁 includes/                     # Reusable components & services
│   ├── dashboard_template.php      # Unified dashboard template generator
│   ├── data_service.php            # Enhanced data service with caching
│   ├── dynamic_sidebar.php         # Responsive sidebar component
│   └── helpers.php                 # Utility functions and helpers
├── 📁 js/                           # JavaScript files
│   └── data_manager.js             # Enhanced data management with error handling
├── 📁 docs/                         # Documentation
│   ├── QUICK_START.md              # Quick setup guide
│   ├── API_INTEGRATION_GUIDE.md    # API integration documentation
│   └── RESPONSIVE_DESIGN_GUIDE.md  # Responsive design documentation
├── 📁 img/                          # Images and assets
│   └── SS.png                      # University logo
├── dashboard.php                    # Main dashboard (clean, template-based)
├── login.php                        # Enhanced authentication with validation
├── logout.php                       # User logout
├── index.php                        # Entry point
└── README.md                        # This comprehensive guide
```

## 🚀 Features

- **Real-time Data Updates** - Auto-refresh every 30 seconds
- **Dynamic Charts** - Interactive Chart.js visualizations
- **Responsive Design** - Works on desktop, tablet, and mobile
- **Multi-Campus Support** - Individual dashboards for each campus
- **Live Data Integration** - Ready for external API integration
- **User Authentication** - Secure login system
- **Admin Interface** - Easy data management
- **Error Handling** - Graceful error recovery

## 🔧 Setup Instructions

### 1. Prerequisites
- **XAMPP** or similar local server environment
- **PHP 7.4+** with JSON support
- **Web browser** with JavaScript enabled

### 2. Installation
1. Clone or download the project to your web server directory
2. Place files in `C:\xampp\htdocs\new-dash\` (or your web root)
3. Start Apache and MySQL in XAMPP
4. Open `http://localhost/new-dash/` in your browser

**Note:** The system now uses a dynamic sidebar that automatically updates across all pages without requiring regeneration scripts.

### 3. Default Login
- **Username:** admin
- **Password:** admin123

### 4. Dynamic Sidebar System
The system now features a dynamic sidebar that:
- **Auto-detects current page** and highlights active navigation
- **Updates automatically** across all pages without regeneration
- **Responsive design** with different behavior for desktop/mobile
- **Single source of truth** - edit once, updates everywhere

## 📊 Data Integration Guide

### Current Data Source
The system currently uses `config/data_config.php` for sample data. This file contains:
- Campus information
- Enrollment data (current/previous year)
- Collection data (financial information)
- Accounts payable data

### API Integration Structure

#### 1. Data Service Layer (`includes/data_service.php`)
This is your main integration point. Update the methods to fetch from your APIs:

```php
class DataService {
    private $apiBaseUrl = 'https://your-api.com/api/v1/';
    
    public function getEnrollmentData($campus) {
        // Replace with your API call
        $response = $this->callAPI('enrollment', $campus);
        return $this->formatEnrollmentData($response);
    }
    
    public function getCollectionData($campus) {
        // Replace with your API call
        $response = $this->callAPI('collection', $campus);
        return $this->formatCollectionData($response);
    }
    
    public function getAccountsPayableData($campus) {
        // Replace with your API call
        $response = $this->callAPI('accounts-payable', $campus);
        return $this->formatPayableData($response);
    }
    
    private function callAPI($endpoint, $campus) {
        $url = $this->apiBaseUrl . $endpoint . '?campus=' . $campus;
        // Add your API authentication headers here
        $headers = [
            'Authorization: Bearer ' . $this->getApiToken(),
            'Content-Type: application/json'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new Exception("API call failed with status: $httpCode");
        }
        
        return json_decode($response, true);
    }
}
```

#### 2. API Endpoints (`api/get_data.php`)
Update this file to use your data service:

```php
require_once '../includes/data_service.php';

$dataService = new DataService();
$campus = $_GET['campus'] ?? 'all_campuses';
$dataType = $_GET['type'] ?? 'all';

$response = [
    'success' => true,
    'timestamp' => date('Y-m-d H:i:s'),
    'campus' => $campus,
    'data' => []
];

if ($dataType === 'all' || $dataType === 'enrollment') {
    $response['data']['enrollment'] = $dataService->getEnrollmentData($campus);
}

if ($dataType === 'all' || $dataType === 'collection') {
    $response['data']['collection'] = $dataService->getCollectionData($campus);
}

if ($dataType === 'all' || $dataType === 'accounts_payable') {
    $response['data']['accounts_payable'] = $dataService->getAccountsPayableData($campus);
}

echo json_encode($response);
```

### Expected Data Format

Your APIs should return data in this format:

#### Enrollment Data
```json
{
    "current_year": 204534,
    "previous_year": 169916,
    "per_campus": {
        "BIÑAN": 45000,
        "GMA CAVITE": 38000,
        "MANILA": 42000
    },
    "per_college": {
        "CAS": 45,
        "CBA": 38,
        "CCS": 42
    },
    "per_sy": [350, 380, 420, 380, 405]
}
```

#### Collection Data
```json
{
    "current_year": 2456789000,
    "previous_year": 2123456000,
    "per_campus": {
        "BIÑAN": 450000000,
        "GMA CAVITE": 380000000
    },
    "monthly": [3200000, 3800000, 4200000, 3900000, 4100000, 3800000, 3600000, 4000000, 4500000, 4200000, 3800000, 3500000]
}
```

#### Accounts Payable Data
```json
{
    "current_year": 456789000,
    "previous_year": 423456000,
    "per_campus": {
        "BIÑAN": 85000000,
        "GMA CAVITE": 72000000
    },
    "by_category": {
        "Supplies": 2500000,
        "Utilities": 1800000,
        "Maintenance": 1500000
    }
}
```

## 🔄 Real-Time Updates

### Auto-Refresh Configuration
The system automatically refreshes data every 30 seconds. To modify:

```javascript
// In js/data_manager.js
this.refreshInterval = 30000; // Change to desired milliseconds
```

### Manual Refresh
Users can click the refresh button (🔄) in the header to update data immediately.

### WebSocket Integration (Optional)
For true real-time updates, you can integrate WebSockets:

```javascript
// Add to js/data_manager.js
initWebSocket() {
    this.ws = new WebSocket('ws://your-websocket-server:8080');
    this.ws.onmessage = (event) => {
        const data = JSON.parse(event.data);
        this.updateDashboard(data.campus);
    };
}
```

## 🎨 Customization

### Adding New Campuses
1. Add campus data to `config/data_config.php`
2. Create new campus file in `campuses/` directory
3. Update sidebar navigation in `includes/dynamic_sidebar.php`

### Modifying Charts
Charts are configured in `includes/dashboard_template.php`. You can:
- Change chart types (bar, line, doughnut)
- Modify colors and styling
- Add new chart types
- Customize data formatting

### Styling
Main styles are in `css/style.css`. Key classes:
- `.three-column-layout` - Main dashboard layout
- `.column` - Individual data columns
- `.summary-card` - Data summary cards
- `.chart-container` - Chart styling

## 🔐 Security

### Authentication
- Session-based authentication
- Login/logout functionality
- Session timeout handling

### API Security
When integrating with external APIs:
- Use HTTPS for all API calls
- Implement proper authentication (API keys, OAuth, etc.)
- Validate and sanitize all input data
- Implement rate limiting
- Log all API calls for monitoring

### Data Validation
```php
// Example validation in data service
private function validateEnrollmentData($data) {
    if (!isset($data['current_year']) || !is_numeric($data['current_year'])) {
        throw new InvalidArgumentException('Invalid enrollment data');
    }
    return $data;
}
```

## 📱 Mobile Responsiveness

The dashboard is fully responsive with comprehensive breakpoints:

### 🖥️ **Desktop (992px+)**
- **3-column layout** with optimal spacing
- **Sidebar always visible** (150px width, collapsible to 60px)
- **No toggle buttons** - clean desktop interface
- **Large charts** with full functionality

### 📱 **Tablet/Phone (991px and below)**
- **1-column layout** for optimal mobile viewing
- **Sidebar hidden by default** - slides in from left when toggled
- **Toggle buttons visible** - hamburger menu in header and floating button
- **Touch-friendly interface** with optimized spacing
- **Responsive charts** that adapt to screen size

### 🎯 **Key Responsive Features:**
- **Dynamic sidebar** that adapts to screen size
- **Collapsible desktop sidebar** - click to toggle icon-only mode
- **Slide-in mobile sidebar** - smooth animations
- **Touch-friendly** buttons and navigation
- **Fluid typography** that scales with screen size
- **Smart grid layouts** that reorganize content
- **Consistent branding** across all devices

## 🐛 Troubleshooting

### Common Issues

1. **Charts not loading**
   - Check browser console for JavaScript errors
   - Verify Chart.js is loaded
   - Check API responses

2. **Data not updating**
   - Verify API endpoints are accessible
   - Check network connectivity
   - Review browser console for errors

3. **Login issues**
   - Clear browser cache and cookies
   - Check session configuration
   - Verify user credentials

### Debug Mode
Enable debug mode by adding to the top of PHP files:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 🚀 Deployment

### Production Checklist
- [ ] Update API endpoints to production URLs
- [ ] Configure proper authentication
- [ ] Set up SSL certificates
- [ ] Configure error logging
- [ ] Set up monitoring
- [ ] Test all functionality
- [ ] Backup configuration

### Performance Optimization
- Enable PHP OPcache
- Use CDN for static assets
- Implement caching for API responses
- Optimize database queries
- Compress images and assets

## 📞 Support

For technical support or questions:
1. Check this README first
2. Review the code comments
3. Check browser console for errors
4. Verify API connectivity
5. Test with sample data

## 🔄 Version History

- **v1.0** - Initial release with config-based data
- **v1.1** - Added real-time updates and API integration
- **v1.2** - Enhanced mobile responsiveness
- **v1.3** - Added admin interface and error handling
- **v2.0** - Dynamic sidebar system, removed regeneration scripts, improved responsive design

---

**Note:** This system is designed to be flexible and easily integrated with any external data source. The modular structure allows for easy customization and extension based on your specific requirements.