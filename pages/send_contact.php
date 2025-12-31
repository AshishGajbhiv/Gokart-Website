<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../includes/PHPMailer/src/Exception.php';
require '../includes/PHPMailer/src/PHPMailer.php';
require '../includes/PHPMailer/src/SMTP.php';
require '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid Request');
}

$name    = htmlspecialchars($_POST['name']);
$email   = htmlspecialchars($_POST['email']);
$subject = htmlspecialchars($_POST['subject']);
$message = htmlspecialchars($_POST['message']);

$mail = new PHPMailer(true);

try {
    // SMTP CONFIG
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_EMAIL;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // EMAIL SETTINGS
    $mail->setFrom('help.sandd@gmail.com', 'S & D Motorsports');
    $mail->addAddress('help.sandd@gmail.com'); // Receive here
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = "Contact Form: $subject";
    $mail->Body = "
        <h2>New Contact Message</h2>
        <p><b>Name:</b> $name</p>
        <p><b>Email:</b> $email</p>
        <p><b>Subject:</b> $subject</p>
        <p><b>Message:</b><br>$message</p>
    ";

    $mail->send();
    echo "Message Sent Successfully";

} catch (Exception $e) {
    echo "Message Sending Failed";
}
