<?php
session_start();
session_destroy(); 
header("Location: /bd/index.php"); 
exit;
?>