<?php
include "db/config.php";

// Check if user is logged in using cookies
if (!isset($_COOKIE['email'])) {
    header("Location: index.php");
    exit();
}

// Retrieve user details from cookies
$user_id = $_COOKIE['user_id'];

// Handle "Add" button redirection
if (isset($_POST['add'])) {
    header("Location: add.php");
    exit();
}

// Fetch all categories from the database
$categories_query = "SELECT * FROM categories WHERE user_id = '$user_id'";
$categories_result = mysqli_query($con, $categories_query);

// Initialize select query
$select_query = "SELECT * FROM recipies, categories WHERE recipies.category_id = categories.category_id AND recipies.user_id = '$user_id'";

// Adjust query based on the selected category button or URL parameter
if (isset($_POST['view_all'])) {
    $select_query = "SELECT * FROM recipies WHERE user_id = '$user_id'";
} elseif (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $select_query = "SELECT * FROM recipies, categories WHERE recipies.category_id = categories.category_id AND categories.category_id = '$category_id' AND recipies.user_id = '$user_id'";
}

$result = mysqli_query($con, $select_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Saver</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-card {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .custom-card:hover {
            transform: scale(1.02);
        }

        .custom-card img {
            max-width: 100%;
            height: auto;
        }

        .custom-card .description,
        .custom-card .title {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .custom-card .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .custom-card .btn-edit {
            background-color: green;
            border-color: green;
            margin-right: 10px;
        }

        .add-button-container {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 7px;
        }

        .add-button-container button {
            flex: 1;
            min-width: 100px;
            max-width: 130px;
        }

        .title {
            margin-bottom: 1rem;
            font-weight: bold;
            font-size: 20px;
        }

        .description {
            margin-bottom: 1.5rem;
            white-space: pre-wrap; /* This property ensures that whitespace is preserved and text wraps when necessary */
        }

        .custom-card a {
            color: inherit;
            text-decoration: none;
        }
    </style>
    <script>
        function confirmDelete(url) {
            if (confirm("Are you sure you want to delete this recipe?")) {
                window.location.href = url;
            }
        }
    </script>
</head>
<body>
    <form method="post">
        <h1 style="text-align: center; margin-top: 20px">Recipe Saver</h1>
        <div class="add-button-container">
            <button class="btn btn-primary" type="submit" name="add">Add Recipe</button>
            <button class="btn btn-danger" type="button"><a style="color: white;" href="add_category.php">Add Category</a></button>
            <button class="btn btn-primary" style="background-color: cadetblue;" type="submit" name="view_all">View All Recipes</button>
            <button class="btn btn-danger" style="background-color: burlywood;" type="button"><a style="color: white;" href="edit_categories.php">Edit Categories</a></button>
            <button class="btn btn-info" type="button"><a style="color: white;" href="view_categories.php">Categories</a></button>
            <button class="btn btn-danger" style="background-color: green;" type="button"><a style="color: white;" href="livepanel.php">Live Panel</a></button>
            <!-- Logout button -->
            <button class="btn btn-danger" style="color: white;" type="button" name="logout" value="logout"><a style="color: white;" href="logout.php">Logout</a></button>
            <?php
            // Dynamically create buttons for each category
            if ($categories_result) {
                while ($category_row = mysqli_fetch_assoc($categories_result)) {
                    $category_id = $category_row['category_id'];
                    $category_name = $category_row['category_name'];
                    // echo "<button class='btn btn-primary' type='submit' style='background-color:grey;' name='category_id' value='$category_id'>View Category: <b>$category_name</b></button>";
                }
            }
            ?>
        </div>
    </form>
    <br><br>
    <div class="container">
        <div class="row">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['recipie_id'];
                    $title = $row['recipie_name'];
                    $description = $row['recipie_description'];
                    $image = $row['recipie_image'];
                    $category_id = $row['category_id'];

                    // Fetch category name
                    $category_query = "SELECT category_name FROM categories WHERE category_id = '$category_id'";
                    $category_result = mysqli_query($con, $category_query);
                    $category_row = mysqli_fetch_assoc($category_result);
                    $category_name = $category_row['category_name'];
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <a href="edit.php?id=<?php echo $id; ?>">
                            <div class="custom-card">
                                <img src="images/<?php echo $image; ?>" alt="Image">
                                <div class="title"><?php echo $title; ?></div>
                                <div class="description"><b style="font-size: 15px;"></b> <?php echo $description; ?></div>
                                <div class="category"><b style="font-size: 13px;">Category:</b> <?php echo $category_name; ?></div>
                                <div class="btn-container">
                                    <a href="edit.php?id=<?php echo $id; ?>" style="color: white" class="btn btn-primary btn-edit">View and Edit</a>
                                    <button class="btn btn-danger" onclick="confirmDelete('delete.php?id=<?php echo $id; ?>')">Delete</button>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "No recipes found.";
            }
            ?>
        </div>
        <br>
    </div>
</body>
</html>
