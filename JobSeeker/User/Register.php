<?php
require_once "config.php";
session_start();

if (isset($_POST['btnSubmit'])) {
    // Check if the user already exists
    $sql = "SELECT * FROM tblaccounts WHERE username = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $_POST['txtUsername']);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 0) {
                // Insert new account into the table
                $sql = "INSERT INTO tblaccounts (username, password, datecreated) VALUES (?, ?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    $datecreated = date("m/d/Y");
                    mysqli_stmt_bind_param($stmt, "sss", $_POST['txtUsername'], $_POST['txtPassword'], $datecreated);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        // ✅ Store account ID in session
                        $account_id = mysqli_insert_id($link);
                        $_SESSION['account_id'] = $account_id;

                        // ✅ Redirect to step 2 without query string
                        header("Location: registerpage2.php");
                        exit();
                    } else {
                        echo "Error inserting account.";
                    }
                }
            } else {
                $errorMessage = "Username already in use.";
            }
        } else {
            echo "<font color='red'>Error on select statement.</font>";
        }
    }
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/MeinaCSS/registerpage.css">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/logo.ico">
    <title>Register - Unipath</title>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Sign Up</h2>
            <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
            <?php if (isset($errorMessage)) {
                echo "<font color = 'red'>$errorMessage</font><br><br>";
            } ?>
            <input type = "text" name = "txtUsername" placeholder = "Username" required><br>    
            <input type = "password" name = "txtPassword" id = "txtPassword" placeholder = "Password" required>
                <center><button type="submit" name = "btnSubmit">Register</button></center>
            </form>
            <div class="signin">
                <p>Already have an account? <a href="login.html">Sign In</a></p>
            </div>
        </div>
        <div class="platforms">
            <img src="../assets/img/logo/logo.png" alt="Unipath Logo" class="company-logo">
        </div>
    </div>
    <script>
    var notificationMessage = "<?php echo isset($notificationMessage) ? $notificationMessage : ''; ?>";
    if (notificationMessage !== "") {
        alert(notificationMessage);
        window.location.href = "registerpage2.php";
    }
    </script>

</body>
</html>