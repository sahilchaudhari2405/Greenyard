<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <style>
        .form-gap {
            padding-top: 70px;
        }

        .input-group {
            margin: 5px 0;
        }

        strong {
            color: black;
        }
    </style>
</head>

<body>
    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Create New Password?</h2>
                            <div class="panel-body">

                                <form action="" id="register-form" role="form" autocomplete="off" class="form" method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                            <input id="new-pass" name="new-password" placeholder="New password" class="form-control" type="password">
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                            <input id="conf-pass" name="re-new-password" placeholder="Retype New password" class="form-control" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" id="setNewPass" type="submit">
                                    Reset Password</button>
                                            </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script>
        $(document).ready(function() {
            $('#new-pass' && '#conf-pass').on('change', function() {
                var newPass = $('#new-pass').val();
                var confPass = $('#conf-pass').val();
                if (newPass !== confPass) alert('Passwords do not match');
                else if (newPass.length == 0 || confPass.length == 0) alert("New Password can't be empty!");
                else if (confPass.length < 8 && newPass.length < 8) alert("New Password must contain minimum 8 characters!");
                else {
     

                    $.ajax({
                        url: 'process.php',
                        type: 'post',
                        data: {
                            'action': "setNewPassword",
                            'password': confPass
                        },
                        success: function(response) {
                            console.log(response);
                            if (response == 'Reset Successfull') {
                                alert(response);
                                window.location = 'login.php';
                            } else alert("An Error has occured!");
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
