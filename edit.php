<?php
// Include the database configuration file
session_start();
include('db/config.php');
//check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

//get user id from session
$user_id = $_SESSION['user_id'];

// Check if the edit form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    // Sanitize and retrieve form data
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $old_image = $_POST['old_image'];
    
    // Handle the image upload
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target = "images/" . basename($image);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "Failed to upload image.";
            exit();
        }
    } else {
        $image = $old_image;
    }

    // Update data in the database
    $update_query = "UPDATE recipies SET recipie_name='$title', recipie_description='$description', recipie_image='$image', category_id='$category' WHERE recipie_id='$id' AND user_id = '$user_id'";

    if (mysqli_query($con, $update_query)) {
        // Redirect to home.php after successful update
        header("Location: home.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Fetch the recipe data to be edited
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $select_query = "SELECT * FROM recipies WHERE recipie_id='$id' AND user_id = '$user_id'";
    $result = mysqli_query($con, $select_query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $title = $row['recipie_name'];
        $description = $row['recipie_description'];
        $image = $row['recipie_image'];
        $category = $row['category_id']; // Fetch category from database
    } else {
        echo "No recipe found with the given ID.";
        exit();
    }
} else {
    echo "No recipe ID provided.";
    exit();
}

// Fetch all categories for the dropdown
$categories_query = "SELECT * FROM categories where user_id = '$user_id'";
$categories_result = mysqli_query($con, $categories_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
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
        .title {
            margin-bottom: 1rem;
        }
        .description {
            margin-bottom: 1.5rem;
            white-space: pre-wrap; /* This property ensures that whitespace is preserved and text wraps when necessary */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Edit Recipe</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="old_image" value="<?php echo $image; ?>">
            <div class="form-group">
                <label for="title">Recipe Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $description; ?></textarea>
            </div>
            <div class="form-group">
                <label for="category">Choose Category</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php
                    if ($categories_result) {
                        while ($category_row = mysqli_fetch_assoc($categories_result)) {
                            $category_id = $category_row['category_id'];
                            $category_name = $category_row['category_name'];
                            $selected = ($category == $category_id) ? "selected" : "";
                            echo "<option value='$category_id' $selected>$category_name</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <img src="images/<?php echo $image; ?>" alt="Current Image" style="margin-top: 10px; width: 100px;">
            </div>

            <button type="submit" class="btn btn-primary" name="update">Update Recipe</button>
            <button type="button" class="btn btn-danger"><a style="color: white;" href="home.php">Cancel</a></button>
        </form>
    </div>
    <br>
    <br>
</body>
</html>
