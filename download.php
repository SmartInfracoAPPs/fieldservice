<?php
require 'config.php';

ob_start(); // Start output buffering

if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    $filepath = 'uploads/' . $filename;

    if (file_exists($filepath)) {
        // Set headers for file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));

        // Output file contents directly to browser
        readfile($filepath);
        exit; // Stop further script execution
    } else {
        die("File not found.");
    }
} else {
    die("Invalid filename parameter.");
}

// End output buffering and flush
ob_end_flush();
?>
