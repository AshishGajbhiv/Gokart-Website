<?php
session_start();
require '../includes/db.php';

/* ================= SECURITY CHECK ================= */

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    exit("OTP not verified");
}

/* ================= COLLECT DATA ================= */

$event_id = isset($_POST['event_id']) && $_POST['event_id'] !== ''
    ? (int)$_POST['event_id']
    : NULL;

$email   = mysqli_real_escape_string($conn, $_POST['email']);
$captain = mysqli_real_escape_string($conn, $_POST['captain_name']);
$vice    = mysqli_real_escape_string($conn, $_POST['vice_captain_name']);
$mobile  = mysqli_real_escape_string($conn, $_POST['mobile']);
$college = mysqli_real_escape_string($conn, $_POST['college']);
$team    = mysqli_real_escape_string($conn, $_POST['team_name']);
$category = mysqli_real_escape_string($conn, $_POST['vehicle_category']);

/* ================= DUPLICATE CHECK ================= */

$checkQuery = "
    SELECT status 
    FROM registrations 
    WHERE email='$email' " . ($event_id ? "AND event_id=$event_id" : "") . "
    ORDER BY id DESC 
    LIMIT 1
";

$check = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($check) > 0) {
    $row = mysqli_fetch_assoc($check);

    if ($row['status'] === 'PENDING') {
        exit("You have already registered. Your registration is under review.");
    }

    if ($row['status'] === 'APPROVED') {
        exit("Your registration is already approved. Duplicate registration is not allowed.");
    }
    // REJECTED → allowed
}

/* ================= FILE VALIDATION ================= */

$allowedExt = ['jpg', 'jpeg', 'png'];

/* Team Logo */
$logoExt = strtolower(pathinfo($_FILES['team_logo']['name'], PATHINFO_EXTENSION));
if (!in_array($logoExt, $allowedExt)) {
    exit("Invalid team logo format. Only JPG, JPEG, PNG allowed.");
}

/* Payment Screenshot */
$payExt = strtolower(pathinfo($_FILES['payment_screenshot']['name'], PATHINFO_EXTENSION));
if (!in_array($payExt, $allowedExt)) {
    exit("Invalid payment screenshot format. Only JPG, JPEG, PNG allowed.");
}

/* ================= FILE UPLOAD ================= */

$logoName = time() . '_logo_' . basename($_FILES['team_logo']['name']);
$paymentName = time() . '_payment_' . basename($_FILES['payment_screenshot']['name']);

$uploadPath = "../uploads/";

if (!move_uploaded_file($_FILES['team_logo']['tmp_name'], $uploadPath . $logoName)) {
    exit("Failed to upload team logo");
}

if (!move_uploaded_file($_FILES['payment_screenshot']['tmp_name'], $uploadPath . $paymentName)) {
    exit("Failed to upload payment screenshot");
}

/* ================= INSERT INTO DB ================= */

$query = "
INSERT INTO registrations (
    event_id,
    captain_name,
    vice_captain_name,
    email,
    mobile,
    college,
    team_name,
    vehicle_category,
    team_logo,
    payment_screenshot,
    status,
    created_at
) VALUES (
    " . ($event_id ? $event_id : "NULL") . ",
    '$captain',
    '$vice',
    '$email',
    '$mobile',
    '$college',
    '$team',
    '$category',
    '$logoName',
    '$paymentName',
    'PENDING',
    NOW()
)";

if (mysqli_query($conn, $query)) {
    unset($_SESSION['otp_verified']); // prevent reuse
    echo "Registration submitted successfully";
} else {
    echo "Database error. Please try again later.";
}
