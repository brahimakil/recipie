<?php
session_start();
include "db/config.php";

//if already logged in redirect to home page
if (isset($_SESSION['email'])) {
    header("Location: home.php");
    exit();
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Fetch the user details from the database
    $result = mysqli_query($con, "SELECT * FROM users WHERE user_email='$email'") or die("Select Error");
    $row = mysqli_fetch_assoc($result);

    // Check if user exists and the password is correct
    if ($row && password_verify($password, $row['user_password'])) {
        // Set session variables
        $_SESSION['email'] = $row['user_email'];
        $_SESSION['username'] = $row['user_name'];
        $_SESSION['user_image'] = $row['user_image'];
        $_SESSION['user_id'] = $row['user_id'];

        // Redirect to home page
        header("Location: home.php");
        exit();
    } else {
        // Error message for invalid credentials
        $error_message = "Invalid email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Recipe Saver</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="login-container">
                    
                    <h2 class="text-center">Welcome to bob's recipes app , log in here </h2>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="submit">Login</button>
                    </form>
                    <p class="text-center mt-3">Don't have an account? <a href="signup.php">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
