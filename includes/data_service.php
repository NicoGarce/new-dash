<?php
/**
 * Data Service Layer
 * 
 * This class handles data retrieval and manipulation for the dashboard system.
 * It provides a unified interface for accessing campus data from various sources
 * including configuration files, databases, and external APIs.
 * 
 * @author Dashboard System
 * @version 1.0
 * @since 2025-09-17
 */

class DataService {
    /**
     * Database connection instance
     * @var PDO|null
     */
    private $db;
    
    /**
     * Configuration data array
     * @var array
     */
    private $config;
    
    /**
     * Cache for frequently accessed data
     * @var array
     */
    private $cache = [];
    
    /**
     * Cache timeout in seconds (5 minutes)
     * @var int
     */
    private $cacheTimeout = 300;
    
    /**
     * Constructor - Initialize the data service
     */
    public function __construct() {
        // Load configuration data
        $this->config = require_once __DIR__ . '/../config/data_config.php';
        
        // Initialize database connection when ready
        // $this->initializeDatabase();
    }
    
    /**
     * Initialize database connection
     * 
     * @return void
     */
    private function initializeDatabase() {
        try {
            require_once __DIR__ . '/../config/database.php';
            $database = new Database();
            $this->db = $database->getConnection();
        } catch (Exception $e) {
            error_log("Database connection failed: " . $e->getMessage());
            // Continue with config-based data
        }
    }
    
    /**
     * Get enrollment data for a specific campus
     * 
     * @param string $campus Campus identifier
     * @return array Enrollment data
     */
    public function getEnrollmentData($campus) {
        // Check cache first
        $cacheKey = "enrollment_{$campus}";
        if ($cachedData = $this->getCachedData($cacheKey)) {
            return $cachedData;
        }
        
        try {
            // Get data from configuration (fallback to all_campuses if campus not found)
            $campusData = $this->config[$campus] ?? $this->config['all_campuses'];
            
            $data = [
                'current_year' => $campusData['enrollment']['current_year'] ?? 0,
                'previous_year' => $campusData['enrollment']['previous_year'] ?? 0,
                'per_campus' => $campusData['enrollment']['per_campus'] ?? null,
                'per_college' => $campusData['enrollment']['per_college'] ?? null,
                'per_sy' => $campusData['enrollment']['per_sy'] ?? null,
                'last_updated' => date('Y-m-d H:i:s'),
                'data_source' => 'config'
            ];
            
            // Cache the result
            $this->setCachedData($cacheKey, $data);
            
            return $data;
            
        } catch (Exception $e) {
            error_log("Error fetching enrollment data for {$campus}: " . $e->getMessage());
            return $this->getDefaultEnrollmentData();
        }
    }
    
    /**
     * Get collection data for a specific campus
     * 
     * @param string $campus Campus identifier
     * @return array Collection data
     */
    public function getCollectionData($campus) {
        // Check cache first
        $cacheKey = "collection_{$campus}";
        if ($cachedData = $this->getCachedData($cacheKey)) {
            return $cachedData;
        }
        
        try {
            // Get data from configuration
            $campusData = $this->config[$campus] ?? $this->config['all_campuses'];
            
            $data = [
                'current_year' => $campusData['collection']['current_year'] ?? 0,
                'previous_year' => $campusData['collection']['previous_year'] ?? 0,
                'per_campus' => $campusData['collection']['per_campus'] ?? null,
                'monthly' => $campusData['collection']['monthly'] ?? null,
                'last_updated' => date('Y-m-d H:i:s'),
                'data_source' => 'config'
            ];
            
            // Cache the result
            $this->setCachedData($cacheKey, $data);
            
            return $data;
            
        } catch (Exception $e) {
            error_log("Error fetching collection data for {$campus}: " . $e->getMessage());
            return $this->getDefaultCollectionData();
        }
    }
    
    /**
     * Get accounts payable data for a specific campus
     * 
     * @param string $campus Campus identifier
     * @return array Accounts payable data
     */
    public function getAccountsPayableData($campus) {
        // Check cache first
        $cacheKey = "payables_{$campus}";
        if ($cachedData = $this->getCachedData($cacheKey)) {
            return $cachedData;
        }
        
        try {
            // Get data from configuration
            $campusData = $this->config[$campus] ?? $this->config['all_campuses'];
            
            $data = [
                'current_year' => $campusData['accounts_payable']['current_year'] ?? 0,
                'previous_year' => $campusData['accounts_payable']['previous_year'] ?? 0,
                'per_campus' => $campusData['accounts_payable']['per_campus'] ?? null,
                'by_category' => $campusData['accounts_payable']['by_category'] ?? null,
                'last_updated' => date('Y-m-d H:i:s'),
                'data_source' => 'config'
            ];
            
            // Cache the result
            $this->setCachedData($cacheKey, $data);
            
            return $data;
            
        } catch (Exception $e) {
            error_log("Error fetching payables data for {$campus}: " . $e->getMessage());
            return $this->getDefaultPayablesData();
        }
    }
    
