# API Integration Guide

This guide explains how to integrate external APIs with the Campus Dashboard System.

## 🔌 Integration Overview

The dashboard system is designed to work with external APIs for real-time data. The integration happens through the `DataService` class and API endpoints.

## 📁 Key Files for Integration

```
includes/data_service.php    # Main data service layer
api/get_data.php            # API endpoint for data fetching
api/update_data.php         # API endpoint for data updates
config/data_config.php      # Current sample data (replace with API calls)
```

## 🔧 Step-by-Step Integration

### Step 1: Update Data Service Layer

Modify `includes/data_service.php` to connect to your APIs:

```php
<?php
class DataService {
    private $apiBaseUrl;
    private $apiKey;
    private $apiSecret;
    
    public function __construct() {
        $this->apiBaseUrl = 'https://your-api.com/api/v1/';
        $this->apiKey = 'your-api-key';
        $this->apiSecret = 'your-api-secret';
    }
    
    public function getEnrollmentData($campus) {
        try {
            $response = $this->callAPI('enrollment', $campus);
            return $this->formatEnrollmentData($response);
        } catch (Exception $e) {
            error_log("Enrollment API Error: " . $e->getMessage());
            return $this->getFallbackEnrollmentData($campus);
        }
    }
    
    public function getCollectionData($campus) {
        try {
            $response = $this->callAPI('collection', $campus);
            return $this->formatCollectionData($response);
        } catch (Exception $e) {
            error_log("Collection API Error: " . $e->getMessage());
            return $this->getFallbackCollectionData($campus);
        }
    }
    
    public function getAccountsPayableData($campus) {
        try {
            $response = $this->callAPI('accounts-payable', $campus);
            return $this->formatPayableData($response);
        } catch (Exception $e) {
            error_log("Payables API Error: " . $e->getMessage());
            return $this->getFallbackPayableData($campus);
        }
    }
    
    private function callAPI($endpoint, $campus) {
        $url = $this->apiBaseUrl . $endpoint;
        
        $headers = [
            'Authorization: Bearer ' . $this->getAccessToken(),
            'Content-Type: application/json',
            'X-API-Key: ' . $this->apiKey
        ];
        
        $params = [
            'campus' => $campus,
            'year' => date('Y'),
            'format' => 'json'
        ];
        
        $url .= '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }
        
        if ($httpCode !== 200) {
            throw new Exception("API Error: HTTP $httpCode - $response");
        }
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON response: " . json_last_error_msg());
        }
        
        return $data;
    }
    
    private function getAccessToken() {
        // Implement your OAuth or token refresh logic here
        // This could be cached and refreshed as needed
        return $this->apiKey; // Simplified for example
    }
    
    private function formatEnrollmentData($apiData) {
        return [
            'current_year' => $apiData['current_enrollment'] ?? 0,
            'previous_year' => $apiData['previous_enrollment'] ?? 0,
            'per_campus' => $apiData['campus_breakdown'] ?? null,
            'per_college' => $apiData['college_breakdown'] ?? null,
            'per_sy' => $apiData['yearly_trend'] ?? null,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    private function formatCollectionData($apiData) {
        return [
            'current_year' => $apiData['current_collection'] ?? 0,
            'previous_year' => $apiData['previous_collection'] ?? 0,
            'per_campus' => $apiData['campus_breakdown'] ?? null,
            'monthly' => $apiData['monthly_breakdown'] ?? null,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    private function formatPayableData($apiData) {
        return [
            'current_year' => $apiData['current_payables'] ?? 0,
            'previous_year' => $apiData['previous_payables'] ?? 0,
            'per_campus' => $apiData['campus_breakdown'] ?? null,
            'by_category' => $apiData['category_breakdown'] ?? null,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    // Fallback methods for when API is unavailable
    private function getFallbackEnrollmentData($campus) {
        // Return cached data or default values
        return [
            'current_year' => 0,
            'previous_year' => 0,
            'per_campus' => null,
            'per_college' => null,
            'per_sy' => null,
            'last_updated' => date('Y-m-d H:i:s'),
            'error' => 'API unavailable - showing cached data'
        ];
    }
    
    // Add similar fallback methods for collection and payables
}
?>
```

