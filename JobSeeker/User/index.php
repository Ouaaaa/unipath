<?php
if (isset($_POST['btnLogin'])) {
	// require the config file
	require_once "config.php";
	// build the template f or the login sql statement
	$sql = "SELECT * FROM tblaccounts WHERE username = ? AND password = ? AND status = 'ACTIVE'";
	// check if the sql statement will run on the link by preparing the statement
	if ($stmt = mysqli_prepare($link, $sql)) {
		// bind the data from the login form to the sql statement
		mysqli_stmt_bind_param($stmt, "ss", $_POST['txtUsername'], $_POST['txtPassword']);
		// check if the statement will execute
		if (mysqli_stmt_execute($stmt)) {
			// get the result of executing the statement
			$result = mysqli_stmt_get_result($stmt);			
			// check if there is/are row/rows in the result
			if (mysqli_num_rows($result) > 0) {
				// fetch the result into an array
				$account = mysqli_fetch_array($result, MYSQLI_ASSOC);
				// create session();
				session_start();
				$_SESSION['username'] = $_POST['txtUsername'];
				$_SESSION['usertype'] = $account['usertype'];
				// redirect to the accounts page
				header("location: MasterPage.html");
			}
			else {
				$loginMessage = "<font color = 'red'><br>Incorrect login details or account is disabled</font>";
			}
		}
	}
	else {
		echo "Error on the login statement";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Unipath</title>
    <link rel="stylesheet" href="example.css">
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="logo">
				<a href="masterpage.html"><img src="../assets/img/logo/logo1.jpg" width="120" height="120" alt=""></a>
			</div> 
        </div>
        <div class="right-panel">
            <h2>Welcome Back!</h2>
			<form  class="login-form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
            <!-- <form class="login-form"> -->  
                <input type="text" name = "txtUsername" placeholder = "Username" required>
                <input type="password" name = "txtPassword" placeholder = "Password" required>
                <div class="forgot-password">
                    <a href="#">Forgot password?</a>
                </div>
                <input type="submit" name="btnLogin" value="Log In" class="btn" required>
            <!--</form> -->
            <p class="signup-link">Don't have an account? <a href="#">Sign Up</a></p>
			</form>
		</div>
    </div>
</body>
</html>
