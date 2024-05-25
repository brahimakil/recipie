<?php

include "db/config.php";
if (isset($_POST['add'])) {
    header("Location: add.php");
    exit();
}

$select_query = "SELECT * FROM recipies";

$result = null;

if (isset($_POST['view_sausage'])) {
    $select_query = "SELECT * FROM recipies WHERE recipie_category = 'Sausage'";
} elseif (isset($_POST['view_all'])) {
    $select_query = "SELECT * FROM recipies";
} elseif (isset($_POST['view_breakfast'])) {
    $select_query = "SELECT * FROM recipies WHERE recipie_category = 'Breakfast'";
}


if ($select_query != "") {
    $result = mysqli_query($con, $select_query);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipie Saver</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-card {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
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
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            
        }

        .add-button-container button {
            width: 100%;
            max-width: 200px;
        }
        .title {
    margin-bottom: 1rem;
    font-weight: bold;
    font-size:20px;
}
.description {
    margin-bottom: 1.5rem;
    white-space: pre-wrap; /* This property ensures that whitespace is preserved and text wraps when necessary */
}
    </style>
</head>
<body>
    <form method="post">
        <div class="add-button-container">
            <button class="btn btn-primary" type="submit" name="add">Add</button>
            <button class="btn btn-primary" style="background-color:cadetblue;" type="submit" name="view_all"  >View All  </button>
            <button class="btn btn-primary" style="background-color: blueviolet;" type="submit" name="view_sausage"  >View Sausage</button>
            <button class="btn btn-primary" style="background-color:chartreuse;" type="submit" name="view_breakfast"  >View Breakfast</button>
        </div>
    </form>
    <br>
    <br>
   <div class="container">
        <div class="row">
            <?php
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['recipie_id'];
                    $title = $row['recipie_name'];
                    $description = $row['recipie_description'];
                    $image = $row['recipie_image'];
                    $category = $row['recipie_category'];
                    ?> 
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="custom-card">
                            <img src="images/<?php echo $image; ?>" alt="Image">
                            <div class="title"><?php echo $title; ?></div>
                            <div class="description"> <b style="font-size: 15px;">Description:</b>  <?php echo $description; ?></div>
                            <div class="category" > <b style="font-size: 13px;">Category:</b>  <?php echo $category; ?>  </div>
                            <div class="btn-container">
                                <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-primary btn-edit" name="edit">View and Edit</a>
                                <a href="delete.php?id=<?php echo $id; ?>" class="btn btn-danger" name="delete">Delete</a>
                            </div>
                        </div>
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