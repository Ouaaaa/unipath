<?php
require_once "config.php";
session_start();

// ✅ Get account_id from session
$account_id = $_SESSION['account_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSubmit'])) {
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $phone = $_POST['phone_number'] ?? '';
    $usertype = $_POST['usertype'];

    if ($account_id) {

        // Check if `usertype` is valid
        if (!in_array($usertype, ['Jobseeker', 'Employer'])) {
            echo "Invalid user type.";
            exit();
        }

        // Insert usertype into tblaccounts
        $sql = "UPDATE tblaccounts SET usertype = ? WHERE account_id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $usertype, $account_id);
            if (mysqli_stmt_execute($stmt)) {

                // Insert user profile details into tblprofiles
                $sqlProfile = "INSERT INTO tblprofiles (account_id, firstname, lastname, phone_number) VALUES (?, ?, ?, ?)";
                if ($stmtProfile = mysqli_prepare($link, $sqlProfile)) {
                    mysqli_stmt_bind_param($stmtProfile, "isss", $account_id, $firstName, $lastName, $phone);
                    if (mysqli_stmt_execute($stmtProfile)) {
                        // ✅ Redirect to career page with account ID in URL
                        if ($usertype == 'Jobseeker') {
                            header("Location: CareerSearch-Page.php?account_id=$account_id");
                        } else {
                            header("Location: Login.php");
                        }
                        exit();
                    } else {
                        echo "Something went wrong with profile insertion: " . mysqli_stmt_error($stmtProfile);
                    }
                    mysqli_stmt_close($stmtProfile);
                } else {
                    echo "Failed to prepare profile SQL statement.";
                }

            } else {
                echo "Something went wrong with account update: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Failed to prepare the SQL statement for usertype.";
        }
    } else {
        echo "User account ID not found in session.";
    }
}
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Step 2</title>
    <link rel="stylesheet" href="../assets/css/MeinaCSS/registerpage2.css">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/logo.ico">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Almost done</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="input-group">
                    <input type="text" placeholder="First Name" name="first-name" required>
                    <input type="text" placeholder="Last Name" name="last-name" required>
                </div>

                <hr>
                <div class="info-box">
                    <p>
                        💡 New to the industry? No worries! You can always update your profile with your work experience later.
                    </p>
                </div>
                <div class="col-2 row-1">
                        <input type="text" placeholder="Phone Number" name="phone_number">
                        <select name="usertype" id="usertype" required>
                            <option value="" disabled selected>Select your usertype</option>
                            <option value="Jobseeker">Jobseeker</option>
                            <option value="Employer">Employer</option>
                        </select>
                </div>
                <center>
                    <button type="submit" name="btnSubmit">Register</button>
                </center>    
            </form>
        </div>
    </div>
</body>
</html>
