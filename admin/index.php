<?php 
  session_start();
  include('../core/ini/definitions.php'); // MonkeyBusiness
  if (!empty($_SESSION['profil']['ruleset']) 
      AND $_SESSION['profil']['ruleset'] === 'admin' 
      AND $_SESSION['profil']['rule_id'] === (string) 1)
  {
    require_once(ADDCORE.'controller/Admin_Controller.php');
  }
  else {
    session_destroy();
    die();
  }
?>