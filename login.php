<?php include 'head.php'; ?>

<?php
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $query = "SELECT * FROM User WHERE Email='$email' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['Password'])) {
            session_start();
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['role'] = $user['Role'];
            $_SESSION['loggedin'] = true;
            header("Location: index.php");
            exit();
        } else {
            $login_error = "Invalid email or password.";
        }
    } else {
        $login_error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Smart Inventory Login</title>
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

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 50px 45px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }

        .brand {
            margin-bottom: 30px;
        }

        .brand .icon {
            font-size: 3rem;
            margin-bottom: 8px;
        }

        .brand h1 {
            font-size: 1.6rem;
            font-weight: 700;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .brand p {
            color: #888;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

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

        .btn-login {
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

        .btn-login:hover {
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
            margin-top: 15px;
            font-size: 0.88rem;
        }

        /* Footer */
        .footer-bar {
            background: rgba(0,0,0,0.4);
            color: rgba(255,255,255,0.8);
            text-align: center;
            padding: 14px;
            font-size: 0.82rem;
        }

        .footer-bar a {
            color: #a78bfa;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="page-wrapper">
        <div class="login-box">
            <div class="brand">
                <div class="icon">📦</div>
                <h1>SMART INVENTORY</h1>
                <p>Manage your inventory smarter</p>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email Address" required />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required />
                </div>
                <button type="submit" name="login" class="btn-login">SIGN IN</button>
            </form>

            <?php if (isset($login_error)): ?>
                <div class="error-msg"><?= $login_error ?></div>
            <?php endif; ?>

            <div class="bottom-links">
                <p>Don't have an account? <a href="register.php">Create one</a></p>
                <p class="mt-2"><a href="forgot_password.php">Forgot Password?</a></p>
            </div>
        </div>
    </div>

    <div class="footer-bar">
        &copy; 2026 Smart Inventory Group. All Rights Reserved. &nbsp;|&nbsp; Developed by <a href="#">Yasir Osman</a>
    </div>

</body>
</html>