### Step 2: Update API Endpoints

Modify `api/get_data.php` to use the data service:

```php
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once '../includes/data_service.php';

$campus = $_GET['campus'] ?? 'all_campuses';
$dataType = $_GET['type'] ?? 'all';

try {
    $dataService = new DataService();
    
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
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Data fetch failed',
        'message' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
```

## 🔐 Authentication Methods

### API Key Authentication
```php
private function callAPI($endpoint, $campus) {
    $headers = [
        'X-API-Key: ' . $this->apiKey,
        'Content-Type: application/json'
    ];
    // ... rest of implementation
}
```

### OAuth 2.0 Authentication
```php
private function getAccessToken() {
    $tokenData = $this->getCachedToken();
    
    if (!$tokenData || $this->isTokenExpired($tokenData)) {
        $tokenData = $this->refreshAccessToken();
        $this->cacheToken($tokenData);
    }
    
    return $tokenData['access_token'];
}

private function refreshAccessToken() {
    $data = [
        'grant_type' => 'client_credentials',
        'client_id' => $this->apiKey,
        'client_secret' => $this->apiSecret
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->apiBaseUrl . 'oauth/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}
```

### Basic Authentication
```php
private function callAPI($endpoint, $campus) {
    $headers = [
        'Authorization: Basic ' . base64_encode($this->apiKey . ':' . $this->apiSecret),
        'Content-Type: application/json'
    ];
    // ... rest of implementation
}
```

## 📊 Data Format Requirements

### Expected API Response Format

Your APIs should return data in this standardized format:

#### Enrollment API Response
```json
{
    "success": true,
    "data": {
        "current_enrollment": 204534,
        "previous_enrollment": 169916,
        "campus_breakdown": {
            "BIÑAN": 45000,
            "GMA CAVITE": 38000,
            "MANILA": 42000
        },
        "college_breakdown": {
            "CAS": 45,
            "CBA": 38,
            "CCS": 42
        },
        "yearly_trend": [350, 380, 420, 380, 405]
    },
    "timestamp": "2025-09-17T10:30:00Z"
}
```

#### Collection API Response
```json
{
    "success": true,
    "data": {
        "current_collection": 2456789000,
        "previous_collection": 2123456000,
        "campus_breakdown": {
            "BIÑAN": 450000000,
            "GMA CAVITE": 380000000
        },
        "monthly_breakdown": [3200000, 3800000, 4200000, 3900000, 4100000, 3800000, 3600000, 4000000, 4500000, 4200000, 3800000, 3500000]
    },
    "timestamp": "2025-09-17T10:30:00Z"
}
```

## 🚀 Performance Optimization

### Caching Implementation
```php
class DataService {
    private $cache;
    private $cacheTimeout = 300; // 5 minutes
    
    public function getEnrollmentData($campus) {
        $cacheKey = "enrollment_{$campus}";
        
        // Check cache first
        $cachedData = $this->getCachedData($cacheKey);
        if ($cachedData) {
            return $cachedData;
        }
        
        // Fetch from API
        $data = $this->fetchFromAPI('enrollment', $campus);
        
        // Cache the result
        $this->setCachedData($cacheKey, $data, $this->cacheTimeout);
        
        return $data;
    }
    
    private function getCachedData($key) {
        // Implement your caching mechanism (Redis, Memcached, file cache, etc.)
        return apcu_fetch($key);
    }
    
    private function setCachedData($key, $data, $timeout) {
        // Implement your caching mechanism
        apcu_store($key, $data, $timeout);
    }
}
```

