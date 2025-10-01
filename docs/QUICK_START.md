# Quick Start Guide

Get your Campus Dashboard System up and running in minutes! This guide covers the enhanced, cleaned-up version with improved architecture and documentation.

## 🚀 5-Minute Setup

### 1. Download & Install
```bash
# Download to your web server directory
cd C:\xampp\htdocs\
# Extract or clone the project to 'new-dash' folder
```

### 2. Start Services
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**

### 3. Access Dashboard
- Open browser to `http://localhost/new-dash/`

### 4. Login
- **Username:** `admin`
- **Password:** `admin123`

### 5. Explore
- Click through different campus dashboards
- Notice the auto-refresh every 30 seconds
- Try the manual refresh button (🔄)

## 🆕 What's New in This Version

### ✨ Code Improvements
- **Clean Architecture** - Separated concerns with proper file organization
- **Helper Functions** - Centralized utility functions in `includes/helpers.php`
- **Enhanced APIs** - Improved error handling and validation
- **Template System** - Unified dashboard generation for all campuses
- **Better Documentation** - Comprehensive comments and guides
- **Standardized Campus Files** - All campus pages use the same clean template

### 🔧 Customization

### Change Data Source
1. Open `includes/data_service.php` - Enhanced with caching and error handling
2. Replace the sample data methods with your API calls
3. Update `api/get_data.php` - Now includes comprehensive validation

### Add New Campus
1. Add campus data to `config/data_config.php`
2. Create new file in `campuses/` directory using the template pattern:
   ```php
   <?php
   // Start session and check authentication
   session_start();
   if (!isset($_SESSION['user_id'])) {
       header('Location: ../login.php');
       exit();
   }
   
   // Include required files
   require_once '../config/data_config.php';
   require_once '../includes/dashboard_template.php';
   
   // Generate dashboard HTML
   echo generateDashboardHTML('your_campus_key', $campusData);
   ?>
   ```
3. Update sidebar navigation in `includes/dynamic_sidebar.php` - Add to `$campusItems` array

### Modify Styling
- Edit `css/style.css` for colors, fonts, layout
- Main color: `#204ca4` (blue)
- Background: `#ffffff` (white)
- Use helper functions in `includes/helpers.php` for consistent formatting

## 📊 Data Integration

### Current Setup
The system uses `config/data_config.php` for sample data. This file contains:
- Campus information
- Enrollment data
- Collection data  
- Accounts receivable data

### API Integration
To connect to your APIs:

1. **Update Data Service** (`includes/data_service.php`):
```php
public function getEnrollmentData($campus) {
    // Replace with your API call
    $response = $this->callAPI('enrollment', $campus);
    return $this->formatEnrollmentData($response);
}
```

2. **Update API Endpoint** (`api/get_data.php`):
```php
$dataService = new DataService();
$response['data']['enrollment'] = $dataService->getEnrollmentData($campus);
```

3. **Test Integration**:
```bash
php test_api_integration.php
```

## 🎯 Key Features

- ✅ **Real-time Updates** - Auto-refresh every 30 seconds
- ✅ **Responsive Design** - Works on all devices
- ✅ **Multiple Campuses** - Individual dashboards
- ✅ **Interactive Charts** - Chart.js visualizations
- ✅ **User Authentication** - Secure login system
- ✅ **Error Handling** - Graceful error recovery
- ✅ **API Ready** - Easy external data integration

## 🔍 File Structure

```
new-dash/
├── 📁 api/                    # API endpoints
├── 📁 campuses/               # Campus dashboards
├── 📁 config/                 # Configuration
├── 📁 css/                    # Stylesheets
├── 📁 includes/               # Reusable components
│   └── dynamic_sidebar.php    # Dynamic sidebar component
├── 📁 js/                     # JavaScript
├── dashboard.php              # Main dashboard
├── login.php                  # Authentication
└── README.md                  # Documentation
```

## 🛠️ Development

### Local Development
1. Use XAMPP or similar local server
2. Place files in web root directory
3. Start Apache and MySQL
4. Open `http://localhost/new-dash/`

### Production Deployment
1. Upload files to web server
2. Configure database connection
3. Set up SSL certificates
4. Update API endpoints
5. Test all functionality

## 🐛 Troubleshooting

### Common Issues

**Charts not loading?**
- Check browser console for errors
- Verify Chart.js is loaded
- Check API responses

**Data not updating?**
- Verify API endpoints
- Check network connectivity
- Review error logs

**Login not working?**
- Clear browser cache
- Check session configuration
- Verify credentials

### Debug Mode
Add to top of PHP files:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📱 Mobile Testing

### Responsive Breakpoints
- **Desktop (992px+):** 3-column layout with sidebar always visible, collapsible to icons
- **Tablet/Phone (991px and below):** 1-column layout with hidden sidebar, toggle to show

### Testing Methods
- **Browser Dev Tools:** Use responsive design mode
- **Real Devices:** Test on actual phones and tablets
- **Different Orientations:** Test both portrait and landscape
- **Touch Interactions:** Verify touch targets are adequate

### Key Features
- **Dynamic sidebar** that adapts to screen size
- **Collapsible desktop sidebar** - click to toggle icon-only mode
- **Slide-in mobile sidebar** - smooth animations
- **Touch-friendly** buttons and navigation
- **Fluid typography** that scales with screen size
- **Smart grid layouts** that reorganize content

## 🔄 Next Steps

1. **Integrate Your APIs** - Follow the API Integration Guide
2. **Customize Styling** - Modify colors and layout
3. **Add New Features** - Extend functionality
4. **Deploy to Production** - Set up live environment
5. **Monitor Performance** - Track usage and errors

## 📞 Support

- Check the main README.md for detailed documentation
- Review the API Integration Guide for external data
- Check browser console for JavaScript errors
- Verify API connectivity and responses

---

**Ready to go!** Your dashboard system is now running with sample data. Follow the integration guide to connect your real data sources.
