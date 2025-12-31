<?php
session_start();

require '../includes/db.php';
require '../includes/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../includes/PHPMailer/src/Exception.php';
require '../includes/PHPMailer/src/PHPMailer.php';
require '../includes/PHPMailer/src/SMTP.php';

if (!isset($_POST['email'])) {
    exit('Email required');
}

$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

if (!$email) {
    exit('Invalid email address');
}

/* ================= OTP LOGIC ================= */

$otp = rand(100000, 999999);

// store OTP in session
$_SESSION['otp'] = $otp;
$_SESSION['otp_verified'] = false;
$_SESSION['otp_time'] = time();

/* ================= SEND EMAIL ================= */

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_EMAIL;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom(SMTP_EMAIL, 'S & D Motorsports');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your OTP for Registration';
    $mail->Body = "
        <h2>Email Verification</h2>
        <p>Your OTP is:</p>
        <h1 style='letter-spacing:4px;'>$otp</h1>
        <p>This OTP is valid for 5 minutes.</p>
        <br>
        <b>S & D Motorsports</b>
    ";

    $mail->send();
    echo "OTP Sent Successfully";

} catch (Exception $e) {
    echo "OTP sending failed";
}
// } catch (Exception $e) {
//     echo "Mailer Error: " . $mail->ErrorInfo;
// }

