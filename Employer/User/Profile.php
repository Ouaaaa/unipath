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

    ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editing Company - Employer Site</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
<body class="bg-gray-100 font-roboto">
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
                                                    <li class="active"><a href="#">Job Management</a></li>
                                                    <li><a href="#">Profile </a></li>
                                                    <li><a href="CareerAdvice">Advice</a></li>
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

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg mx-auto">
            <h2 class="text-2xl font-bold mb-6 text-center">Company Profile</h2>
            <form>
                <div class="mb-4">
                    <img id="profile-pic" class="profile-pic" src="Profile/<?php echo $row['profile_pic']; ?>" width="100" /> 
                    <label for="jobTitle" class="block text-gray-700 font-bold mb-2">Add Logo</label>
                    <input class="choose-profile-pic"type="file" name="profile" id="profile-upload"/>
                </div>
                <div class="mb-4">
                    <label for="companyName" class="block text-gray-700 font-bold mb-2">Company Name</label>
                    <input type="text" id="companyName" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter company name">
                </div>
                <div class="mb-4">
                    <label for="jobDescription" class="block text-gray-700 font-bold mb-2">Job Description</label>
                    <textarea id="jobDescription" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="5" placeholder="Enter job description"></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-bold hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <script src="../assets/js/vendor/jquery-1.12.4.min.js"></script>
            <script src="../assets/js/popper.min.js"></script>
            <script src="../assets/js/bootstrap.min.js"></script>
</body>
</html>