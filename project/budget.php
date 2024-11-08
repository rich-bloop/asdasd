<?php
// Include the database connection
include_once 'database.php'; // Ensure this file is included for DB connection
include_once 'dashboard.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION["user"]["ID"] ?? null; // Ensure user ID is stored in the session
    $budgetAmount = $_POST['totalBudget'] ?? 0; // Get the budget amount from the form input
    $currentDate = date("Y-m-d H:i:s");
    $durationDate = date("Y-m-d H:i:s", strtotime($currentDate . ' + 7 days'));

    // Debug to check values
    if (empty($userId) || empty($budgetAmount)) {
        echo "<script>alert('User ID or budget amount is missing');</script>";
        exit;
    }

    // Database insertion
    $sql = "INSERT INTO budget (User_Id, Budget, RDATE, Duration) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "<script>alert('SQL preparation failed: " . $conn->error . "');</script>";
        exit;
    }

    $stmt->bind_param("idss", $userId, $budgetAmount, $currentDate, $durationDate);

    if ($stmt->execute()) {
        echo "<script>alert('Budget added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget - Expense Manager</title>
    <link rel="stylesheet" href="css/budget.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
        function incrementAmount(amount) {
            const totalBudgetInput = document.getElementById('totalBudget');
            let currentAmount = parseInt(totalBudgetInput.value) || 0;
            totalBudgetInput.value = currentAmount + amount;
        }

        function clearAmount() {
            document.getElementById('totalBudget').value = '';
        }
    </script>   
</head>
<body>
    <div class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Balance</h2>
                <a class="x-icon" href="dashboard.php">
                    <span class="close">×</span>
                </a> 
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <h3>Enter Budget</h3>
                    <input id="totalBudget" name="totalBudget" placeholder="0" type="number" required/>
                    <div class="button-grid">
                        <button type="button" onclick="incrementAmount(100)">₱ 100</button>
                        <button type="button" onclick="incrementAmount(500)">₱ 500</button>
                        <button type="button" onclick="incrementAmount(1000)">₱ 1,000</button>
                        <button type="button" onclick="incrementAmount(5000)">₱ 5,000</button>
                        <button type="button" onclick="incrementAmount(10000)">₱ 10,000</button>
                        <button type="button" onclick="incrementAmount(20000)">₱ 20,000</button>
                    </div>
                    <div class="action-buttons">
                        <button type="button" onclick="clearAmount()">Clear</button>
                        <button type="submit">Done</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>