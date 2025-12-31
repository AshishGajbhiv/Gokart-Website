<?php
session_start();
require '../includes/PHPMailer/src/PHPMailer.php';
require '../includes/PHPMailer/src/SMTP.php';
require '../includes/PHPMailer/src/Exception.php';
require '../includes/config.php';

use PHPMailer\PHPMailer\PHPMailer;

$otp = rand(100000,999999);
$_SESSION['admin_otp'] = $otp;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username   = SMTP_EMAIL;
$mail->Password   = SMTP_PASS;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('help.sandd@gmail.com','S & D Motorsports');
$mail->addAddress($_POST['email']);
$mail->isHTML(true);
$mail->Subject = 'Admin OTP Verification';
$mail->Body = "<h2>Your OTP: $otp</h2>";

$mail->send();
echo "OTP sent successfully";
