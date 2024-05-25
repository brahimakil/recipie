<?php
// Include the database configuration file
include('db/config.php');

if (isset($_GET['id'])) {
    // Sanitize and retrieve the recipe ID from the GET request
    $id = mysqli_real_escape_string($con, $_GET['id']);

    // Get the image file name to delete the image file from the server
    $select_query = "SELECT recipie_image FROM recipies WHERE recipie_id='$id'";
    $result = mysqli_query($con, $select_query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $image = $row['recipie_image'];
        
        // Delete the recipe from the database
        $delete_query = "DELETE FROM recipies WHERE recipie_id='$id'";
        if (mysqli_query($con, $delete_query)) {
            // Delete the image file from the server
            if (file_exists("images/" . $image)) {
                unlink("images/" . $image);
            }
            // Redirect to home.php after successful deletion
            header("Location: home.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "No recipe found with the given ID.";
    }
} else {
    echo "Invalid request.";
}
?>