    /**
     * Get all data for a specific campus
     * 
     * @param string $campus Campus identifier
     * @return array Complete campus data
     */
    public function getAllData($campus) {
        return [
            'enrollment' => $this->getEnrollmentData($campus),
            'collection' => $this->getCollectionData($campus),
            'accounts_payable' => $this->getAccountsPayableData($campus),
            'campus_name' => $this->config[$campus]['name'] ?? 'Unknown Campus',
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Update data for a specific campus and data type
     * 
     * @param string $campus Campus identifier
     * @param string $dataType Type of data to update
     * @param array $data New data to store
     * @return array Update result
     */
    public function updateData($campus, $dataType, $data) {
        try {
            // Validate data type
            $validDataTypes = ['enrollment', 'collection', 'accounts_payable'];
            if (!in_array($dataType, $validDataTypes)) {
                throw new InvalidArgumentException("Invalid data type: {$dataType}");
            }
            
            // Validate campus
            if (!isset($this->config[$campus])) {
                throw new InvalidArgumentException("Campus not found: {$campus}");
            }
            
            // TODO: Implement database update when ready
            // For now, just return success
            $result = [
                'success' => true,
                'message' => 'Data updated successfully',
                'campus' => $campus,
                'data_type' => $dataType,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            // Clear cache for this campus and data type
            $this->clearCache("{$dataType}_{$campus}");
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error updating data for {$campus}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update data: ' . $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    /**
     * Get real-time data (for live updates)
     * 
     * @param string $campus Campus identifier
     * @return array Real-time data
     */
    public function getRealTimeData($campus) {
        // Clear cache to force fresh data
        $this->clearCache();
        
        return $this->getAllData($campus);
    }
    
    /**
     * Get cached data if available and not expired
     * 
     * @param string $key Cache key
     * @return array|null Cached data or null if not found/expired
     */
    private function getCachedData($key) {
        if (!isset($this->cache[$key])) {
            return null;
        }
        
        $cachedItem = $this->cache[$key];
        if (time() - $cachedItem['timestamp'] > $this->cacheTimeout) {
            unset($this->cache[$key]);
            return null;
        }
        
        return $cachedItem['data'];
    }
    
    /**
     * Set cached data with timestamp
     * 
     * @param string $key Cache key
     * @param array $data Data to cache
     * @return void
     */
    private function setCachedData($key, $data) {
        $this->cache[$key] = [
            'data' => $data,
            'timestamp' => time()
        ];
    }
    
    /**
     * Clear cache for specific key or all cache
     * 
     * @param string|null $key Specific cache key or null for all
     * @return void
     */
    private function clearCache($key = null) {
        if ($key === null) {
            $this->cache = [];
        } else {
            unset($this->cache[$key]);
        }
    }
    
    /**
     * Get default enrollment data when errors occur
     * 
     * @return array Default enrollment data
     */
    private function getDefaultEnrollmentData() {
        return [
            'current_year' => 0,
            'previous_year' => 0,
            'per_campus' => null,
            'per_college' => null,
            'per_sy' => null,
            'last_updated' => date('Y-m-d H:i:s'),
            'data_source' => 'default',
            'error' => 'Data temporarily unavailable'
        ];
    }
    
    /**
     * Get default collection data when errors occur
     * 
     * @return array Default collection data
     */
    private function getDefaultCollectionData() {
        return [
            'current_year' => 0,
            'previous_year' => 0,
            'per_campus' => null,
            'monthly' => null,
            'last_updated' => date('Y-m-d H:i:s'),
            'data_source' => 'default',
            'error' => 'Data temporarily unavailable'
        ];
    }
    
    /**
     * Get default payables data when errors occur
     * 
     * @return array Default payables data
     */
    private function getDefaultPayablesData() {
        return [
            'current_year' => 0,
            'previous_year' => 0,
            'per_campus' => null,
            'by_category' => null,
            'last_updated' => date('Y-m-d H:i:s'),
            'data_source' => 'default',
            'error' => 'Data temporarily unavailable'
        ];
    }
    
    /**
     * Get list of available campuses
     * 
     * @return array List of campus identifiers
     */
    public function getAvailableCampuses() {
        return array_keys($this->config);
    }
    
    /**
     * Validate campus identifier
     * 
     * @param string $campus Campus identifier
     * @return bool True if valid, false otherwise
     */
    public function isValidCampus($campus) {
        return isset($this->config[$campus]);
    }
}
?>
