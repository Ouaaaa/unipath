<?php
require_once "config.php";
session_start();
$accountID = $_SESSION['account_id'];
$sql = "SELECT profile_id FROM tblprofiles WHERE account_id = ?";
$profile_id = ''; // Initialize the variable to avoid undefined variable errors


$stmt = $link->prepare("SELECT * FROM tblprofiles WHERE profile_id = ?");
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();


if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind the parameter for account_id
    mysqli_stmt_bind_param($stmt, "i", $accountID);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Bind the result to the variable $firstname
    mysqli_stmt_bind_result($stmt, $profile_id);

    // Fetch the result
    mysqli_stmt_fetch($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);
}
function uploadResume($link, $profile_id)
{
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $resume_name = $_FILES['resume']['name'];
        $tempname = $_FILES['resume']['tmp_name'];
        $folder = 'Resume/' . $resume_name;

        // Make sure Resume directory exists
        if (!is_dir('Resume')) {
            mkdir('Resume', 0777, true);
        }

        // Update resume file name in database
        $stmt = $link->prepare("UPDATE tblprofiles SET resume = ? WHERE profile_id = ?");
        $stmt->bind_param("si", $resume_name, $profile_id);
        $query = $stmt->execute();
        $stmt->close();

        // Move uploaded file to the Resume folder
        if ($query && move_uploaded_file($tempname, $folder)) {
            echo "<h2>Resume uploaded successfully</h2>";
        } else {
            echo "<h2>Failed to upload resume</h2>";
        }
    }
}

// Store the result in session for reuse
$_SESSION['profile_id'] = $profile_id;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']))
{
    $last_name = $_POST['last-name'];
    $first_name = $_POST['first-name'];
    $birthdate = $_POST['birthdate'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['street'];
    $city = $_POST['city'];

    $stmt = $link->prepare("UPDATE tblprofiles SET lastname = ?, firstname = ?, birthdate = ?, phone_number = ?, email = ?, street = ?, city = ? WHERE profile_id = ?");
    $stmt->bind_param("sssssssi", $last_name, $first_name, $birthdate, $phone, $email, $address, $city, $profile_id);
    $stmt->execute();
    $stmt->close();

    $profile_id = $_SESSION['profile_id'];

    // Only update profile picture if a file was selected
    if (!empty($_FILES['profile']['name'])) {
        $profile_name = $_FILES['profile']['name'];
        $tempname = $_FILES['profile']['tmp_name'];
        $folder = 'Profile/'.$profile_name;

        $stmt = $link->prepare("UPDATE tblprofiles SET profile_pic = ? WHERE profile_id = ?");
        $stmt->bind_param("si", $profile_name, $profile_id);
        $query = $stmt->execute();

        if ($query && move_uploaded_file($tempname, $folder)) {
            echo "<script>alert('Profile updated successfully!'); window.location.href='Profile.php';</script>";
            exit();
        } else {
            echo "<h2>Profile picture upload failed</h2>";
        }
    }

    // Always handle resume upload
    uploadResume($link, $profile_id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Job Portal - Profile</title>
    <meta name="description" content="">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/logo.ico">
    
    <!-- CSS here -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/css/flaticon.css">
    <link rel="stylesheet" href="../assets/css/price_rangs.css">
    <link rel="stylesheet" href="../assets/css/slicknav.css">
    <link rel="stylesheet" href="../assets/css/animate.min.css">
    <link rel="stylesheet" href="../assets/css/magnific-popup.css">
    <link rel="stylesheet" href="../assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/css/slick.css">
    <link rel="stylesheet" href="../assets/css/nice-select.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="profile.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<header>
    <div class="header-area header-transparrent">
        <div class="headder-top header-sticky">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-2">
                        <div class="logo">
                            <a href="index.html"><img src="../assets/img/logo/logo1.jpg" width="100" height="70" alt="Logo"></a>
                        </div>  
                    </div>

                    <div class="col-lg-9 col-md-9">
                        <div class="menu-wrapper">
                            <div class="main-menu">
                                <nav class="d-none d-lg-block">
                                    <ul id="navigation">
                                        <li><a href="CareerSearch-Page.php">Career Search</a></li>
                                        <li class="active"><a href="Profile.php">Profile</a></li>
                                        <li><a href="CareerAdvice.php">Advice</a></li>
                                        <li><a href="CompanyPage.php">Companies</a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="header-btn d-none f-right d-lg-block">
                                <div class="dropdown">
                                    <button class="btn head-dropdown dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown">
                                    <?php echo htmlspecialchars($_SESSION['lastname']); ?>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="MasterPage.php">Log out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<main>
    <section class="profile-section">
    <form method="POST" enctype="multipart/form-data">
        <div class="profile-card">
        <div>
        <?php 
                $profile_id = $_SESSION['profile_id']; // again, should come from session

                $stmt = $link->prepare("SELECT * FROM tblprofiles WHERE profile_id = ?");
                $stmt->bind_param("i", $profile_id);
                $stmt->execute();
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
                
            ?>
            <img id="profile-pic" class="profile-pic" src="Profile/<?php echo $row['profile_pic']; ?>" width="100" /> 
        </div>
                <input class="choose-profile-pic"type="file" name="profile" id="profile-upload"/>
                <p>📄 Resume</p>
                <input class="choose-profile-pic" type="file" name="resume" id=""/>
                <?php if (!empty($row['resume'])): ?>
                    <p>
                        <a href="Resume/<?php echo htmlspecialchars($row['resume']); ?>" download>
                        <i class="fas fa-download" style="color: black; font-size: 20px; margin-top: 5px;"></i>
                        </a>
                    </p>
                <?php else: ?>
                    <p>No resume uploaded yet.</p>
                <?php endif; ?>


                <br /><br />
            <h2><?php echo htmlspecialchars($row['lastname']); ?> <?php echo htmlspecialchars($row['firstname']); ?></h2>
            <p><i class="fas fa-location-arrow"></i> <?php echo htmlspecialchars($row['street']); ?> , <?php echo htmlspecialchars($row['city']); ?></p>
            <p><i class="fas fa-birthday-cake"></i> <?php echo htmlspecialchars($row['birthdate']); ?></p>
            <p>📞 <?php echo htmlspecialchars($row['phone_number']); ?></p>
            <p>📧 <?php echo htmlspecialchars($row['email']); ?></p>
        </div>

        <div class="settings-form">
            <h2>Personal Settings</h2>
                <div class="input-profile">
                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($row['lastname']); ?>">
                
                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($row['firstname']); ?>">
                
                <label for="birthdate">Birthdate:</label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($row['birthdate']); ?>">
                
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($row['phone_number']); ?>">
                
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">

                <h3>Address</h3>
                <label for="street">Street Address:</label>
                <input type="text" id="street" name="street" value="<?php echo htmlspecialchars($row['street']); ?>">
                
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($row['city']); ?>">
                
                <br></br>
                </div>
                <button type="submit" name="submit"class="save-btn">Save Changes</button>    
        </div>
    </form>
    </section>
</main>

<script>
    document.getElementById("profile-upload").addEventListener("change", function(event) {
    const file = event.target.files[0];
    if (file) 
    {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("profile-pic").src = e.target.result;
        };
        reader.readAsDataURL(file);
        }
    });
</script>

</body>
</html>
