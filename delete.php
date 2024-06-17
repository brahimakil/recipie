<?php
// Include the database configuration file
include('db/config.php');
session_start();

// Check if user is logged in using cookies
if (!isset($_COOKIE['email'])) {
    header("Location: index.php");
    exit();
}

// Get the user_id from cookies
$user_id = $_COOKIE['user_id'];

// Check if recipe ID is provided in the GET request
if (isset($_GET['id'])) {
    // Sanitize and retrieve the recipe ID from the GET request
    $id = mysqli_real_escape_string($con, $_GET['id']);

    // Query to fetch recipe details and ensure it belongs to the logged-in user
    $select_query = "SELECT recipie_image FROM recipies WHERE recipie_id='$id' AND user_id = '$user_id'";
    $result = mysqli_query($con, $select_query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $image = $row['recipie_image'];

        // Delete the recipe from the database
        $delete_query = "DELETE FROM recipies WHERE recipie_id='$id' AND user_id = '$user_id'";
        if (mysqli_query($con, $delete_query)) {
            // Delete the image file from the server
            if (file_exists("images/" . $image)) {
                unlink("images/" . $image);
            }
            // Redirect to home.php after successful deletion
            header("Location: home.php");
            exit();
        } else {
            echo "Error deleting recipe: " . mysqli_error($con);
        }
    } else {
        echo "No recipe found with the given ID.";
    }
} else {
    echo "Invalid request.";
}
?>
