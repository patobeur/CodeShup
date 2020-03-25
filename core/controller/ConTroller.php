<?php
    if (isset($_GET['kill'])) header("Location:deco.php" );
    Define('DEBUG' , true);
    
    require_once('core/session.php');
    require_once('core/toolbox.php');

    $Db = new Db();
    $User = new User();
    $Page = new Page();
    $Page->do_affichelapagehtml();
    // echo $_Session['cms']['user']['statut'];
    
    print_airB($User->get_UserFiche(),'fiche user',1);
    DEBUG ? print_airB($_SESSION,'CMS SESSION') : '';
    // tuto : https://codeshack.io/super-fast-php-mysql-database-class/
?>