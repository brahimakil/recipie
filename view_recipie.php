<?php
// Include the database configuration file
include('db/config.php');

// Check if user is logged in using cookies
if (!isset($_COOKIE['email'])) {
    header("Location: index.php");
    exit();
}

// Retrieve user_id from cookies
$user_id = $_COOKIE['user_id'];

// Check if the recipe ID is provided in the URL
if (isset($_GET['id'])) {
    $recipie_id = mysqli_real_escape_string($con, $_GET['id']);

    // Increment the recipe reviews count
    $update_reviews_query = "UPDATE recipies SET recipie_reviews = recipie_reviews + 1 WHERE recipie_id = '$recipie_id'";
    mysqli_query($con, $update_reviews_query);

    // Fetch the recipe details from the database
    $query = "
    SELECT 
        r.recipie_name, 
        r.recipie_description, 
        r.recipie_image, 
        r.recipie_reviews,
        r.recipie_starred,
        c.category_name,
        u.user_name 
    FROM 
        recipies r 
    JOIN 
        users u 
    ON 
        r.user_id = u.user_id
    JOIN
        categories c
    ON
        r.category_id = c.category_id 
    WHERE 
        r.recipie_id = '$recipie_id'
";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $recipie_name = $row['recipie_name'];
        $recipie_description = $row['recipie_description'];
        $recipie_image = $row['recipie_image'];
        $recipie_reviews = $row['recipie_reviews'];
        $recipie_starred = $row['recipie_starred'];
        $user_name = $row['user_name'];
        $category_name = $row['category_name'];

        // Check if the user has already starred this recipe
        $interaction_query = "SELECT has_starred FROM user_recipe_interactions WHERE user_id = '$user_id' AND recipie_id = '$recipie_id'";
        $interaction_result = mysqli_query($con, $interaction_query);
        $has_starred = false;

        if ($interaction_result && mysqli_num_rows($interaction_result) > 0) {
            $interaction_row = mysqli_fetch_assoc($interaction_result);
            $has_starred = $interaction_row['has_starred'];
        }
    } else {
        echo "No recipe found with the given ID.";
        exit();
    }
} else {
    echo "No recipe ID provided.";
    exit();
}

// Handle starring the recipe
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['star'])) {
    if (!$has_starred) {
        // Increment the recipe starred count
        $update_starred_query = "UPDATE recipies SET recipie_starred = recipie_starred + 1 WHERE recipie_id = '$recipie_id'";
        mysqli_query($con, $update_starred_query);

        // Update the user_recipe_interactions table
        $interaction_update_query = "INSERT INTO user_recipe_interactions (user_id, recipie_id, has_starred) VALUES ('$user_id', '$recipie_id', TRUE)
                                    ON DUPLICATE KEY UPDATE has_starred = TRUE";
        mysqli_query($con, $interaction_update_query);

        // Refresh the page to update the star count and disable the button
        header("Location: view_recipie.php?id=$recipie_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recipe</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .recipe-image {
            max-width: 100%;
            height: auto;
        }
        .star-btn {
            cursor: pointer;
            font-size: 1.5rem;
            color: <?php echo $has_starred ? 'gold' : 'grey'; ?>;
        }
        .star-btn.disabled {
            pointer-events: none;
            color: gold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4"><?php echo $recipie_name; ?></h2>
        <p><strong>By:</strong> <?php echo $user_name; ?></p>
        <img src="images/<?php echo $recipie_image; ?>" class="recipe-image" alt="<?php echo $recipie_name; ?>">
        <p class="mt-4"><strong>Description:</strong> <?php echo nl2br($recipie_description); ?></p>
        <p><strong>Category:</strong> <?php echo $category_name; ?></p>
        <p><strong>Views:</strong> <?php echo $recipie_reviews; ?></p>
        <p><strong>Starred:</strong> <?php echo $recipie_starred; ?></p>
        <div class="text-center mt-4">
            <form method="post">
                <button type="submit" name="star" class="star-btn <?php echo $has_starred ? 'disabled' : ''; ?>">⭐</button>
            </form>
        </div>
        <div class="text-center mt-4">
            <a href="livepanel.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
</body>
</html>
