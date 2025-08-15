<?php
require_once "config.php";
include "session-checker.php";

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
$companyID = $_SESSION['company_id'] ?? null;  // Attempt to get company_id from session
if (!$companyID) {
    // Optionally, you can query it from the database if necessary
    $sql = "SELECT company_id FROM tblcompany WHERE account_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $accountID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $companyID);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

if (!$companyID) {
    $errorMessage = "Company ID not provided.";
}

$_SESSION['company_name'] = $companyName;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btnSubmit'])) {
    $jobName = $_POST['job_name'];
    $jobLocation = $_POST['job_location'];
    $minSalary = $_POST['min_salary'];
    $maxSalary = $_POST['max_salary'];
    $jobType = $_POST['job_type'];
    $jobAbout = $_POST['job_about'];

    if (!is_numeric($minSalary) || !is_numeric($maxSalary)) {
        $errorMessage = "Salary must be a number.";
    } elseif ($minSalary > $maxSalary) {
        $errorMessage = "Minimum salary cannot be greater than maximum salary.";
    }
    // Validate if company_id is available
    elseif ($companyID) {
        // Prepare SQL query to insert job data into tbljobs
        $sql = "INSERT INTO tbljobs (company_id, job_name, job_location, max_salary, min_salary, job_type, job_about, status, datecreated)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "issssss", $companyID, $jobName, $jobLocation, $maxSalary, $minSalary, $jobType, $jobAbout);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Job added successfully!'); window.location.href='Job-Management.php';</script>";
                exit();
            }
            
             else {
                $errorMessage = "Error: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errorMessage = "Error preparing the SQL statement: " . mysqli_error($link);
        }
    } else {
        $errorMessage = "Company ID not provided.";
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
                                            <li class="active"><a href="Job-Management.php">Job Management</a></li>
                                            <li><a href="#">Profile </a></li>
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
    <h2>Add Job</h2>
    <?php if (!empty($successMessage)): ?>
    <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
<?php endif; ?>

    <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">

    <label for="companyName">Job Name</label>
    <input type="text" id="companyName" name="job_name" placeholder="Enter Job name" required />

    <label for="location">Location</label>
    <input type="text" id="location" name="job_location" placeholder="Enter Location name" required />

    <label for="minSalary">Minimum Salary</label>
    <input type="text" id="minSalary" name="min_salary" placeholder="Enter Minimum salary" required />

    <label for="maxSalary">Maximum Salary</label>
    <input type="text" id="maxSalary" name="max_salary" placeholder="Enter Maximum salary" required />

      <div style="display: flex; gap: 50px; flex-direction: column;">
    <div style="flex: 1;">
        <label for="jobType">Job Type</label>
        <select id="jobType" name="job_type" class="job-add-select" required>
            <option value="" disabled selected>Select Job Type</option>
            <option value="Full-Time">Full-Time</option>
            <option value="Part-Time">Part-Time</option>
            <option value="Freelance">Freelance</option>
        </select>
    </div>
    <div style="flex: 1; display: flex; flex-direction: column; justify-content: flex-end;">
        <label for="jobDescription">Job Description</label>
        <textarea id="jobDescription" name="job_about" placeholder="Enter job description" class="job-add-select" required></textarea>
    </div>
</div>
    <button type="submit" name = "btnSubmit">Add Job</button>
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
</body>
</html>
