<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile Edit</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="profile-container">
        <h2>Edit Company Profile</h2>
        
        <!-- Company Logo Upload -->
        <div class="logo-upload">
            <img id="logoPreview" src="placeholder-logo.png" alt="Company Logo">
            <input type="file" id="logoInput" accept="image/*">
            <label for="logoInput" class="upload-btn">Upload Logo</label>
        </div>

        <!-- Company Info Form -->
        <form id="companyForm">
            <label>Company Name</label>
            <input type="text" id="companyName" placeholder="Enter Company Name" required>

            <label>Email</label>
            <input type="email" id="companyEmail" placeholder="Enter Email" required>

            <label>Description</label>
            <textarea id="companyDescription" placeholder="Enter Company Description"></textarea>

            <button type="submit" class="save-btn">Save Profile</button>
        </form>
    </div>

    <script>
        document.getElementById('logoInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('companyForm').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Profile Updated Successfully!');
        });

    </script>
</body>
</html>
