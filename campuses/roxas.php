<?php
/**
 * ROXAS Campus Dashboard
 * 
 * This page displays the dashboard for the ROXAS campus with enrollment,
 * collection, and accounts payable data. It uses the dashboard template
 * system for consistent layout and functionality.
 * 
 * @author Dashboard System
 * @version 1.0
 * @since 2025-09-17
 */

// Start session for authentication
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Include required files
require_once '../config/data_config.php';
require_once '../includes/dashboard_template.php';

// Generate dashboard HTML for ROXAS campus
echo generateDashboardHTML('roxas', $campusData['roxas']);
?>