<?php
session_start();

$loggedUser = $_SESSION['loggedUserName'];

include 'config.php';
$connectionString = "host=" . $config['DB_HOST'] . " port =5432 dbname=" . $config['DB_DATABASE'] . " user=" . $config['DB_USERNAME'] . " password=" . $config['DB_PASSWORD'];
$conn = pg_connect($connectionString);

if (!$conn) {
    echo 'something went wrong!';
    exit();
}
$query = "Select * from ImageData";
$result = pg_query($conn, $query);
$row = pg_fetch_all($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.1">
    <title>Upload Plant</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    echo '  
    <div class="container">
        <div class="navigation-bar">
            <div id="navigation-container">
                <a href="#" id="logo"><img src="images/logo.png" width="100px" alt="" srcset=""></a>
                <ul>
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="#">Shop</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#"><i class="fa fa-user-circle" aria-hidden="true"><span href="logout.php"
                                    id="displayUserName">
                                     ' . $_SESSION["loggedUserName"] . '
                                </span></i></a></li>
                </ul>
            </div>
        </div>
        ';
    ?>
    <!-- Upload Section -->
    <div class="upload-section container">
        <div class="row">
            <h1 class="col-lg-12">Upload New Plant Here</h1>
            <form action="process.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="imageTitle">Title</label>
                    <input type="text" class="form-control" name="imageTitle" id="imageTitle" placeholder="Enter Title">
                </div>
                <div class="form-group">
                    <label for="imageDescription">Description</label>
                    <textarea rows="5" cols="60" type="text" class="form-control" name="imageDescription" id="imageDescription" placeholder="Description..."></textarea>
                </div>

                <div class="input-group mb-3">
                    <input type="file" class="form-control" 
                    name="file" id="file">
                </div>
                <button type="submit" name="upload" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    </div>

    <!-- Jquery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script>

        $('#displayUserName').click(function() {
            $.ajax({
                url: 'logout.php',
                success: function(response) {
                    alert(response);
                    window.location = 'login.php';
                }
            });
        });
    </script>
</body>

</html>
