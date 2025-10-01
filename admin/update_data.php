<?php
/**
 * Data Update Interface
 * Simple interface for updating dashboard data
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../config/data_config.php';

if ($_POST) {
    $campus = $_POST['campus'];
    $dataType = $_POST['data_type'];
    $currentYear = $_POST['current_year'];
    $previousYear = $_POST['previous_year'];
    
    // Update the data (in a real system, this would update the database)
    if (isset($campusData[$campus])) {
        $campusData[$campus][$dataType]['current_year'] = (int)$currentYear;
        $campusData[$campus][$dataType]['previous_year'] = (int)$previousYear;
        
        // Save to file (in a real system, this would save to database)
        $configContent = "<?php\nreturn " . var_export($campusData, true) . ";\n?>";
        file_put_contents('../config/data_config.php', $configContent);
        
        $success = "Data updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #204ca4;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 1rem;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #204ca4;
        }
        .btn {
            background: #204ca4;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
        }
        .btn:hover {
            background: #1a3d8a;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Update Dashboard Data</h1>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="campus">Campus:</label>
                <select name="campus" id="campus" required>
                    <option value="">Select Campus</option>
                    <?php foreach ($campusData as $key => $campus): ?>
                        <option value="<?php echo $key; ?>"><?php echo $campus['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="data_type">Data Type:</label>
                <select name="data_type" id="data_type" required>
                    <option value="">Select Data Type</option>
                    <option value="enrollment">Enrollment</option>
                    <option value="collection">Collection</option>
                    <option value="accounts_payable">Accounts Receivable</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="current_year">Current Year Value:</label>
                <input type="number" name="current_year" id="current_year" required>
            </div>
            
            <div class="form-group">
                <label for="previous_year">Previous Year Value:</label>
                <input type="number" name="previous_year" id="previous_year" required>
            </div>
            
            <button type="submit" class="btn">Update Data</button>
        </form>
        
        <div style="margin-top: 40px;">
            <h3>Current Data Preview</h3>
            <div id="data-preview"></div>
        </div>
    </div>

    <script>
        // Update preview when campus or data type changes
        document.getElementById('campus').addEventListener('change', updatePreview);
        document.getElementById('data_type').addEventListener('change', updatePreview);
        
        function updatePreview() {
            const campus = document.getElementById('campus').value;
            const dataType = document.getElementById('data_type').value;
            
            if (campus && dataType) {
                // Fetch current data
                fetch(`../api/get_data.php?campus=${campus}&type=${dataType}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const preview = document.getElementById('data-preview');
                            preview.innerHTML = `
                                <p><strong>Current Year:</strong> ${data.data[dataType].current_year.toLocaleString()}</p>
                                <p><strong>Previous Year:</strong> ${data.data[dataType].previous_year.toLocaleString()}</p>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        }
    </script>
</body>
</html>
