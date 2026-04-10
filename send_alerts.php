<?php
require 'send_otp.php';

function sendAlertEmail($name, $email, $product, $days_left) {

    $subject = "⚠️ Smart Inventory Alert";

    $body = "
        <h2>Hello $name 👋</h2>

        <p>Stock Alert from SIMS:</p>

        <p><b>Product:</b> $product</p>
        <p><b>Estimated Days Left:</b> $days_left</p>

        <p style='color:red;'>
        ⚠️ Stock level is changing based on sales.
        </p>

        <br>
        <p>SIMS System</p>
    ";

    return sendMail($email, $subject, $body);
}