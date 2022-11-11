<?php
session_start();

include 'config.php';
$connectionString = "host=" . $config['DB_HOST'] . " port =5432 dbname=" . $config['DB_DATABASE'] . " user=" . $config['DB_USERNAME'] . " password=" . $config['DB_PASSWORD'];
$conn = pg_connect($connectionString);
$myfile = fopen("logfile.txt", "a");
fwrite($myfile, "inside process \n");

if (!$conn) {
    echo 'something went wrong!';
    exit();
}

/*-----------------------------------------------
            Trigger File Upload
-----------------------------------------------*/
if(isset($_POST['upload'])) {
    uploadNewImageData();
}

/*-----------------------------------------------
        Check type of action to be performed 
-----------------------------------------------*/
if (isset($_POST['action'])) {
    fwrite($myfile, "inside action \n");
    if ($_POST['action'] == 'register') {
        fwrite($myfile, "inside register \n");
        register();
    } else if ($_POST['action'] == 'login') {
        fwrite($myfile, "inside login \n");
        login();
    }else if($_POST['action'] == 'upload'){
        fwrite($myfile, "inside upload image \n");
        uploadNewImageData();
    } else if($_POST['action'] == 'resetPassword'){
        fwrite($myfile, "inside reset password \n");
        resetPassword();
    } else if($_POST['action'] == 'setNewPassword'){
        fwrite($myfile, "inside set new password \n");
        setNewPassword();
    }
}

/*-----------------------------------------------
                Register User
-----------------------------------------------*/
function register()
{
    global $conn, $myfile;
    $username = $_POST['name'];
    $useremail = $_POST['email'];
    $userpass = $_POST['password'];

    $query = "SELECT * FROM UserInfo WHERE email='$useremail'";
    $result = pg_query($conn, $query);
    if (pg_num_rows($result) > 0) {
        echo 'User already exists! Kindly login.';
        $txt = "User already exists!\n";
        fwrite($myfile, $txt);
        exit();
    } else {
        try{
            $userpass = md5($userpass);
            $query = "Insert into UserInfo(username,email,userpassword) values('$username','$useremail','$userpass')";
            $result = pg_query($conn, $query);
            echo 'Registration Successful';
        }catch(Exception $e){
            echo 'Error!';
        }
    }
}


/*-----------------------------------------------
                Login User
-----------------------------------------------*/
function login()
{
    global $myfile, $conn;
    fwrite($myfile,"Inside user login\n");
    $useremail = $_POST['email'];
    $userpass = md5($_POST['password']);
    $query = "Select * from UserInfo where email='$useremail' and userpassword='$userpass'";
    fwrite($myfile, $query."\n");
    $result = pg_query($conn, $query);
    fwrite($myfile, pg_num_rows($result)." rows");
    if (pg_num_rows($result) == 1) {
        $row = pg_fetch_row($result);
        $loggedUserName = $row[1];
        $loggedUserEmail = $row[2];
        $loggedUserPassword = $row[3];
        $_SESSION['loggedUserName'] = $loggedUserName;
        $_SESSION['loggedUserEmail'] = $loggedUserEmail;
        $_SESSION['loggedUserPassword'] = $loggedUserPassword;
        fwrite($myfile, "Login Successfull\n");
        echo 'Login Successfull ' . $loggedUserName;
    } else echo 'Password Incorrect';
}


/*-----------------------------------------------
            Process Upload file 
-----------------------------------------------*/
function uploadNewImageData()
{
    global $conn,$myfile;
    // fwrite($myfile,$_FILES['file']);
    $file = $_FILES['file'];

    $imgTitle=trim($_POST['imageTitle']);
    $imgDesc=pg_escape_string(trim($_POST['imageDescription']));
    $imgAuthor=$_SESSION['loggedUserName'];
    $fileName = $_FILES['file']['name'];
    $fileType = $_FILES['file']['type'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileTempLocation = $_FILES['file']['tmp_name'];
    echo "File name is: " . $fileName . "<br>\n"."File type is: " . $fileType . "<br>\n"."File Size is: " . ($fileSize / 1000) . " kb";

    // get the file extension
    $fileExt = explode('.', $fileName);
    // print_r($fileExt);
    $fileExtension = strtolower(end($fileExt));

    // list of file types which can be uploaded
    $allowedExtension = array('jpg', 'jpeg', 'png');

    // check if the correct file type is selected
    if (in_array($fileExtension, $allowedExtension)) {
        if ($fileError === 0) {
            if ($fileSize < 500000) {
                $fileUniqueName = "$fileExt[0]." . $fileExtension;
                echo $fileUniqueName;
                $fileDestinantion = 'uploads/' . $fileUniqueName;
                move_uploaded_file($fileTempLocation, $fileDestinantion);
                $query = "insert into ImageData(imageName,ImageDescription,imageAuthor,imageTitle) values('$fileUniqueName','{$imgDesc}','$imgAuthor','$imgTitle')";
                $run = pg_query($conn, $query);
                if ($run) {
                    header("Location: dashboard.php");
                }
                else echo 'error';
            } else echo 'File size is larger than 500kb';
        }
    } else echo 'Selected file type not allowed!';
}

/*-----------------------------------------------
                Password Reset
-----------------------------------------------*/
function resetPassword(){
    global $conn,$myfile;
    $userEmail=$_POST['email'];
    $query= "Select * from UserInfo where email='$userEmail'";
    $result=pg_query($conn,$query);
    if(pg_num_rows($result)==1){
        $_SESSION['resetEmail']=pg_fetch_row($result)[2];
        fwrite($myfile,$_SESSION['resetEmail']);
        echo 'Reset authorised';
    }else echo 'Reset rejected';
}

/*-----------------------------------------------
                Set New Password
-----------------------------------------------*/
function setNewPassword(){
    global $conn,$myfile;
    $userEmail=$_SESSION['resetEmail'];
    $userPass=md5($_POST['password']);
    $query= "update UserInfo set userpassword='$userPass' where email='$userEmail'";
    fwrite($myfile,$query);
    $result=pg_query($conn,$query);
    if($result){
        echo 'Reset Successfull';
    }else echo 'Reset rejected';
}


?>
