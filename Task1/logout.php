<?php 
session_start();
session_destroy();
session_unset();
echo 'Logout';
?>