<?php
require_once "config.php"; // Ensure database connection
include "session-checker.php";
$accountID = $_SESSION['account_id'];
$sql = "SELECT firstname FROM tblprofiles WHERE account_id = ?";
$firstname = ''; // Initialize the variable to avoid undefined variable errors

if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind the parameter for account_id
    mysqli_stmt_bind_param($stmt, "i", $accountID);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Bind the result to the variable $firstname
    mysqli_stmt_bind_result($stmt, $firstname);

    // Fetch the result
    mysqli_stmt_fetch($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Store the result in session for reuse
$_SESSION['firstname'] = $firstname;

?>


<html lang="en">
<head>
         <title>Explore Company </title>
         <link rel="shortcut icon" type="image/x-icon" href="../assets/img/logo.ico">

        <!-- CSS here -->
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        
</head>
<body>
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
                                                <li><a href="CareerSearch-Page.php">Career Search</a></li>
                                                <li><a href="Profile.php">Profile </a></li>
                                                <li><a href="CareerAdvice.php">Advice</a></li>
                                                <li class="active"><a href="#">Companies</a></li>
                                            </ul>
                                        </nav>
                                    </div>          
                                    <!-- Header-btn -->
                                    <div class="header-btn d-none f-right d-lg-block">
                                    <div class="dropdown">
                                        <button class="btn head-dropdown dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo htmlspecialchars($_SESSION['firstname']); ?>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                                            <a class="dropdown-item" href="#">Log out</a>
                                        </div>
                                    </div>
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
            <!-- Search Box -->
            <div class="wrapper-company-search">
                <div class="search-container">
                    <h2>Find the right company for you</h2>
                    <form id="searchForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="search-box">
                        <div class="input-wrapper">
                            <div class="input-form">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search" name="txtCompany">
                            </div>
                            <button name="btnSubmit" class="path-btn">Path</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="company-section">
                <h2>Explore Companies</h2>
                <p>Discover information about new job openings, company reviews, workplace culture, and available perks and benefits.</p>
            </div>
<?php if (isset($_POST['btnSubmit'])) {
    $Company_id = $_POST['txtCompany'] ??'';
    
    $query = "SELECT company_name, company_logo ,company_id FROM tblcompany WHERE 1=1";


    $params = [];
    $types = '';

    if (!empty($Company_id)) {
        $query .= " AND company_name LIKE ?";
        $params[] = "%$Company_id%";
        $types .= 's';
    }

    if ($stmt = mysqli_prepare($link, $query)) {
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            echo"<div class='company-container'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo"<div class='company-card'>";
                if (!empty($row['company_logo'])) {
                    echo "              <img src='../../Employer/User/logos/" . htmlspecialchars($row['company_logo']) . "' alt='" . htmlspecialchars($row['company_name']) . " Logo'>";
                } else {
                    echo "              <img src='../assets/img/default-logo.png' alt='Default Logo'>";
                }
                echo "<h3>" . htmlspecialchars($row['company_name']) . "</h3>";
                echo '<button class="job-btn">';

                $company_id = $row['company_id']; // Get the current company_id

                // Query to count jobs for the specific company
                $jobCountQuery = "SELECT COUNT(*) AS job_count FROM tbljobs WHERE company_id = ?";
                if ($jobStmt = mysqli_prepare($link, $jobCountQuery)) {
                    mysqli_stmt_bind_param($jobStmt, 'i', $company_id);
                    mysqli_stmt_execute($jobStmt);
                    mysqli_stmt_bind_result($jobStmt, $jobCount);
                    mysqli_stmt_fetch($jobStmt);
                    mysqli_stmt_close($jobStmt);
                    
                    // If no jobs are found, set jobCount to 0
                    $jobCount = $jobCount ?: 0;
                }

                // Echo the number of jobs
                echo '<a href="Company-Preview.php?company_id=' . urlencode($company_id) . '">' . $jobCount . ' Jobs</a>';

                echo '</button>';
                echo"</div>";
            }
            echo"</div>";
        }else {
            echo "<p>No job company found matching your criteria.</p>";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}
?>
     <!-- JS here -->
	
    <!-- All JS Custom Plugins Link Here here -->
    <script src="../assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <!-- Jquery, Popper, Bootstrap -->
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