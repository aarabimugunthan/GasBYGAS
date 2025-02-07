<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();
require 'config/database.php';
require 'vendor/autoload.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head_office') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_emails = json_decode($_POST['recipient_emails'], true);
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $mailSent = false;

    require 'vendor/autoload.php';
    
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nirujann581@gmail.com';
        $mail->Password = 'dlmj saxc ovlf lngr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@gasbygas.com', 'GasByGas');
        
        // Add all recipients as BCC
        foreach ($recipient_emails as $email) {
            $mail->addBCC($email);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        $mailSent = true;
        $_SESSION['success_message'] = "Emails sent successfully!";
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        $mailSent = false;
        $_SESSION['error_message'] = "Failed to send emails: " . $mail->ErrorInfo;
    }

    header('Location: manage_users.php');
    exit();
}

?>