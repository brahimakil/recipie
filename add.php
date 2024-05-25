<?php
// Include the database configuration file
include('db/config.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $category = mysqli_real_escape_string($con, $_POST['category']);

    // Check if an image is uploaded
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target = "images/" . basename($image);
        // Move the uploaded image to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Insert data into the database with the image
            $insert_query = "INSERT INTO recipies (recipie_name, recipie_description, recipie_image, recipie_category) 
                             VALUES ('$title', '$description', '$image', '$category')";
        } else {
            echo "Failed to upload image.";
        }
    } else {
        // Insert data into the database without the image
        $insert_query = "INSERT INTO recipies (recipie_name, recipie_description, recipie_category) 
                         VALUES ('$title', '$description', '$category')";
    }

    // Execute the query if defined
    if (isset($insert_query) && mysqli_query($con, $insert_query)) {
        // Redirect to index.php after successful insertion
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe</title>
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
        <h2 class="mt-4" style="text-align: center;" >Add New Recipe</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Recipe Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" id="image" name="image" >
            </div>
            <div class="form-group">
                <label for="category">Choose Category</label>
                <select name="category" id="category" class="form-control">
                    <option value="">Select Category</option>
                    <option value="Sausage">Sausage</option>
                    <option value="Breakfast">Breakfast</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary" href="index.php" >Add Recipe</button>
            <button type="button" class="btn btn-danger" style="color: white;" ><a style="color: white;" href="index.php">Cancel</a></button>
        </form>
    </div>
</body>
</html>
