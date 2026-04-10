<?php 
session_start();
include 'db_config.php';
require 'send_otp.php';
include 'head.php'; 

$message = "";
$message_type = "";

if (isset($_POST['send_otp'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $query = "SELECT * FROM User WHERE Email='$email' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $otp = rand(100000, 999999);
        $update_query = "UPDATE User SET otp='$otp', otp_expiry = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE Email='$email'";
        
        if (mysqli_query($con, $update_query)) {
            $to = $email;
            $subject = "Your Password Reset OTP";
            $email_content = "Your OTP for password reset is: $otp. It will expire in 15 minutes.";
            $headers = "From: no-reply@yourdomain.com";

            $_SESSION['reset_email'] = $email;
            sendOTP($email, $otp);
            
            header("Location: verify_otp.php?msg=sent"); 
            exit();
        } else {
            $message = "Error updating database: " . mysqli_error($con);
            $message_type = "danger";
        }
    } else {
        $message = "No account found with that email.";
        $message_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Forgot Password</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', sans-serif;
            background:
                linear-gradient(135deg, rgba(106, 17, 203, 0.75), rgba(37, 117, 252, 0.75)),
                url('https://images.unsplash.com/photo-1553413077-190dd305871c?w=1600') center/cover no-repeat fixed;
        }

        .page-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 50px 45px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }

        .brand { margin-bottom: 25px; }
        .brand .icon { font-size: 2.8rem; margin-bottom: 6px; }
        .brand h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }
        .brand p { color: #888; font-size: 0.85rem; margin-top: 4px; }

        .form-group { margin-bottom: 18px; text-align: left; }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 3px rgba(106,17,203,0.1);
            outline: none;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 1px;
            margin-top: 5px;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-submit:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .bottom-links {
            margin-top: 20px;
            font-size: 0.88rem;
            color: #666;
        }

        .bottom-links a {
            color: #6a11cb;
            text-decoration: none;
            font-weight: 600;
        }

        .bottom-links a:hover { text-decoration: underline; }

        .error-msg {
            background: #fff0f0;
            color: #e74c3c;
            border: 1px solid #fcc;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 0.88rem;
        }

        .footer-bar {
            background: rgba(0,0,0,0.4);
            color: rgba(255,255,255,0.8);
            text-align: center;
            padding: 14px;
            font-size: 0.82rem;
        }

        .footer-bar a { color: #a78bfa; text-decoration: none; }
    </style>
</head>
<body>

    <div class="page-wrapper">
        <div class="box">
            <div class="brand">
                <div class="icon">🔐</div>
                <h1>SMART INVENTORY</h1>
                <p>Enter your email to receive an OTP</p>
            </div>

            <?php if ($message != ""): ?>
                <div class="error-msg"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email Address" required />
                </div>
                <button type="submit" name="send_otp" class="btn-submit">SEND OTP</button>
            </form>

            <div class="bottom-links">
                <p>Remembered your password? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>

    <div class="footer-bar">
        &copy; 2026 Smart Inventory Group. All Rights Reserved. &nbsp;|&nbsp; Developed by <a href="#">Yasir Osman</a>
    </div>

</body>
</html>