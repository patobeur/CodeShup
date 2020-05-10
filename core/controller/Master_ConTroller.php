<?php
    // ConTroller principal > Controller
    ini_set('display_errors',1);

    $timer = microtime(true);
    if (isset($_GET['kill'])) header("Location:deco.php" );

    include('core/ini/definitions.php'); // MonkeyBusiness

    include(AAINI.'bdd.php');   
    include(AAINI.'session.php');
    require_once('core/cookie.php');
    require_once('core/toolbox.php');

    $Page = new Page($timer);
    $Page->do_affichelapagehtml();
    
    
    // DEBUG
    (!empty($_SESSION['panier'])             ) ? print_airB($_SESSION['panier'],     'panier') : '';
    (!empty($_SESSION['cms'])       AND DEBUG) ? print_airB($_SESSION['cms'],       'CMS SESSION') : '';
    (!empty($_SESSION['user'])      AND DEBUG) ? print_airB($_SESSION['user'],      'USER SESSION') : '';
    (!empty($_SESSION['profil'])    AND DEBUG) ? print_airB($_SESSION['profil'],    'PROFIL SESSION') : '';
    (!empty($_SESSION['token'])              ) ? print_airB($_SESSION['token'],     'TOKEN SESSION') : '';
    
?>