### Batch API Calls
```php
public function getAllCampusData($campus) {
    // Make single API call for all data types
    $response = $this->callAPI('all-data', $campus);
    
    return [
        'enrollment' => $this->formatEnrollmentData($response['enrollment']),
        'collection' => $this->formatCollectionData($response['collection']),
        'accounts_payable' => $this->formatPayableData($response['accounts_payable'])
    ];
}
```

## 🔄 Real-Time Updates

### WebSocket Integration
```javascript
// Add to js/data_manager.js
class DataManager {
    constructor() {
        // ... existing code ...
        this.initWebSocket();
    }
    
    initWebSocket() {
        this.ws = new WebSocket('wss://your-websocket-server:8080');
        
        this.ws.onopen = () => {
            console.log('WebSocket connected');
        };
        
        this.ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.handleRealtimeUpdate(data);
        };
        
        this.ws.onclose = () => {
            console.log('WebSocket disconnected, attempting reconnect...');
            setTimeout(() => this.initWebSocket(), 5000);
        };
    }
    
    handleRealtimeUpdate(data) {
        if (data.type === 'enrollment_update') {
            this.updateEnrollmentChart(data.payload);
        } else if (data.type === 'collection_update') {
            this.updateCollectionChart(data.payload);
        }
        // ... handle other update types
    }
}
```

### Server-Sent Events (SSE)
```php
// Create api/stream.php for SSE
<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

while (true) {
    $data = $this->checkForUpdates();
    
    if ($data) {
        echo "data: " . json_encode($data) . "\n\n";
        ob_flush();
        flush();
    }
    
    sleep(5); // Check every 5 seconds
}
?>
```

## 🛡️ Error Handling

### Comprehensive Error Handling
```php
private function callAPI($endpoint, $campus) {
    $maxRetries = 3;
    $retryDelay = 1; // seconds
    
    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
        try {
            $response = $this->makeAPICall($endpoint, $campus);
            return $response;
        } catch (Exception $e) {
            if ($attempt === $maxRetries) {
                throw new Exception("API call failed after $maxRetries attempts: " . $e->getMessage());
            }
            
            error_log("API attempt $attempt failed: " . $e->getMessage());
            sleep($retryDelay * $attempt); // Exponential backoff
        }
    }
}
```

### Fallback Data Strategy
```php
public function getEnrollmentData($campus) {
    try {
        return $this->fetchFromAPI('enrollment', $campus);
    } catch (Exception $e) {
        // Try cached data
        $cached = $this->getCachedData("enrollment_{$campus}");
        if ($cached) {
            return $cached;
        }
        
        // Return default data
        return $this->getDefaultEnrollmentData($campus);
    }
}
```

## 📝 Testing Your Integration

### Test Script
```php
<?php
// test_api_integration.php
require_once 'includes/data_service.php';

$dataService = new DataService();

echo "Testing API Integration...\n\n";

// Test each campus
$campuses = ['all_campuses', 'binan', 'manila', 'gma_cavite'];

foreach ($campuses as $campus) {
    echo "Testing $campus:\n";
    
    try {
        $enrollment = $dataService->getEnrollmentData($campus);
        echo "  ✓ Enrollment: " . $enrollment['current_year'] . "\n";
        
        $collection = $dataService->getCollectionData($campus);
        echo "  ✓ Collection: " . number_format($collection['current_year']) . "\n";
        
        $payables = $dataService->getAccountsPayableData($campus);
        echo "  ✓ Payables: " . number_format($payables['current_year']) . "\n";
        
    } catch (Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}
?>
```

## 🔧 Configuration

### Environment Variables
Create a `.env` file for configuration:

```env
API_BASE_URL=https://your-api.com/api/v1/
API_KEY=your-api-key
API_SECRET=your-api-secret
CACHE_TIMEOUT=300
API_TIMEOUT=30
```

### Configuration Class
```php
class Config {
    public static function get($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

// Usage
$apiBaseUrl = Config::get('API_BASE_URL', 'https://default-api.com/');
```

This integration guide provides everything you need to connect your external APIs to the dashboard system. The modular design allows for easy customization based on your specific API requirements.
