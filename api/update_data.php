<?php
/**
 * Data Update API Endpoint
 * 
 * This API handles real-time data updates for the dashboard system.
 * It provides a secure interface for updating campus data including
 * enrollment, collection, and accounts receivable information.
 * 
 * @author Dashboard System
 * @version 1.0
 * @since 2025-09-17
 */

// Set response headers for JSON API
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Max-Age: 86400'); // Cache preflight for 24 hours

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Start session for authentication
session_start();

// Check authentication
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Unauthorized',
        'message' => 'User must be logged in to update data',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit();
}

// Include required dependencies
require_once '../includes/data_service.php';

try {
    // Get request method and input data
    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate request method
    if (!in_array($method, ['POST', 'PUT'])) {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'error' => 'Method Not Allowed',
            'message' => 'Only POST and PUT methods are allowed',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }
    
    // Validate input data
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON',
            'message' => 'Request body must contain valid JSON',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }
    
    // Extract and validate required parameters
    $campus = $input['campus'] ?? null;
    $dataType = $input['data_type'] ?? null;
    $data = $input['data'] ?? null;
    
    // Validate required parameters
    if (!$campus || !$dataType || !$data) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Missing Required Parameters',
            'message' => 'campus, data_type, and data parameters are required',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }
    
    // Validate data type
    $validDataTypes = ['enrollment', 'collection', 'accounts_payable'];
    if (!in_array($dataType, $validDataTypes)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Invalid Data Type',
            'message' => 'data_type must be one of: ' . implode(', ', $validDataTypes),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit();
    }
    
    // Initialize data service and update data
    $dataService = new DataService();
    $result = $dataService->updateData($campus, $dataType, $data);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Data updated successfully',
        'data' => $result,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    // Log error for debugging
    error_log("Update API Error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal Server Error',
        'message' => 'An error occurred while updating data',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
