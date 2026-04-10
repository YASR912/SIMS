<?php 
include 'head.php'; 

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: forgot_password.php");
    exit();
}

$message = "";
$message_type = "";
$email = $_SESSION['reset_email'];

if (isset($_POST['reset_password'])) {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE User SET Password='$hashed_password', otp=NULL, otp_expiry=NULL WHERE Email='$email'";
        
        if (mysqli_query($con, $update_query)) {
            unset($_SESSION['reset_email']);
            unset($_SESSION['otp_verified']);
            $message = "Password reset successful! Redirecting to login...";
            $message_type = "success";
            header("refresh:3;url=login.php");
        } else {
            $message = "Error updating password.";
            $message_type = "danger";
        }
    } else {
        $message = "Passwords do not match.";
        $message_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Reset Password</title>
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

        .alert-box {
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 0.88rem;
        }

        .alert-danger {
            background: #fff0f0;
            color: #e74c3c;
            border: 1px solid #fcc;
        }

        .alert-success {
            background: #f0fff4;
            color: #27ae60;
            border: 1px solid #b2dfdb;
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
                <div class="icon">🔑</div>
                <h1>SMART INVENTORY</h1>
                <p>Enter your new password below</p>
            </div>

            <?php if ($message != ""): ?>
                <div class="alert-box alert-<?= $message_type ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input
                        type="password"
                        class="form-control"
                        name="password"
                        placeholder="New Password"
                        required
                    />
                </div>
                <div class="form-group">
                    <input
                        type="password"
                        class="form-control"
                        name="confirm_password"
                        placeholder="Confirm New Password"
                        required
                    />
                </div>
                <button type="submit" name="reset_password" class="btn-submit">RESET PASSWORD</button>
            </form>
        </div>
    </div>

    <div class="footer-bar">
        &copy; 2026 Smart Inventory Group. All Rights Reserved. &nbsp;|&nbsp; Developed by <a href="#">Yasir Osman</a>
    </div>

</body>
</html>