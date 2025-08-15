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
$profileID = $_GET['profile_id'] ?? null;

$applicantData = [];

if ($profileID) {
    $query = "SELECT firstname, lastname, email, phone_number, resume, profile_pic FROM tblprofiles WHERE profile_id = ?";
    
    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $profileID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $firstname, $lastname, $email, $contact, $resume, $profilePic);
        
        if (mysqli_stmt_fetch($stmt)) {
            $applicantData = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'phone_number' => $contact,
                'resume' => $resume,
                'profile_pic' => $profilePic
            ];
        }
        mysqli_stmt_close($stmt);
    }
}
$jobID = $_GET['job_id'] ?? null;
$jobName = '';

if ($jobID) {
    $jobQuery = "SELECT job_name FROM tbljobs WHERE job_id = ?";
    if ($stmt = mysqli_prepare($link, $jobQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $jobID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $jobName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

?>
<html>
<head>
         <title>View Application</title>
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
                                                <li><a href="Profile.php">Profile </a></li>
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
                                            <a class="dropdown-item" href="#">Log out</a>
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
            <!-- View Application Start  -->
        <div class="profile-card">
            <div class="profile-left">
                    <?php
                        $profilePic = !empty($applicantData['profile_pic']) 
                            ? "../../Jobseeker/User/Profile/" . htmlspecialchars($applicantData['profile_pic']) 
                            : "../assets/img/default-logo.png";
                    ?>
                <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile" />
                <h3 style="color: white; font-size 20px"><?php echo htmlspecialchars($applicantData['lastname']); ?> <?php echo htmlspecialchars($applicantData['firstname']); ?></h3>
                <div class="contact-info">
                <p style="color: white; font-size 15px">📧 <?php echo htmlspecialchars($applicantData['email']); ?></p>
                <p style="color: white; font-size 15px">📞 <?php echo htmlspecialchars($applicantData['phone_number']); ?></p>
                </div>
            </div>

            <div class="profile-right">
                <h2><?php echo htmlspecialchars($jobName); ?></h2>
                <div class="resume-section">
                    <label>📄 Resume</label>
                    <input type="text" value="<?php echo htmlspecialchars($applicantData['resume']); ?>" readonly>
                    <?php if (!empty($applicantData['resume'])): ?>
                        <p>
                            <a href="../../Jobseeker/User/Resume/<?php echo htmlspecialchars($applicantData['resume']); ?>" download>
                            <i class="fas fa-download" style="color: black; font-size: 20px; margin-top: 5px;"></i>
                            </a>
                        </p>
                    <?php else: ?>
                        <p>No resume uploaded yet.</p>
                    <?php endif; ?>
                </div>

                <div class="additional-info">
                    <label>📝 Additional Information</label>
                    <textarea readonly>Calm during emergencies
                        High communication skills
                        etc...</textarea>
                </div>
            </div>
        </div>
            <!-- View Application End -->
         <!-- JS here -->
	<!-- All JS Custom Plugins Link Here here -->
		<!-- Jquery, Popper, Bootstrap -->
		<script src="../assets/js/vendor/jquery-1.12.4.min.js"></script>
        <script src="../assets/js/popper.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        </body>
        
</html>