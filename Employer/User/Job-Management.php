<?php
require_once "config.php";
include "session-checker.php";

$accountID = $_SESSION['account_id'];
$companyName = ''; // Initialize the variable to avoid undefined variable errors

// Fetch company name on page load
$sql = "SELECT company_name FROM tblcompany WHERE account_id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $accountID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $companyName);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Store the company name in session for reuse
$_SESSION['company_name'] = $companyName;

// Function to fetch jobs
function fetchJobs($link, $accountID, $jobID = '', $location = '') {
    $query = "
        SELECT j.job_id, j.job_name, j.job_location 
        FROM tbljobs j
        INNER JOIN tblcompany c ON j.company_id = c.company_id
        WHERE c.account_id = ? AND j.status = 'Approved'
    ";

    $params = [$accountID];
    $types = 'i';

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

    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "<div class='JL-container'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='JL-job-card'>";
                echo "<h4>" . htmlspecialchars($row['job_name']) . "</h4>";
                echo "<p class='JL-location'>" . htmlspecialchars($row['job_location']) . "</p>";
                echo '<button class="JL-applicants">';

                $job_id = $row['job_id'];
                $jobCount = 0;

                $jobCountQuery = "SELECT COUNT(*) AS applicants_count FROM tblapplications WHERE job_id = ?";
                if ($jobStmt = mysqli_prepare($link, $jobCountQuery)) {
                    mysqli_stmt_bind_param($jobStmt, 'i', $job_id);
                    mysqli_stmt_execute($jobStmt);
                    mysqli_stmt_bind_result($jobStmt, $jobCountResult);
                    if (mysqli_stmt_fetch($jobStmt)) {
                        $jobCount = $jobCountResult;
                    }
                    mysqli_stmt_close($jobStmt);
                }

                echo '<a href="Job-Applicants.php?job_id=' . urlencode($job_id) . '">' . $jobCount . ' Applicants</a>';
                echo '</button>';
                echo '<button class="JL-Edit">';
                echo '<a href="Update-Job.php?job_id=' . urlencode($job_id) . '"> Edit Job</a>';
                echo '</button>';
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No job listings found for your company matching the criteria.</p>";
        }
        mysqli_stmt_close($stmt);
    }
}
$companyID = '';

// Fetch company_id as well
$sqlCompanyID = "SELECT company_id FROM tblcompany WHERE account_id = ?";
if ($stmtID = mysqli_prepare($link, $sqlCompanyID)) {
    mysqli_stmt_bind_param($stmtID, "i", $accountID);
    mysqli_stmt_execute($stmtID);
    mysqli_stmt_bind_result($stmtID, $companyID);
    mysqli_stmt_fetch($stmtID);
    mysqli_stmt_close($stmtID);
}

?>
<html>
<head>
    <title>Job Management</title>
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
                                            <li><a href="Company-Profile.php">Profile </a></li>
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

    <div class="wrapper">
        <div class="col-xl-8">
            <!-- form -->
            <form id="searchForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="search-box">
                <div class="input-form">
                    <input type="text" placeholder="Job Title or Keyword" name="txtJob">
                </div>
                <div class="input-form">
                    <input type="text" placeholder="Enter Region or City" name="txtLocation">
                </div>
                <div class="search-form">
                    <button type="submit" name="btnSubmit" class="search-btn">PATH</button>
                </div>
            </form>    
        </div>
    </div>

    <!-- Adding and Updating Start here -->
    <button class="btn-add"><a href="Add-Job.php?company_id=<?php echo urlencode($companyID); ?>"> Add Job</a></button>
    <!-- Adding and Updating End -->

<?php
    // Handle the search functionality if the form is submitted
    if (isset($_POST['btnSubmit'])) {
        $jobID = $_POST['txtJob'] ?? '';
        $location = $_POST['txtLocation'] ?? '';
        fetchJobs($link, $accountID, $jobID, $location);
    } else {
        // Automatically load jobs when the page loads (without search)
        fetchJobs($link, $accountID);
    }

    // Close the database connection
    mysqli_close($link);
?>
    
    <!-- JS here -->
    <script src="../assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

</body>
</html>
