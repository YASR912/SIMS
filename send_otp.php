<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// ✅ دالة عامة لكل الإيميلات
function sendMail($to, $subject, $body) {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'smart.inventory.deme@gmail.com';
        $mail->Password = 'eeovibavlnuicdkc';

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('smart.inventory.deme@gmail.com', 'SIMS System');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();

        return true;

    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}

// 🔐 OTP Function
function sendOTP($email, $otp) {

    $subject = "OTP Verification Code";

    $body = "
        <h2>Smart Inventory System</h2>
        <p>Your OTP Code is:</p>
        <h1 style='color:blue;'>$otp</h1>
        <p>This code expires in 5 minutes.</p>
    ";

    return sendMail($email, $subject, $body);
}