<?php
/**
 * Dynamic Sidebar Component
 * 
 * This component provides a responsive sidebar navigation that automatically
 * detects the current page and highlights the active item. It adapts to
 * different screen sizes and provides appropriate navigation paths.
 * 
 * @author Dashboard System
 * @version 1.0
 * @since 2025-09-17
 */

// Get the current page name for navigation highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
$isDashboard = ($currentPage === 'dashboard.php');
$isCampus = (strpos($currentPage, '.php') !== false && 
             $currentPage !== 'dashboard.php' && 
             $currentPage !== 'index.php' && 
             $currentPage !== 'login.php' && 
             $currentPage !== 'logout.php');

// Determine the correct path for assets based on current location
$isInCampuses = (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/campuses/') !== false);
$assetPath = $isInCampuses ? '../' : '';

// Define campus navigation items
$campusItems = [
    'binan' => [
        'name' => 'BIÑAN',
        'icon' => 'fas fa-university',
        'tooltip' => 'BIÑAN CAMPUS'
    ],
    'gma_cavite' => [
        'name' => 'GMA CAVITE',
        'icon' => 'fas fa-university',
        'tooltip' => 'GMA CAVITE CAMPUS'
    ],
    'manila' => [
        'name' => 'MANILA',
        'icon' => 'fas fa-university',
        'tooltip' => 'MANILA CAMPUS'
    ],
    'pangasinan' => [
        'name' => 'PANGASINAN',
        'icon' => 'fas fa-university',
        'tooltip' => 'PANGASINAN CAMPUS'
    ],
    'isabela' => [
        'name' => 'ISABELA',
        'icon' => 'fas fa-university',
        'tooltip' => 'ISABELA CAMPUS'
    ],
    'roxas' => [
        'name' => 'ROXAS',
        'icon' => 'fas fa-university',
        'tooltip' => 'ROXAS CAMPUS'
    ],
    'med-university' => [
        'name' => 'MEDICAL UNIVERSITY',
        'icon' => 'fas fa-university',
        'tooltip' => 'MEDICAL UNIVERSITY CAMPUS'
    ]
];
?>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="<?php echo $assetPath; ?>img/SS.png" alt="JONELTA Logo" class="logo-img">
        </div>
        <button class="sidebar-close" id="sidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <h2>CAMPUSES</h2>
    <ul class="nav-list">
        <!-- Main Dashboard Link -->
        <li>
            <a href="<?php echo $assetPath; ?>dashboard.php" 
               class="<?php echo $isDashboard ? 'active' : ''; ?>" 
               data-tooltip="JONELTA DASHBOARD">
                <i class="fas fa-chart-bar"></i>
                <span>JONELTA</span>
            </a>
        </li>
        
        <!-- Campus Links - Generated from array -->
        <?php foreach ($campusItems as $campusKey => $campusInfo): ?>
        <li>
            <a href="<?php echo $assetPath; ?>campuses/<?php echo $campusKey; ?>.php" 
               class="<?php echo ($currentPage === $campusKey . '.php') ? 'active' : ''; ?>" 
               data-tooltip="<?php echo $campusInfo['tooltip']; ?>">
                <i class="<?php echo $campusInfo['icon']; ?>"></i>
                <span><?php echo $campusInfo['name']; ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
