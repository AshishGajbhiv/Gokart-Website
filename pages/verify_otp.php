<?php
session_start();

if (!isset($_POST['otp'])) {
    exit('OTP required');
}

if (!isset($_SESSION['otp'])) {
    exit('OTP expired');
}

// OTP valid for 5 minutes
if (time() - $_SESSION['otp_time'] > 300) {
    unset($_SESSION['otp'], $_SESSION['otp_time']);
    exit('OTP expired');
}

if ($_POST['otp'] == $_SESSION['otp']) {
    $_SESSION['otp_verified'] = true;
    unset($_SESSION['otp']);
    echo "OTP verified successfully";
} else {
    echo "Invalid OTP";
}
