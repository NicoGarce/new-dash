<?php
/**
 * Data Update API
 * Handles real-time data updates
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once '../includes/data_service.php';

$input = json_decode(file_get_contents('php://input'), true);
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $campus = $input['campus'] ?? null;
    $dataType = $input['data_type'] ?? null;
    $data = $input['data'] ?? null;
    
    if (!$campus || !$dataType || !$data) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required parameters']);
        exit();
    }
    
    $dataService = new DataService();
    $result = $dataService->updateData($campus, $dataType, $data);
    
    echo json_encode($result);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>
