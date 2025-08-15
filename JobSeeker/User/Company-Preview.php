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
$companyID = $_GET['company_id'] ?? null;  // e.g., CompanyPage.php?company_id=1
$companyName = '';
$companyLogo = '';

if ($companyID) {
    $query = "SELECT company_name, company_logo FROM tblcompany WHERE company_id = ?";
    if ($stmt2 = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt2, "i", $companyID);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $companyName, $companyLogo);
        mysqli_stmt_fetch($stmt2);
        mysqli_stmt_close($stmt2);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
         <title>Explore Company </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="../assets/img/logo.ico">

        <!-- CSS here -->
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
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
                                                <li><a href="#">Career Search</a></li>
                                                <li><a href="Profile.php">Profile </a></li>
                                                <li><a href="#">Advice</a></li>
                                                <li class="active"><a href="CompanyPage.php">Companies</a></li>
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
        <div class="job-card">
            <div class="job-header">
                <?php if (!empty($companyLogo)) : ?>
                    <img src="../../Employer/User/logos/<?php echo htmlspecialchars($companyLogo); ?>" alt="<?php echo htmlspecialchars($companyName); ?> Logo" class="company-logo">
                <?php else : ?>
                    <img src="../assets/img/default-logo.png" alt="Default Logo" class="company-logo">
                <?php endif; ?>
            </div>

            <div class="company-title">
                <h2><i class="fas fa-building"></i> <?php echo htmlspecialchars($companyName); ?></h2>
            </div>
            <nav class="custom-nav">
                <ul>
                    <li><a href="Company-About.php?company_id=<?php echo urlencode($companyID); ?>">About</a></li>
                    <li><a href="Company-Jobs.php?company_id=<?php echo urlencode($companyID); ?>">Jobs</a></li>
                </ul>
            </nav>


                <div class="company-about">
                    <p><i class="fas fa-info-circle"></i>


                        Ready to become part of the dynamic <?php echo htmlspecialchars($companyName); ?> team? We're seeking candidates with the right skills and experience for this role.
                    </p>
                </div>
    </div>

        </body>
        </html>