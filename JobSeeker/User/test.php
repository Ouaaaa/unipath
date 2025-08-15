<?php
require_once "config.php"; // Ensure database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
         <title>Explore Company </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.ico">

        <!-- CSS here -->
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="example.css">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        
</head>
<body>
        <div class="job-card">
            <div class="job-header">
                <img src="accenture.png" alt="Accenture Logo" class="company-logo">
            </div>

            <div class="job-content">
                <h2>Software IT Associate (Entry Level) | Receive 30K* Joining Bonus</h2>
            </div>

                <div class="job-details">
                    <p><i class="fas fa-map-marker-alt"></i>Accenture</p>
                    <p><i class="fas fa-map-marker-alt"></i> Metro Manila, Philippines</p>
                    <p><i class="fas fa-code"></i> Developers/Programmers (Information & Communication Technology)</p>
                    <p><i class="fas fa-clock"></i> Full Time</p>
                </div>

                <button class="apply-btn">Apply Now</button>
                <nav class="custom-nav">
                    <ul>
                        <li><a href="#" class="active">About</a></li>
                        <li><a href="#">Life and Culture</a></li>
                        <li><a href="#">Jobs</a></li>
                        <li><a href="#">Reviews</a></li>
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
                        <tr>
                            <td>Software IT Associate</td>
                            <td>Metro Manila, Philippines</td>
                            <td>Full Time</td>
                            <td><a href="#" class="apply-btn">Apply Now</a></td>
                        </tr>
                        <tr>
                            <td>Web Developer</td>
                            <td>Cebu, Philippines</td>
                            <td>Part Time</td>
                            <td><a href="#" class="apply-btn">Apply Now</a></td>
                        </tr>
                        <tr>
                            <td>Data Analyst</td>
                            <td>Davao, Philippines</td>
                            <td>Full Time</td>
                            <td><a href="#" class="apply-btn">Apply Now</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>


        </body>
        </html>