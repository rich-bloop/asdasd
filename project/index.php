<?php
session_start();
// Redirect to homepage if user is already logged in
if (isset($_SESSION["user"])) {
    header("Location: dashboard.php");
    exit(); // Always exit after redirecting
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Include your CSS file for styling -->
</head>
<body>
    <div class="container">
        <h2>Login to Your Account</h2>
        <?php
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            
            require_once "database.php"; // Ensure you have this file and it's correctly set up

            // Use prepared statements to prevent SQL injection
            $sql = "SELECT * FROM user WHERE Email = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

                if ($user) {
                    // Verify the password
                    if (password_verify($password, $user["Password"])) {
                        $_SESSION["user"] = "yes"; // Set session variable for login status
                        $_SESSION["user_id"] = $user["ID"]; // Store user ID in session
                        header("Location: dashboard.php");
                        exit(); // Always exit after redirecting
                    } else {
                        echo "<p class='error-message'>Incorrect password</p>"; // Styled with CSS
                    }
                } else {
                    echo "<p class='error-message'>Email does not exist</p>"; // Styled with CSS
                }
            } else {
                echo "<p class='error-message'>Database query failed. Please try again later.</p>"; // Handle query failure
            }
            mysqli_stmt_close($stmt); // Close the statement
        }
        ?>
        <form action="index.php" method="post"> <!-- Form action points to the same file -->
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" required>
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login">
            </div>
        </form>
        <div>
            <p>Not registered yet? <a href="registration.php">Register here</a></p>
        </div>
    </div>
</body>
</html>