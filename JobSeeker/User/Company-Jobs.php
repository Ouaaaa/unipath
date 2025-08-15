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


$jobs = [];

$jobs = [];

if ($companyID) {
    $jobQuery = "SELECT job_id, job_name, job_location, job_type FROM tbljobs WHERE company_id = ? AND status = 'approved'";
    if ($stmt3 = mysqli_prepare($link, $jobQuery)) {
        mysqli_stmt_bind_param($stmt3, "i", $companyID);
        mysqli_stmt_execute($stmt3);
        mysqli_stmt_bind_result($stmt3, $jobID, $jobName, $jobLocation, $jobType);

        while (mysqli_stmt_fetch($stmt3)) {
            $jobs[] = [
                'job_id' => $jobID,
                'job_name' => $jobName,
                'job_location' => $jobLocation,
                'job_type' => $jobType
            ];
        }
        mysqli_stmt_close($stmt3);
    }
}

if (isset($_POST['btnSubmit'])) {
    $jobID = $_POST['job_id'];
    $accountID = $_SESSION['account_id'];

    // Fetch the corresponding profile_id for the user
    $profileQuery = "SELECT profile_id FROM tblprofiles WHERE account_id = ?";
    $profileID = null;

    if ($stmt = mysqli_prepare($link, $profileQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $accountID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $profileID);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

    // Now insert into tblapplications
    $insertQuery = "INSERT INTO tblapplications (account_id, job_id, profile_id, datecreated) VALUES (?, ?, ?, ?)";
    $dateCreated = date("m/d/Y");

    if ($stmt = mysqli_prepare($link, $insertQuery)) {
        mysqli_stmt_bind_param($stmt, "iiis", $accountID, $jobID, $profileID, $dateCreated);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Application submitted successfully!');</script>";
        } else {
            echo "<script>alert('Failed to apply. Please try again.');</script>";
        }
        mysqli_stmt_close($stmt);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
         <title>Company Jobs </title>
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
        <link rel="stylesheet" href="../assets/css/company-jobs.css">
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
                                                <li><a href="CareerSearch-Page.php">Career Search</a></li>
                                                <li><a href="Profile.php">Profile </a></li>
                                                <li><a href="CareerAdvice.php">Advice</a></li>
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
                                            <a class="dropdown-item" href="Login.php">Log out</a>
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
                    <li><a href="Company-Jobs.php?company_id=<?php echo urlencode($companyID); ?>" class="active">Jobs</a></li>
                    </ul>
                </nav>

            <div class="jobs-section">
                <table class="jobs-table">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Apply</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($jobs)) : ?>
                        <?php foreach ($jobs as $job) : ?>
                            <tr>
                                <td><a href="Job-Apply.php?job_id=<?php echo urlencode($job['job_id']); ?>&company_id=<?php echo urlencode($companyID); ?>"><?php echo htmlspecialchars($job['job_name']); ?></a></td>
                                <td><?php echo htmlspecialchars($job['job_location']); ?></td>
                                <td><?php echo htmlspecialchars($job['job_type']); ?></td>
                                <td><a href="#" class="apply-btn" data-toggle="modal" data-target="#applyModal" data-jobid="<?php echo $job['job_id']; ?>">Apply</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4">No job postings available for this company.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>

    </div>
<!-- Apply Modal -->
<div class="modal fade" id="applyModal" tabindex="-1" role="dialog" aria-labelledby="applyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="POST" action="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="applyModalLabel">Apply for Job</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="job_id" id="modal-job-id">
          <p>Are you sure you want to apply for this job?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" name="btnSubmit" class="btn btn-primary">Yes, Apply</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- jQuery -->
<script src="vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- jQuery Easing (optional) -->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- SB Admin custom scripts -->
<script src="js/sb-admin-2.min.js"></script>

<script>
$(document).ready(function () {
    $('#applyModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var jobId = button.data('jobid');
        $('#modal-job-id').val(jobId); // Set the hidden input value
    });
});
</script>

        </body>
        </html>