<?php
    if (isset($_GET['kill'])) header("Location:deco.php" );
    Define('DEBUG' , true);
    
    require_once('core/session.php');
    require_once('core/toolbox.php');

    $Db = new Db();
    $Page = new Page();
    $User = new User();
    $Page->do_affichelapagehtml();
    
    //$User = new User(['mail' => '','passwrd' => '']);

    DEBUG ? print_airB($_SESSION,'CMS SESSION') : '';
    // tuto : https://codeshack.io/super-fast-php-mysql-database-class/
?>