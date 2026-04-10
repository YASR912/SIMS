<?php include 'head.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = mysqli_real_escape_string($con, $_POST['username']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);
  $confirmPassword = mysqli_real_escape_string($con, $_POST['confirmPassword']);
  $firstName = mysqli_real_escape_string($con, $_POST['firstName']);
  $lastName = mysqli_real_escape_string($con, $_POST['lastName']);
  $gender = mysqli_real_escape_string($con, $_POST['gender']);
  $role = 'Employee';

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format!');</script>";
  } else if ($password !== $confirmPassword) {
    echo "<script>alert('Passwords do not match!');</script>";
  } else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $checkUser = mysqli_query($con, "SELECT * FROM User WHERE username = '$username' OR email = '$email'");
    if (mysqli_num_rows($checkUser) > 0) {
      echo "<script>alert('Username or Email already exists!');</script>";
    } else {
      $query = "INSERT INTO User (Username, Email, Password, Role, FirstName, LastName, Gender) VALUES ('$username', '$email', '$hashedPassword', '$role', '$firstName', '$lastName', '$gender')";
      if (mysqli_query($con, $query)) {
        echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
      } else {
        echo "<script>alert('Error in registration. Please try again.');</script>";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Smart Inventory Register</title>
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

        .register-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 45px 45px;
            width: 100%;
            max-width: 480px;
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

        .form-group { margin-bottom: 14px; text-align: left; }

        .form-control {
            width: 100%;
            padding: 11px 16px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.92rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 3px rgba(106,17,203,0.1);
            outline: none;
        }

        .btn-register {
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

        .btn-register:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .bottom-links {
            margin-top: 18px;
            font-size: 0.88rem;
            color: #666;
        }

        .bottom-links a {
            color: #6a11cb;
            text-decoration: none;
            font-weight: 600;
        }

        .bottom-links a:hover { text-decoration: underline; }

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
        <div class="register-box">
            <div class="brand">
                <div class="icon">📦</div>
                <h1>SMART INVENTORY</h1>
                <p>Create your account</p>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" required />
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email Address" required />
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="firstName" placeholder="First Name" required />
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="lastName" placeholder="Last Name" required />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm Password" required />
                </div>
                <div class="form-group">
                    <select class="form-control" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <button type="submit" class="btn-register">SIGN UP</button>
            </form>

            <div class="bottom-links">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>

    <div class="footer-bar">
        &copy; 2026 Smart Inventory Group. All Rights Reserved. &nbsp;|&nbsp; Developed by <a href="#">Yasir Osman</a>
    </div>

</body>
</html>