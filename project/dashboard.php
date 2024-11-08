<?php
session_start();
// Redirect to login if user is not logged in
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Include database connection
include 'database.php';

// Fetch the budget for the logged-in user
$user_id = $_SESSION["user_id"]; // Assuming you have stored user ID in the session
$sql = "SELECT Budget FROM budget WHERE User_Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$budget = 0; // Default budget value
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $budget = $row['Budget']; // Get the budget from the result
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <title>Home Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <img alt="User  profile picture" height="80" src="https://scontent.fmnl17-2.fna.fbcdn.net/v/t39.30808-6/440240205_397554123122218_6202281443261005401_n.jpg?stp=cp6_dst-jpg&_nc_cat=107&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=FjX5CzRHnEwQ7kNvgGgmC86&_nc_zt=23&_nc_ht=scontent.fmnl17-2.fna&_nc_gid=AfSO3TYmOpIga8NS4LrWRRP&oh=00_AYCNF2oX_mfuz1JMu_8ZcDxKMFHkagY62e4k8MFlrOq_0Q&oe=6732D115" width="80"/>
            <h2>Roi Aldrich Santos</h2>
            <p>roialdrich@gmail.com</p>
            <ul>
                <li><a href="#">Home Page</a></li>
                <li><a href="#">Account</a></li>
                <li><a href="#">History</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1><i class="fas fa-home"></i> Home Page</h1>
                <div class="icons">
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-cog"></i>
                </div>
            </div>
            <div class="card-container">
                <div class="card balance">
                    <h3><i class="fas fa-wallet icon"></i> Balance</h3>
                    <p>₱ <?php echo number_format($budget, 2); ?></p> <!-- Display the fetched budget -->
                    <a class="plus-icon" href="budget.php">
                        <i class="fas fa-plus edit-icon"></i>
                    </a>
                </div>
                <div class="card expense">
                    <h3><i class="fas fa-money-bill-wave icon"></i> Total Expense</h3>
                    <p>₱ 999,999</p>
                </div>
            </div>
            <div class="card-container">
                <div class="card">
                    <h3><i class="fas fa-credit-card icon"></i> Total Money Lent</h3>
                    <p>₱ 999,999</p>
                    <i class="fas fa-edit edit-icon"></i>
                </div>
                <div class="card">
                    <h3><i class="fas fa-chart-line icon"></i> Total Money Loaned</h3>
                    <p>₱ 999,999</p>
                    <i class="fas fa-edit edit-icon"></i>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart">
                    <h3><i class="fas fa-chart-pie icon"></i> Daily Expense</h3>
                    <div class="pie-chart"></div>
                    <div class="legend">
                        <div><span class="food"></span> Food <span>₱ 99</span></div>
                        <div><span class="material"></span> Material <span>₱ 999</span></div>
                        <div><span class="entertainment"></span> Entertainment <span>₱ 9,999</span></div>
                        <div><span class="miscellaneous"></span> Miscellaneous <span>₱ 99,999</span></div>
                        <div><span class="transportation"></span> Transportation <span>₱ 999,999</span></div>
                    </div>
                </div>
                <div class="chart">
                    <h3><i class="fas fa-chart-bar icon"></i> Monthly Expense</h3>
                    <div class="bar-chart"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>