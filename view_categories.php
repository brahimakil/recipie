<?php
include 'db/config.php';

// Check if user is logged in using cookies
if (!isset($_COOKIE['email'])) {
    header("Location: index.php");
    exit();
}

// Retrieve the user_id from cookies
$user_id = $_COOKIE['user_id'];

// Fetch all categories belonging to the logged-in user from the database
$query = "SELECT * FROM categories WHERE user_id = '$user_id'";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Types of Categories</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .btn-category {
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4" style="text-align: center;">View Types of Categories</h2>
        <div class="text-center">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $category_id = $row['category_id'];
                    $category_name = $row['category_name'];
                    echo "<button class='btn btn-primary btn-category' onclick=\"window.location.href='home.php?category_id=$category_id'\">$category_name</button>";
                }
            } else {
                echo "<p>No categories found.</p>";
            }
            ?>
        </div>
        <div class="text-center mt-4">
            <a href="home.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
</body>
</html>
