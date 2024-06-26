<?php
include "db/config.php";

// Check if user is already logged in using cookies
if (isset($_COOKIE['email'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    
    // Check if email already exists
    $sql = "SELECT * FROM users WHERE user_email = '$email'";
    $result = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $error_message = "Email already exists. Please use a different email address.";
    } else {
        // Hash the password before storing it
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (user_name, user_email, user_password) VALUES ('$username', '$email', '$password_hash')";
        
        if (mysqli_query($con, $sql)) {
            // Retrieve the user_id after insertion
            $user_id = mysqli_insert_id($con);

            // Set cookies
            setcookie('email', $email, time() + (86400 * 30), "/");
            setcookie('username', $username, time() + (86400 * 30), "/");
            setcookie('user_image', '', time() + (86400 * 30), "/");
            setcookie('user_id', $user_id, time() + (86400 * 30), "/");

            // Redirect after registration
            $success_message = "Registration successful! Redirecting to home ...";
            header("Refresh: 0; url=home.php");
            exit();
        } else {
            $error_message = "Error: " . $sql . "<br>" . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Recipe Saver</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .signup-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .signup-container h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="signup-container">
                    <h2 class="text-center">Welcome to Bob's Recipes App, Sign up here</h2>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                    </form>
                    <p class="text-center mt-3">Already have an account? <a href="index.php">Log In</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
