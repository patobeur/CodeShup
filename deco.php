<?php 
    session_start();
    session_destroy();
    header('location:'.dirname($_SERVER['PHP_SELF']).'/');
?>