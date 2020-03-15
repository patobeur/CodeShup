<?php
    if (isset($_GET['kill'])) header("Location:deco.php" );
    Define('DEBUG' , true);
    
    require_once('core/session.php');
    require_once('core/toolbox.php');

    $Page = new Page();
    $Page->do_affichelapagehtml();
    
    $User = new User(['login' => 'patobeur','mail' => 'patobeur@patobeur.link']);

    DEBUG ? print_airB($_SESSION,'CMS SESSION') : '';
    
?>