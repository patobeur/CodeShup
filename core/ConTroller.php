<?php
    if (isset($_GET['kill'])) header("Location:deco.php" );

    
    require_once('core/session.php');

    require_once('core/toolbox.php');

    // print_airB($_SESSION['cms'],'avant');
    $Page = new Page();
    // print_airB($_SESSION['cms'],'apres Page');
    $Page->do_affichelapagehtml();
    print_airB($_SESSION['cms'],'après do_affichelapagehtml');
?>