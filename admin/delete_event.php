<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: manage_events.php");
    exit;
}

$event_id = intval($_GET['id']);

/* 1️⃣ Delete event images (files + DB) */
$imgs = mysqli_query($conn, "SELECT image_name FROM event_images WHERE event_id = $event_id");

while ($img = mysqli_fetch_assoc($imgs)) {
    $imgPath = "../uploads/event_images/" . $img['image_name'];
    if (file_exists($imgPath)) {
        unlink($imgPath);
    }
}

mysqli_query($conn, "DELETE FROM event_images WHERE event_id = $event_id");

/* 2️⃣ Delete rulebook file */
$event = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT rulebook_pdf FROM events WHERE id = $event_id")
);

if ($event && !empty($event['rulebook_pdf'])) {
    $pdfPath = "../uploads/event_rulebook/" . $event['rulebook_pdf'];
    if (file_exists($pdfPath)) {
        unlink($pdfPath);
    }
}

/* 3️⃣ Delete event record */
mysqli_query($conn, "DELETE FROM events WHERE id = $event_id");

/* 4️⃣ Redirect back */
header("Location: manage_events.php");
exit;
