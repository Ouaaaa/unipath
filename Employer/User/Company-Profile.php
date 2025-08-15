<?php
require_once "config.php";
include "session-checker.php";
$jobID = isset($_GET['job_id']) ? intval($_GET['job_id']) : null;
$jobData = [];

if ($jobID) {
    $sql = "SELECT * FROM tbljobs WHERE job_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $jobID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $jobData = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

// Retrieve account ID from the session
$accountID = $_SESSION['account_id'];

// Retrieve the company name from the database using account ID
$sql = "SELECT company_name FROM tblcompany WHERE account_id = ?";
$companyName = '';

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $accountID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $companyName);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Retrieve company_id from the query string if provided
// Always fetch fresh company data using current account ID
$sql = "SELECT company_id, company_name FROM tblcompany WHERE account_id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $accountID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $companyID, $companyName);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Always update session with the latest company ID and name
$_SESSION['company_id'] = $companyID;
$_SESSION['company_name'] = $companyName;

if (!$companyID) {
    $errorMessage = "Company ID not provided.";
}
$company_id = $_SESSION['company_id'] ?? null;
if ($company_id) {
    $stmt = $link->prepare("SELECT * FROM tblcompany WHERE company_id = ?");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
}


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btnSubmit'])) {
    $companyName = $_POST['company_name'];
    $companyAbout = $_POST['company_about'];

    $stmt = $link->prepare("UPDATE tblcompany SET company_name = ?, company_about = ? WHERE company_id = ?");
    $stmt->bind_param("ssi", $companyName, $companyAbout, $company_id);
    $stmt->execute();
    $stmt->close();


    // Only update profile picture if a file was selected
    if (!empty($_FILES['logo']['name'])) {
        $logo_name = $_FILES['logo']['name'];
        $tempname = $_FILES['logo']['tmp_name'];
        $folder = 'logos/'.$logo_name;

        $stmt = $link->prepare("UPDATE tblcompany SET company_logo = ? WHERE company_id = ?");
        $stmt->bind_param("si", $logo_name, $company_id);
        $query = $stmt->execute();

        if ($query && move_uploaded_file($tempname, $folder)) {
            echo "<script>alert('Company Profile updated successfully!'); window.location.href='CareerSearch-Page.php';</script>";
            exit();
        } else {
            echo "<h2>Company logo upload failed</h2>";
        }
    }
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Company Profile</title>
  <link rel="shortcut icon" type="image/x-icon" href="../assets/img/logo.ico">
    <link rel="stylesheet" href="../assets/css/add-job.css">
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

</head>
<body>
<header>
        <div class="header-area header-transparrent">
            <div class="headder-top header-sticky">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-2">
                            <!-- Logo -->
                            <div class="logo">
                                <a href="index.html"><img src="../assets/img/logo/logo1.jpg" width="100" height="70" alt=""></a>
                            </div>  
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div class="menu-wrapper">
                                <div class="main-menu">
                                    <nav class="d-none d-lg-block">
                                        <ul id="navigation">
                                            <li><a href="CareerSearch-Page.php">Career Search</a></li>
                                            <li><a href="Job-Management.php">Job Management</a></li>
                                            <li class="active"><a href="#">Profile </a></li>
                                            <li><a href="CareerAdvice.php">Advice</a></li>
                                        </ul>
                                    </nav>
                                </div>          
                                <div class="header-btn d-none f-right d-lg-block">
                                    <div class="dropdown">
                                        <button class="btn head-dropdown dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo htmlspecialchars($_SESSION['company_name']); ?>
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
  <div class="job-add-card">
    <h2>Company Profile</h2>
    <?php if (!empty($successMessage)): ?>
    <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
<?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

    <div>
        <?php 
                $company_id = $_SESSION['company_id']; // again, should come from session

                $stmt = $link->prepare("SELECT * FROM tblcompany WHERE company_id = ?");
                $stmt->bind_param("i", $company_id);
                $stmt->execute();
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
                
            ?>
            <img id="company_logo" class="profile-pic" src="logos/<?php echo $row['company_logo']; ?>" width="100" /> 
        </div>
    <label for="companyLogo">Edit Logo</label>
    <input type="file" name="logo" id="logo-upload"/>


    <label for="companyName">Company Name</label>
    <input type="text" id="companyName" name="company_name" placeholder="Enter Company name"  
    value="<?php echo htmlspecialchars($row['company_name']); ?>">
 
        <label for="companyAbout">Company Description</label>
        <textarea id="companyAbout" name="company_about" placeholder="Enter company description" class="job-add-select" ><?php echo htmlspecialchars($row['company_about']); ?></textarea>
        <button type="submit" name = "btnSubmit">Save Changes</button>
    </div>
    </form>
  </div>
  <script src="../assets/js/vendor/modernizr-3.5.0.min.js"></script>
		<!-- Jquery, Popper, Bootstrap -->
		<script src="../assets/js/vendor/jquery-1.12.4.min.js"></script>
        <script src="../assets/js/popper.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
	    <!-- Jquery Mobile Menu -->
        <script src="../assets/js/jquery.slicknav.min.js"></script>

		<!-- Jquery Slick , Owl-Carousel Plugins -->
        <script src="../assets/js/owl.carousel.min.js"></script>
        <script src="../assets/js/slick.min.js"></script>
        <script src="../assets/js/price_rangs.js"></script>
        
		<!-- One Page, Animated-HeadLin -->
        <script src="../assets/js/wow.min.js"></script>
		<script src="../assets/js/animated.headline.js"></script>
        <script src="../assets/js/jquery.magnific-popup.js"></script>

		<!-- Scrollup, nice-select, sticky -->
        <script src="../assets/js/jquery.scrollUp.min.js"></script>
        <script src="../assets/js/jquery.nice-select.min.js"></script>
		<script src="../assets/js/jquery.sticky.js"></script>
        
        <!-- contact js -->
        <script src="../assets/js/contact.js"></script>
        <script src="../assets/js/jquery.form.js"></script>
        <script src="../assets/js/jquery.validate.min.js"></script>
        <script src="../assets/js/mail-script.js"></script>
        <script src="../assets/js/jquery.ajaxchimp.min.js"></script>
        
		<!-- Jquery Plugins, main Jquery -->	
        <script src="../assets/js/plugins.js"></script>
        <script src="../assets/js/main.js"></script>
        <script>
document.getElementById("logo-upload").addEventListener("change", function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("company_logo").src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>
