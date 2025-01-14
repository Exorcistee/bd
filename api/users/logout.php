<?php
session_start();
session_destroy();  
header("Location: /bd/login.php");
exit;
?>