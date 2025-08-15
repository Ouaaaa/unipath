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
    <title>Career Advice</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/logo.ico">
    <link rel="stylesheet" href="../assets/css/MeinaCSS/careerAdvice.css">
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
    <!-- Top Navigation Bar -->
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
                                                    <li><a href="Job-Management.php">Job Management</a></li>
                                                    <li><a href="Company-Profile.php">Profile </a></li>
                                                    <li class="active"><a href="CareerAdvice.php">Advice</a></li>
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

    <!-- Career Advice Section -->
    <main>
        <div class="advice-container">
            <section class="articles">
                <article class="article-card">
                    <div class="article-image">
                        <img src="../assets/img/advice/interview.png">
                    </div>
                    <div class="article-content">
                        <h3 style="color:white;">Hiring 101: A Quick & Dirty Guide for First-Time Hiring Managers</h3>
                        <p style="color:white;">Hiring the right people is crucial for the success of your business and that's why entrepreneurs should have a formal hiring process in place when looking for new staff.

						<br><br>By putting time and work into finding the right people, you will improve your chances of hiring the best performers and avoiding costly and painful mistakes.

						<br><br>Many entrepreneurs have good instincts about whether someone is right or not for the job. But you shouldn't rely just on gut feelings. The recruitment decision should be founded on solid, objective factors.</p>
                        <br><a href="https://www.bdc.ca/en/articles-tools/employees/recruit/7-steps-recruiting-right-people" target="_blank">Find out more</a>
                    </div>
                </article><br><br>
                
                <article class="article-card">
                    <div class="article-image">
                        <img src="../assets/img/advice/hiring-managers.png">
                    </div>
                    <div class="article-content">
                        <h3 style="color:white;">How to structure effective job interviews: A guide for hiring managers</h3>
                        <p style="color:white;">As a hiring manager, knowing how to structure effective job interviews is a critical skill that significantly impacts the future of your organisation. A well-structured interview process helps you identify the most suitable job applicants, and ensures that you make informed hiring decisions that align with your company's goals and values.

						<br><br>Whether it is your first time hiring or if you are a seasoned recruiter, this guide will explore the critical elements of structuring an effective job interview and provide practical tips and strategies to elevate your interviewing skills and attract top talent to your organisation.</p>
                        <br><a href="https://www.coursera.org/articles/how-to-get-a-job-with-no-experience" target="_blank" >Find out more</a>
                    </div>
                </article>
				
				<article class="article-card">
                    <div class="article-image">
                        <img src="../assets/img/advice/hiring.png" alt="How to write a cv?">
                    </div>
                    <div class="article-content">
                        <h3 style="color:white;">7 Interview Best Practices for Hiring Managers</h3><br><br>
                        <p style="color:white;">Interviewing potential candidates can be a stressful and intimidating experience. Not only does it require interacting with and assessing strangers, but it’s a huge responsibility. The wrong candidate can waste time, money, and energy you don’t have to spare.

						<br><br>In order to make sure that you’re getting the most out of the hiring process, it’s important to adhere to these seven interview best practices.</p>
                        <br><a href="https://raiserecruiting.com/interview-best-practices-for-hiring-managers/" target="_blank">Find out more</a>
                    </div>
                </article>
				
				<article class="article-card">
                    <div class="article-image">
                        <img src="../assets/img/advice/job-interview.png" alt="Graduate Resume Example for 2025">
                    </div>
                    <div class="article-content">
                        <h3 style="color:white;">Hiring 101: A Quick & Dirty Guide for First-Time Hiring Managers</h3>
                        <p style="color:white;">Interviewing for a job is stressful, whichever side of the table you’re on. But when you’re a new hiring manager, faced with the challenge of trying to hire the perfect employee off a piece of paper and hour-long conversation, stress takes on a whole new meaning. Given the high cost of a bad hire, you know you need to get it right the first time to avoid rebooting the hiring process. Having to re-hire, retrain, and retain a new employee costs time and money you don’t have.

						<br><br>Whether it’s your first time hiring or you just need a better process, there are a few key things you need to know to make your hiring process effective. Here are 7 helpful tips to help you manage this complex process and, in the end, land a great new team member!</p>
                        <br><a href="https://novoresume.com/career-blog/students-graduates-resume-example" target="_blank">Find out more</a>
                    </div>
                </article>
            </section>
        </div>
    </main>
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