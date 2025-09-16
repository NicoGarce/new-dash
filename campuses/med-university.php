<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allied Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php include '../sidebar/sidebar.php'; ?>
    <div class="main-content">
        <h1>Medical University Dashboard</h1>

        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'Enrollment')">Enrollment</button>
            <button class="tablinks" onclick="openTab(event, 'Collection')">Collection</button>
            <button class="tablinks" onclick="openTab(event, 'AccountsPayable')">Accounts Payable</button>
        </div>

        <div id="Enrollment" class="tabcontent">
            <h3>Enrollment</h3>
            <div class="chart-container">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>

        <div id="Collection" class="tabcontent">
            <h3>Collection</h3>
            <div class="chart-container">
                <canvas id="collectionChart"></canvas>
            </div>
        </div>

        <div id="AccountsPayable" class="tabcontent">
            <h3>Accounts Payable</h3>
            <div class="chart-container">
                <canvas id="accountsPayableChart"></canvas>
            </div>
        </div>

        <script>
            function openTab(evt, tabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            // Set default tab
            document.addEventListener("DOMContentLoaded", function() {
                openTab(event, 'Enrollment');
            });
        </script>
    </div>
</body>
</html>