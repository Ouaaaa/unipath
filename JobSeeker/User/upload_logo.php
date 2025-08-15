<?php
require_once "config.php"; // Include database connection

if (isset($_POST['submit'])) {
    $company_name = $_POST['company_name'];

    // Check if a file was uploaded
    if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === 0) {
        $target_dir = "uploads/"; // Folder to store images
        $file_name = basename($_FILES['company_logo']['name']);
        $target_file = $target_dir . time() . "_" . $file_name; // Unique file name

        // Allowed file types
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_types)) {
            if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $target_file)) {
                // Insert into database
                $sql = "INSERT INTO tblcompanies (companyName, logo_path) VALUES (?, ?)";
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $company_name, $target_file);
                if (mysqli_stmt_execute($stmt)) {
                    echo "Logo uploaded successfully!";
                } else {
                    echo "Error inserting data.";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type.";
        }
    } else {
        echo "Please select a valid logo.";
    }

    mysqli_close($link);
}
?>
