<?php
session_start();
try {
    if(isset($_SESSION['loggedUserName']))
    $loggedUser = $_SESSION['loggedUserName'];
    else header('Location: login.php');
}
catch (Exception $e){
    header('Location: login.php');
}


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
    <title>Explore Nature!</title>

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
    <div class="container">
        <div class="navigation-bar">
            <div id="navigation-container">
                <a href="#" id="logo"><img src="images/logo.png" width="100px" alt="" srcset=""></a>
                <ul>
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="#">Shop</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">About us</a></li>
                    <li>
                        <a href="">
                            <i class="fa fa-user-circle" aria-hidden="true">
                                <span data-toggle="tooltip" data-placement="bottom" title="Logout" href="logout.php" id="displayUserName"><?php echo $_SESSION["loggedUserName"]; ?>
                                </span>
                            </i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    <!-- Branding Section -->
    <div class="branding">
        <div class="company-title">
            <h1>Delivering <span>Plants,</span> <br>
                Delivering <span>Happiness!</span>
            </h1>
            <a class="upload-button" href="upload.php">Upload New</a>
        </div>
        <div class="plant-image">
            <div class="img">
                <img src="images/plant.png" alt="">
            </div>
        </div>
    </div>

    <div class="explore-section">
        <div class="image-container">
            <div class="container mt-5">
                <div class="row">
                    <div class="card-deck">
                        <?php
                        for ($i = 0; $i < count($row); $i++) {
                            $imageId = $row[$i]['imageid'];
                            $imageTitle = $row[$i]['imagetitle'];
                            $imageSrc = $row[$i]['imagename'];
                            $imageDescription = $row[$i]['imagedescription'];
                            $imageAuthor = $row[$i]['imageauthor'];
                            echo '
                            <div id="' . $imageId . '" class="card mb-3" style="min-width: 14rem; max-width: 14rem;" onclick="viewCard()">
                                <img class="card-img-top" style="min-width: 14rem; max-width: 14rem; height: 14rem;" src="uploads/' . $imageSrc . '" alt="Card image cap">
                                <div class="card-body">
                                    <h5 class="card-title">' . $imageTitle . '</h5>
                                    <p class="card-text">Author: ' . $imageAuthor . '</p>
                                </div>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Jquery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script>

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
        $(document).ready(function() {
            $(".card").bind("click", function() {
                viewCard();
            });
        });

        function viewCard() {
            $('.card').click(function() {
                var cardId = $(this).attr("id");
                console.log(cardId);
                set_url_data('view.php', cardId);
            });
        }

        function set_url_data(go_to_url, data) {
            new_url = go_to_url + '?data=' + data;
            window.open(new_url);
        }

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