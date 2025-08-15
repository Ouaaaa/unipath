<?php
require_once "config.php"; 


// Handle form submission and filtering jobs
if (isset($_POST['btnSubmit'])) {
    // Retrieve and sanitize form inputs
    $jobID = $_POST['txtJob'] ?? '';
    $location = $_POST['txtLocation'] ?? '';
    $workType = $_POST['cmbWorktype'] ?? '';

    // Base query
    $query = "
        SELECT j.*, c.company_name, c.company_logo 
        FROM tbljobs j
        JOIN tblcompany c ON j.company_id = c.company_id
        WHERE j.status = 'approved'"; // Only approved jobs

    $params = [];
    $types = '';

    if (!empty($jobID)) {
        $query .= " AND j.job_name LIKE ?";
        $params[] = "%$jobID%";
        $types .= 's';
    }
    if (!empty($location)) {
        $query .= " AND j.job_location LIKE ?";
        $params[] = "%$location%";
        $types .= 's';
    }
    if (!empty($workType)) {
        $query .= " AND j.job_type = ?"; // Correctly filtering by work type
        $params[] = $workType;
        $types .= 's';  // 's' for string type
    }


    // Prepare and execute the query
    if ($stmt = mysqli_prepare($link, $query)) {
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Fetch the results
        while ($row = mysqli_fetch_assoc($result)) {
            $jobs[] = $row; // Store jobs in the $jobs array
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
} else {
    // If the form is not submitted, load all approved jobs
    $query = "
        SELECT j.*, c.company_name, c.company_logo 
        FROM tbljobs j
        JOIN tblcompany c ON j.company_id = c.company_id
        WHERE j.status = 'approved'"; // Only approved jobs

    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Fetch the results
        while ($row = mysqli_fetch_assoc($result)) {
            $jobs[] = $row; // Store jobs in the $jobs array
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
}

// Close the database connection
mysqli_close($link);
?>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
         <title>Job Portal </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>
<body>
    <form id="form1" runat="server">
        <header>
            <!-- Header Start -->
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
                                    <!-- Main-menu -->
                                    <div class="main-menu">
                                        <nav class="d-none d-lg-block">
                                            <ul id="navigation">
                                                <li><a href="MasterPage.php">Career Search</a></li>
                                                <li><a href="Login.php">Profile </a></li>
                                                <li><a href="CareerAdvice.php">Advice</a></li>
                                                <li><a href="CompanyPage.php">Companies</a></li>
                                            </ul>
                                        </nav>
                                    </div>          
                                    <!-- Header-btn -->
                                    <div class="header-btn d-none f-right d-lg-block">
                                        <a href="Login.php" class="btn head-btn1">Sign In</a>
                                        <a href="Register.php" class="btn head-btn1">Register</a>
                                        <a href="../../Employer/User/MasterPage.php" class="btn head-btn1">Employer</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Mobile Menu -->
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>
               </div>   
           </div>
            <!-- Header End -->
        </header>
        
        </div>
    </form>
    <div class="wrapper">
        <div class="col-xl-8">

        <!-- Job search form -->
        <form id="searchForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="search-box">
                <div class="input-form">
                    <input type="text" name="txtJob" placeholder="Job Title" value="<?= htmlspecialchars($_POST['txtJob'] ?? '') ?>">
                </div>
                <div class="input-form">
                <input type="text" name="txtLocation" placeholder="Location" value="<?= htmlspecialchars($_POST['txtLocation'] ?? '') ?>">
                </div>
                <div class="select-form">
                <select name="cmbWorktype">
                    <option value="">Select Work Type</option>
                    <option value="Full-time" <?= isset($_POST['cmbWorktype']) && $_POST['cmbWorktype'] == 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                    <option value="Part-time" <?= isset($_POST['cmbWorktype']) && $_POST['cmbWorktype'] == 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                    <option value="Freelance" <?= isset($_POST['cmbWorktype']) && $_POST['cmbWorktype'] == 'Freelance' ? 'selected' : '' ?>>Freelance</option>
                </select>

                </div>
                <div class="search-form">
                        <input type="submit" name="btnSubmit" class="search-btn">
                </div>	
        </form>
        </div>
        </div>

        <!-- Display jobs -->
        <?php if (!empty($jobs)): ?>
            <div class="job-container">
                <?php foreach ($jobs as $row): ?>
                    <div class="job-box">
                        <article class="job-card">
                            <div class="job-items">
                                <div class="company-logo">
                                    <?php if (!empty($row['company_logo'])): ?>
                                        <img src='../../Employer/User/logos/<?= htmlspecialchars($row['company_logo']) ?>' alt='<?= htmlspecialchars($row['company_name']) ?> Logo'>
                                    <?php else: ?>
                                        <img src='../assets/img/default-logo.png' alt='Default Logo'>
                                    <?php endif; ?>
                                </div>
                                <div class="job-details">
                                    <h4 class="job-title">
                                        <a href="../../JobSeeker/User/Login.php?vacancyID=<?= htmlspecialchars($row['job_name']) ?>"><?= htmlspecialchars($row['job_name']) ?></a>
                                    </h4>
                                    <ul class="job-info">
                                        <li class="company-name"><?= htmlspecialchars($row['company_name']) ?></li>
                                        <li class="location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['job_location']) ?></li>
                                        <li class="salary">₱<?= htmlspecialchars($row['min_salary']) ?> - ₱<?= htmlspecialchars($row['max_salary']) ?></li>
                                    </ul>
                                </div>
                                <div class="items-link items-link2 f-right">
                                    <a href="../../JobSeeker/User/Login.php" class="job-type"><?= htmlspecialchars($row['job_type']) ?></a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No job listings found matching your criteria.</p>
        <?php endif; ?>
    </div>
    <div class="wrapper-login-home">
            <div class="login-box-home">
                <div class="login-form-home">
                    <a href="Login.php" class="btn head-btn1">Sign In</a>
                </div>
                <div class="search-form">
                    <a href="Register.php" class="btn head-btn1">Register</a>
                </div>
                <p>Sign In or create an account to manage your profile and apply for your desired job.</p>
            </div>
        </div>
        
      <!-- JS here -->
      <script>
function clearForm() {
    location.reload(); // Reloads the page
}
</script>
    <!-- Add your JS file if needed -->
    <!-- All JS Custom Plugins Link Here here -->
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
