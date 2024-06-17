<?php
// Include the database configuration file
include('db/config.php');

// Check if user is logged in using cookies
if (!isset($_COOKIE['email'])) {
    header("Location: index.php");
    exit();
}

// Fetch all recipes with their user details
$query = "
    SELECT 
        r.recipie_id, 
        r.recipie_name, 
        r.recipie_description, 
        r.recipie_image, 
        r.recipie_reviews,
        u.user_name 
    FROM 
        recipies r 
    JOIN 
        users u 
    ON 
        r.user_id = u.user_id
";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Recipe Panel</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 1200px;
            margin-top: 50px;
        }
        .recipe-card {
            margin-bottom: 20px;
        }
        .recipe-image {
            max-width: 100%;
            height: auto;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card-title {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .card-text {
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }
        .card-link {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4 text-center">Live Recipe Panel</h2>
        <div class="row">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $recipie_id = $row['recipie_id'];
                    $recipie_name = $row['recipie_name'];
                    $recipie_description = $row['recipie_description'];
                    $recipie_image = $row['recipie_image'];
                    $recipie_reviews = $row['recipie_reviews'];
                    $user_name = $row['user_name'];
                    ?>
                    <div class="col-md-4 recipe-card">
                        <div class="card">
                            <a href="view_recipie.php?id=<?php echo $recipie_id; ?>" class="card-link">
                                <img src="images/<?php echo $recipie_image; ?>" class="card-img-top recipe-image" alt="<?php echo $recipie_name; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $recipie_name; ?></h5>
                                    <p class="card-text"><?php echo $recipie_description; ?></p>
                                    <p class="card-text"><small class="text-muted">By <?php echo $user_name; ?></small></p>
                                    <p class="card-text"><small class="text-muted">Views: <?php echo $recipie_reviews; ?></small></p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No recipes found.</p>";
            }
            ?>
        </div>
        <div class="text-center mt-4">
            <a href="home.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
</body>
</html>
