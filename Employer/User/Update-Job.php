<?php
require_once "config.php";
include "session-checker.php";
$jobID = isset($_POST['job_id']) ? intval($_POST['job_id']) : (isset($_GET['job_id']) ? intval($_GET['job_id']) : null);

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
        if ($jobID) {
            // UPDATE existing job
            $sql = "UPDATE tbljobs 
                    SET job_name = ?, job_location = ?, min_salary = ?, max_salary = ?, job_type = ?, job_about = ? 
                    WHERE job_id = ? ";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssddssi", $jobName, $jobLocation, $minSalary, $maxSalary, $jobType, $jobAbout, $jobID);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Job updated successfully!'); window.location.href='Job-Management.php';</script>";
                    exit();
                } else {
                    $errorMessage = "Error: " . mysqli_error($link);
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            // INSERT new job
            $sql = "INSERT INTO tbljobs (company_id, job_name, job_location, max_salary, min_salary, job_type, job_about, status, datecreated)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "issssss", $companyID, $jobName, $jobLocation, $maxSalary, $minSalary, $jobType, $jobAbout);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Job added successfully!'); window.location.href='Job-Management.php';</script>";
                    exit();
                } else {
                    $errorMessage = "Error: " . mysqli_error($link);
                }
                mysqli_stmt_close($stmt);
            }
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
  <title>Update Job</title>
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
    <h2>Update Job</h2>
    <?php if (!empty($successMessage)): ?>
    <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
<?php endif; ?>

    <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
    <label for="companyName">Job Name</label>
    <input type="text" id="companyName" name="job_name" placeholder="Enter Job name" required 
    value="<?php echo htmlspecialchars($jobData['job_name'] ?? ''); ?>" />
    <label for="location">Location</label>
    <input type="text" id="location" name="job_location" placeholder="Enter Location name" required 
    value="<?php echo htmlspecialchars($jobData['job_location'] ?? ''); ?>" />
    <label for="minSalary">Minimum Salary</label>
    <input type="text" id="minSalary" name="min_salary" placeholder="Enter Minimum salary" required 
    value="<?php echo htmlspecialchars($jobData['min_salary'] ?? ''); ?>" />
    <label for="maxSalary">Maximum Salary</label>
    <input type="text" id="maxSalary" name="max_salary" placeholder="Enter Maximum salary" required 
    value="<?php echo htmlspecialchars($jobData['max_salary'] ?? ''); ?>" />


      <div style="display: flex; gap: 50px; flex-direction: column;">
    <div style="flex: 1;">
        <label for="jobType">Job Type</label>
        <select id="jobType" name="job_type" class="job-add-select" required>
            <option value="" disabled>Select Job Type</option>
            <option value="Full-Time" <?php if (($jobData['job_type'] ?? '') == 'Full-Time') echo 'selected'; ?>>Full-Time</option>
            <option value="Part-Time" <?php if (($jobData['job_type'] ?? '') == 'Part-Time') echo 'selected'; ?>>Part-Time</option>
            <option value="Freelance" <?php if (($jobData['job_type'] ?? '') == 'Freelance') echo 'selected'; ?>>Freelance</option>
        </select>

    </div>
    <div style="flex: 1; display: flex; flex-direction: column; justify-content: flex-end;">
        <label for="jobDescription">Job Description</label>
        <textarea id="jobDescription" name="job_about" placeholder="Enter job description" class="job-add-select" required><?php echo htmlspecialchars($jobData['job_about'] ?? ''); ?></textarea>

    </div>
</div>
<?php if ($jobID): ?>
  <input type="hidden" name="job_id" value="<?php echo $jobID; ?>">
<?php endif; ?>

    <button type="submit" name = "btnSubmit">Update Job</button>
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
