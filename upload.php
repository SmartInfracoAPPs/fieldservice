<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $comments = $_POST['comments'];
    $EngName = $_POST['EngName']; // Ensure correct field name here

    // Handle image uploads
    $totalImgFiles = count($_FILES['fileImg']['name']);
    $imgFilesArray = array();

    for ($i = 0; $i < $totalImgFiles; $i++) {
        $imgName = $_FILES["fileImg"]["name"][$i];
        $imgTmpName = $_FILES["fileImg"]["tmp_name"][$i];
        $imgExtension = pathinfo($imgName, PATHINFO_EXTENSION);
        $newImgName = uniqid() . '.' . $imgExtension;
        $targetImgFilePath = 'uploads/' . $newImgName;

        if (move_uploaded_file($imgTmpName, $targetImgFilePath)) {
            $imgFilesArray[] = $newImgName;
        } else {
            echo "Failed to upload image file {$imgName}.<br>";
        }
    }

    // Handle text file upload
    $txtName = $_FILES["fileTxt"]["name"];
    $txtTmpName = $_FILES["fileTxt"]["tmp_name"];
    $txtExtension = pathinfo($txtName, PATHINFO_EXTENSION);
    $newTxtName = uniqid() . '.' . $txtExtension;
    $targetTxtFilePath = 'uploads/' . $newTxtName;

    if (move_uploaded_file($txtTmpName, $targetTxtFilePath)) {
        // Insert filenames into database
        $imgFilesJson = json_encode($imgFilesArray);
        $query = "INSERT INTO tb_images (name, date, comments, image, text_file, EngName) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters and execute statement
        $stmt->bind_param("ssssss", $name, $date, $comments, $imgFilesJson, $newTxtName, $EngName);

        
        if ($stmt->execute()) {
            echo "
                <script>
                    alert('Successfully Added to Database');
                    window.location.href = 'home.php';
                </script>";
        } else {
            echo "Error: " . $query . "<br>" . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Failed to upload text file {$txtName}.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload A Record</title>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body>
    <nav>
        <ul>
            <li style="float: left;"><a href="home.php">Home</a></li>
            <li style="float: left;"><a href="#">Views</a></li>
            <li style="float: right;"><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="title">
            <h2>Upload a new record</h2>
        </div>
        <div class="half">
            <div class="item">
                <label for="name">Site ID/ID</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="item">
                <label for="date">Date of Installation</label>
                <input type="date" id="date" name="date" required>
            </div>
        </div>
        <div class="half">
            <div class="item">
                <label for="EngName">Engineer Name</label>
                <input type="text" id="EngName" name="EngName" required>
            </div>
        </div>
        <div class="full">
            <div class="item">
                <label for="comments">Enter your comments:</label>
                <textarea id="comments" name="comments" rows="4" placeholder="Write your message here..."></textarea>
            </div>
        </div>
        <div class="fullitem">
            <div class="itemfull">
                <label for="fileImg">Upload Pictures</label>
                <input type="file" id="fileImg" name="fileImg[]" accept=".jpg, .jpeg, .png" required multiple><br>
            </div>
        </div>
        <div class="fullitem">
            <div class="itemfull">
                <label for="fileTxt">Upload Config</label>
                <input type="file" id="fileTxt" name="fileTxt" accept=".txt" required><br>
            </div>
        </div>
        <div class="action">
            <input type="submit" name="submit" value="SUBMIT">
        </div>
    </form>
</body>
</html>
