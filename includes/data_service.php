<?php
/**
 * Data Service Layer
 * Handles data retrieval from various sources (database, APIs, etc.)
 */

class DataService {
    private $db;
    private $config;
    
    public function __construct() {
        $this->config = require_once __DIR__ . '/../config/data_config.php';
        // Initialize database connection here when ready
        // $this->db = new PDO(...);
    }
    
    /**
     * Get enrollment data for a campus
     */
    public function getEnrollmentData($campus) {
        // For now, return config data
        // Later, this will query the database
        $campusData = $this->config[$campus] ?? $this->config['all_campuses'];
        
        return [
            'current_year' => $campusData['enrollment']['current_year'],
            'previous_year' => $campusData['enrollment']['previous_year'],
            'per_campus' => $campusData['enrollment']['per_campus'] ?? null,
            'per_college' => $campusData['enrollment']['per_college'] ?? null,
            'per_sy' => $campusData['enrollment']['per_sy'] ?? null,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Get collection data for a campus
     */
    public function getCollectionData($campus) {
        $campusData = $this->config[$campus] ?? $this->config['all_campuses'];
        
        return [
            'current_year' => $campusData['collection']['current_year'],
            'previous_year' => $campusData['collection']['previous_year'],
            'per_campus' => $campusData['collection']['per_campus'] ?? null,
            'monthly' => $campusData['collection']['monthly'] ?? null,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Get accounts payable data for a campus
     */
    public function getAccountsPayableData($campus) {
        $campusData = $this->config[$campus] ?? $this->config['all_campuses'];
        
        return [
            'current_year' => $campusData['accounts_payable']['current_year'],
            'previous_year' => $campusData['accounts_payable']['previous_year'],
            'per_campus' => $campusData['accounts_payable']['per_campus'] ?? null,
            'by_category' => $campusData['accounts_payable']['by_category'] ?? null,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Get all data for a campus
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
     * Update data (for future database integration)
     */
    public function updateData($campus, $dataType, $data) {
        // This will be implemented when database is ready
        // For now, just return success
        return [
            'success' => true,
            'message' => 'Data updated successfully',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Get real-time data (for live updates)
     */
    public function getRealTimeData($campus) {
        // This can be enhanced to fetch from real-time sources
        return $this->getAllData($campus);
    }
}
?>
