<?php
// Include the database configuration file
include('db/config.php');

// Retrieve categories from the database
$query = "SELECT * FROM categories";
$result = mysqli_query($con, $query);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error_message = "";

    // Loop through the categories and update them
    while ($row = mysqli_fetch_assoc($result)) {
        $category_id = $row['category_id'];
        $category_name = mysqli_real_escape_string($con, $_POST["category_name_$category_id"]);
        
        // Check if the category name already exists (excluding the current category)
        $check_query = "SELECT * FROM categories WHERE category_name = '$category_name' AND category_id != '$category_id'";
        $check_result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Provide an error message
            $error_message = "Category '$category_name' already exists. Please choose a different category name.";
            break;
        } else {
            // Update the category in the database
            $update_query = "UPDATE categories SET category_name = '$category_name' WHERE category_id = '$category_id'";
            mysqli_query($con, $update_query);
        }
    }

    // If there's no error, redirect to index.php after successful update
    if (empty($error_message)) {
        header("Location: index.php");
        exit();
    }
}

// Re-fetch the categories after potential updates for display
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Categories</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4" style="text-align: center;">Edit Categories</h2>
        
        <form method="post">
        <?php
        if (!empty($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="form-group">
                    <label for="category_name_<?php echo $row['category_id']; ?>">Category Name</label>
                    <input type="text" class="form-control" id="category_name_<?php echo $row['category_id']; ?>" name="category_name_<?php echo $row['category_id']; ?>" value="<?php echo $row['category_name']; ?>" required>
                </div>
            <?php } ?>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-danger"><a style="color: white;" href="index.php">Cancel</a></button>
        </form>
    </div>
</body>
</html>
