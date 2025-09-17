<?php
/**
 * Data API Endpoint
 * 
 * This API provides JSON data for dashboard updates. It serves as the main
 * data source for the dashboard system, handling requests for enrollment,
 * collection, and accounts payable data across all campuses.
 * 
 * @author Dashboard System
 * @version 1.0
 * @since 2025-09-17
 */

// Set response headers for JSON API
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Max-Age: 86400'); // Cache preflight for 24 hours

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Start session for authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check authentication
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized',
        'message' => 'User must be logged in to access data',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit();
}

// Include required dependencies
require_once __DIR__ . '/../config/data_config.php';

try {
    // Get and validate request parameters
    $campus = $_GET['campus'] ?? 'all_campuses';
    $dataType = $_GET['type'] ?? 'all';
    $format = $_GET['format'] ?? 'json';
    
    // Validate campus parameter
    $validCampuses = array_keys($campusData);
    if (!in_array($campus, $validCampuses)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid Campus',
            'message' => 'Campus must be one of: ' . implode(', ', $validCampuses),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }
    
    // Validate data type parameter
    $validDataTypes = ['all', 'enrollment', 'collection', 'accounts_payable'];
    if (!in_array($dataType, $validDataTypes)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid Data Type',
            'message' => 'Type must be one of: ' . implode(', ', $validDataTypes),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }
    
    // Get campus data
    if (!isset($campusData[$campus])) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Campus Not Found',
            'message' => "Campus '{$campus}' not found in data source",
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }
    
    $campusInfo = $campusData[$campus];
    
    // Prepare response data structure
    $response = [
        'success' => true,
        'timestamp' => date('Y-m-d H:i:s'),
        'campus' => $campus,
        'campus_name' => $campusInfo['name'] ?? 'Unknown Campus',
        'data' => []
    ];
    
    // Build response data based on requested type
    if ($dataType === 'all' || $dataType === 'enrollment') {
        $response['data']['enrollment'] = [
            'current_year' => $campusInfo['enrollment']['current_year'] ?? 0,
            'previous_year' => $campusInfo['enrollment']['previous_year'] ?? 0,
            'per_campus' => $campusInfo['enrollment']['per_campus'] ?? null,
            'per_college' => $campusInfo['enrollment']['per_college'] ?? null,
            'per_sy' => $campusInfo['enrollment']['per_sy'] ?? null,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    if ($dataType === 'all' || $dataType === 'collection') {
        $response['data']['collection'] = [
            'current_year' => $campusInfo['collection']['current_year'] ?? 0,
            'previous_year' => $campusInfo['collection']['previous_year'] ?? 0,
            'per_campus' => $campusInfo['collection']['per_campus'] ?? null,
            'monthly' => $campusInfo['collection']['monthly'] ?? null,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    if ($dataType === 'all' || $dataType === 'accounts_payable') {
        $response['data']['accounts_payable'] = [
            'current_year' => $campusInfo['accounts_payable']['current_year'] ?? 0,
            'previous_year' => $campusInfo['accounts_payable']['previous_year'] ?? 0,
            'per_campus' => $campusInfo['accounts_payable']['per_campus'] ?? null,
            'by_category' => $campusInfo['accounts_payable']['by_category'] ?? null,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    // Add metadata
    $response['metadata'] = [
        'last_updated' => date('Y-m-d H:i:s'),
        'data_source' => 'config',
        'version' => '1.0',
        'total_campuses' => count($campusData),
        'available_campuses' => array_keys($campusData)
    ];
    
    // Return JSON response with pretty print for development
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Log error for debugging
    error_log("Get Data API Error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal Server Error',
        'message' => 'An error occurred while fetching data',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
