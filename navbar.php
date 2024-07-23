<!-- navbar.php -->
<nav>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="upload.php">Upload Image and Text File</a></li>
        <li style="float: right;"><a href="logout.php">Logout</a></li>
        <li style="float: right;">Welcome, <?php echo $_SESSION['name']; ?></li>
    </ul>
</nav>
