<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    exit("Unauthorized");
}

$text = mysqli_real_escape_string($conn, $_POST['notice_text']);

// Deactivate old notices
mysqli_query($conn, "UPDATE notices SET is_active=0");

// Insert new notice
mysqli_query(
    $conn,
    "INSERT INTO notices (notice_text, is_active) VALUES ('$text', 1)"
);

header("Location: manage_notice.php");
exit;
