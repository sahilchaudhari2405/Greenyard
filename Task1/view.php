<?php
session_start();
if (isset($_SESSION['loggedUserName'])) {
    $loggedUser = $_SESSION['loggedUserName'];
}

include 'config.php';
$connectionString = "host=" . $config['DB_HOST'] . " port =5432 dbname=" . $config['DB_DATABASE'] . " user=" . $config['DB_USERNAME'] . " password=" . $config['DB_PASSWORD'];
$conn = pg_connect($connectionString);

if (!$conn) {
    echo 'something went wrong!';
    exit();
}

$imgId = $_GET['data'];
$_SESSION['imgId'] = $imgId;
$query = "Select * from ImageData where imageId='$imgId'";
$result = pg_query($conn, $query);
$row = pg_fetch_row($result);
$name = $row[4]
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.1">
    <title><?php $name; ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>

</head>

<body>
    <?php
    echo '  <div class="container">
        <div class="navigation-bar">
            <div id="navigation-container">
                <a href="#" id="logo"><img src="images/logo.png" width="100px" alt="" srcset=""></a>
                <ul>
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="#">Shop</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#"><i class="fa fa-user-circle" aria-hidden="true"><span href="logout.php" id="displayUserName">' . $_SESSION["loggedUserName"] . '</span></i></a></li>
                </ul>
            </div>
        </div>';
    ?>

    <?php
    $imgId = $_GET['data'];
    $_SESSION['imgId'] = $imgId;
    $query = "Select * from ImageData where imageId='$imgId'";
    $result = pg_query($conn, $query);
    $row = pg_fetch_all($result);


    for ($i = 0; $i < count($row); $i++) {
        $imageId = $row[$i]['imageid'];
        $imageTitle = $row[$i]['imagetitle'];
        $imageSrc = $row[$i]['imagename'];
        $imageDescription = $row[$i]['imagedescription'];
        $imageAuthor = $row[$i]['imageauthor'];
        echo '<div class="row">
            <div class="imageContainer col-lg-6">
                <img src="uploads/' . $imageSrc . '" alt="' . $imageTitle . '" srcset="">
                <h2>' . $imageTitle . '</h2>
                <h5>Author: ' . $imageAuthor . '</h5>
                <a href="download.php" id="download-button">Download PDF</a>
            </div>
            <div class="description col-lg-6">
                <h3>About:</h3>
                <p>
                ' . $imageDescription . '
                </p>
            </div>
        </div>';
    }
    ?>

    </div>
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