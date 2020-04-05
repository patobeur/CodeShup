<?php 
  session_start();
  if (empty($_SESSION['profil']['ruleset']) AND $_SESSION['profil']['ruleset'] != 'admin' AND $_SESSION['profil']['rule_id'] != 1 )
  {
    header('location:'.dirname($_SERVER['PHP_SELF']).'../');
  }
	require_once('../core/Controller/Admin_Controller.php');
?>
