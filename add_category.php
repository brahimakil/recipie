<?php
// Include the database configuration file
include('db/config.php');

// Check if user is logged in using cookies
if (!isset($_COOKIE['email'])) {
    header("Location: index.php");
    exit();
}

// Get the user_id from cookies
$user_id = $_COOKIE['user_id'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $category_name = mysqli_real_escape_string($con, $_POST['category_name']);

    // Check if category already exists for this user
    $check_query = "SELECT * FROM categories WHERE category_name = '$category_name' AND user_id = '$user_id'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error_message = "Category already exists. Please select a different category.";
    } else {
        // Insert data into the database
        $insert_query = "INSERT INTO categories (category_name, user_id) VALUES ('$category_name', '$user_id')";

        // Execute the query
        if (mysqli_query($con, $insert_query)) {
            // Redirect to home.php after successful insertion
            header("Location: home.php");
            exit();
        } else {
            $error_message = "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-control {
            height: calc(2.25rem + 9px);
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-danger {
            color: white;
            width: 80px;
            height: 30px;
            border-color: red;
            background-color: red;
        }
        .btn-primary:hover {
            color: #fff;
            background-color: #0056b3;
            border-color: #004085;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4" style="text-align: center;">Add New Category</h2>
        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>
        <form method="post">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
            <button type="button" class="btn btn-danger"><a style="color: white;" href="home.php">Cancel</a></button>
        </form>
    </div>
</body>
</html>
