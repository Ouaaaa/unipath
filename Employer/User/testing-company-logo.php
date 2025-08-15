<?php
require_once "config.php";

if(isset($_POST['submit']))
{
    $logo_name = $_FILES['logo']['name'];
    $tempname = $_FILES['logo']['tmp_name'];
    $folder = 'logos/'.$logo_name;

    // Corrected SQL syntax
    $query = mysqli_query($link, "INSERT INTO tblcompany (company_logo) VALUES ('$logo_name')");

    if($query && move_uploaded_file($tempname, $folder))
    {
        echo "<h2>File uploaded successfully</h2>";
    }
    else
    {
        echo "<h2>File not uploaded</h2>";
    }
}
?>

<html>
    <head>
        <title>Testing for upload</title>
    </head>
    <body>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="logo"/>
            <br /><br />
            <button type="submit" name="submit">Submit</button>
        </form>

        <div>
            <?php 
            $res = mysqli_query($link, "SELECT * FROM tblcompany");
            while($row = mysqli_fetch_assoc($res)) {
            ?>
            <img src="logos/<?php echo $row['company_logo']; ?>" width="100" />
            <?php } ?>
        </div>
    </body>
</html>
