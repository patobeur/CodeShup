<?php
// ConTroller principal > Controller 

    if (isset($_GET['kill'])) header("Location:deco.php" );
    
    require_once('core/ini/session.php');
    require_once('core/toolbox.php');

    // $Db = new Db();
    // $User = new User();
    // $Page = new Page($User,$Db);
    $Page = new Page();
    $Page->do_affichelapagehtml();
    
    
    Define('DEBUG' , true); // ca ne marche pas dans definition.php ???
    DEBUG ? print_airB($_SESSION,'CMS SESSION') : '';
    // tuto : https://codeshack.io/super-fast-php-mysql-database-class/
?>