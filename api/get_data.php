<?php
/**
 * Data API Endpoint
 * Provides JSON data for dashboard updates
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../config/data_config.php';

// Get request parameters
$campus = $_GET['campus'] ?? 'all_campuses';
$dataType = $_GET['type'] ?? 'all';
$format = $_GET['format'] ?? 'json';

// Validate campus parameter
$validCampuses = array_keys($campusData);
if (!in_array($campus, $validCampuses)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid campus']);
    exit();
}

// Get campus data
if (!isset($campusData[$campus])) {
    http_response_code(400);
    echo json_encode(['error' => 'Campus not found', 'campus' => $campus]);
    exit();
}

$campusInfo = $campusData[$campus];

// Prepare response data
$response = [
    'success' => true,
    'timestamp' => date('Y-m-d H:i:s'),
    'campus' => $campus,
    'data' => []
];

// Return specific data type or all data
if ($dataType === 'all' || $dataType === 'enrollment') {
    $response['data']['enrollment'] = [
        'current_year' => $campusInfo['enrollment']['current_year'],
        'previous_year' => $campusInfo['enrollment']['previous_year'],
        'per_campus' => $campusInfo['enrollment']['per_campus'] ?? null,
        'per_college' => $campusInfo['enrollment']['per_college'] ?? null,
        'per_sy' => $campusInfo['enrollment']['per_sy'] ?? null
    ];
}

if ($dataType === 'all' || $dataType === 'collection') {
    $response['data']['collection'] = [
        'current_year' => $campusInfo['collection']['current_year'],
        'previous_year' => $campusInfo['collection']['previous_year'],
        'per_campus' => $campusInfo['collection']['per_campus'] ?? null,
        'monthly' => $campusInfo['collection']['monthly'] ?? null
    ];
}

if ($dataType === 'all' || $dataType === 'accounts_payable') {
    $response['data']['accounts_payable'] = [
        'current_year' => $campusInfo['accounts_payable']['current_year'],
        'previous_year' => $campusInfo['accounts_payable']['previous_year'],
        'per_campus' => $campusInfo['accounts_payable']['per_campus'] ?? null,
        'by_category' => $campusInfo['accounts_payable']['by_category'] ?? null
    ];
}

// Add metadata
$response['metadata'] = [
    'last_updated' => date('Y-m-d H:i:s'),
    'data_source' => 'config',
    'version' => '1.0'
];

// Return JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>
