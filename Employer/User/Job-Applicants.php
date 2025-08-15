<?php
require_once "config.php";
include "session-checker.php";

$accountID = $_SESSION['account_id'];
$sql = "SELECT company_name FROM tblcompany WHERE account_id = ?";
$companyName = ''; // Initialize the variable to avoid undefined variable errors

if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind the parameter for account_id
    mysqli_stmt_bind_param($stmt, "i", $accountID);

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Bind the result to the variable $firstname
    mysqli_stmt_bind_result($stmt, $companyName);

    // Fetch the result
    mysqli_stmt_fetch($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Store the result in session for reuse
$_SESSION['company_name'] = $companyName;

$JobID = $_GET['job_id'] ?? null;  // e.g., CompanyPage.php?company_id=1
$JobName = '';
$JobLocation = '';
$Applicants = '0';

if ($JobID) {
    $query = "SELECT job_name, job_location FROM tbljobs WHERE job_id = ?";
    if ($stmt2 = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt2, "i", $JobID);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $JobName, $JobLocation);
        mysqli_stmt_fetch($stmt2);
        mysqli_stmt_close($stmt2);
    }
    $query = "SELECT COUNT(*) AS applicant_count FROM tblapplications WHERE job_id = ?";
    if ($stmt3 = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt3, "i", $JobID);
        mysqli_stmt_execute($stmt3);
        mysqli_stmt_bind_result($stmt3, $Applicants);
        mysqli_stmt_fetch($stmt3);
        mysqli_stmt_close($stmt3);
    }
    $applicantQuery = "SELECT p.profile_id, p.lastname, p.firstname, p.profile_pic, a.datecreated , a.job_id
                   FROM tblapplications a
                   INNER JOIN tblprofiles p ON a.profile_id = p.profile_id
                   WHERE a.job_id = ?";

    $applicants = [];

    if ($stmt4 = mysqli_prepare($link, $applicantQuery)) {
        mysqli_stmt_bind_param($stmt4, "i", $JobID);
        mysqli_stmt_execute($stmt4);
        mysqli_stmt_bind_result($stmt4, $profileID, $profileLastname, $profileFirstname, $profilePicture, $applicationDate, $job_id);

        
        while (mysqli_stmt_fetch($stmt4)) {
            $applicants[] = [
                'profile_id' => $profileID,
                'profile_lastname' => $profileLastname,
                'profile_firstname' => $profileFirstname,
                'profile_picture' => $profilePicture,
                'application_date' => $applicationDate,
                'job_id' => $job_id
            ];
            
        }

        mysqli_stmt_close($stmt4);
    }
}


?>
<html>
<head>
         <title>Applicants List</title>
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
    <form id="form1">
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
                                                <li class="active"><a href="Job-Management.php">Job Management</a></li>
                                                <li><a href="#">Profile </a></li>
                                                <li><a href="CareerAdvice.php">Advice</a></li>
                                            </ul>
                                        </nav>
                                    </div>          
                                    <!-- Header-btn -->
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


        <!-- Applicants List start here-->
        <div class="JA-job-header">
          <h3><?php echo htmlspecialchars($JobName); ?></h3>
            <p><?php echo htmlspecialchars($JobLocation); ?> • <span><?php echo $Applicants; ?> Applicants</span></p>
        </div>

        <?php if (count($applicants) > 0): ?>
            <?php foreach ($applicants as $applicant): ?>
                <div class="JA-applicant-card" style="margin-bottom: 16px;">
                    <?php
                        $profilePic = !empty($applicant['profile_picture']) 
                            ? "../../Jobseeker/User/Profile/" . htmlspecialchars($applicant['profile_picture']) 
                            : "../assets/img/default-logo.png";
                    ?>
                    <img src="<?php echo $profilePic; ?>" alt="Profile" />

                    <div class="JA-applicant-info">
                        <strong><?php echo htmlspecialchars($applicant['profile_lastname']); ?> <?php echo htmlspecialchars($applicant['profile_firstname']); ?></strong>
                        <p>Applied on: <?php echo htmlspecialchars($applicant['application_date']); ?></p>
                    </div>
                    <button class="JA-view-button"><a href="View-Application.php?profile_id=<?php echo urlencode($applicant['profile_id']); ?>&job_id=<?php echo urlencode($applicant['job_id']); ?>" class="JA-view-button">View Application</a></button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No applicants for this job yet.</p>
        <?php endif; ?>
        <!-- Applicants List End here--> 

        <!-- Applicants List end here--> 
         <!-- JS here -->
	<!-- All JS Custom Plugins Link Here here -->
		<!-- Jquery, Popper, Bootstrap -->
		<script src="../assets/js/vendor/jquery-1.12.4.min.js"></script>
        <script src="../assets/js/popper.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        </body>
        
</html>