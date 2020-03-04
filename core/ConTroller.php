<?php
    if (isset($_GET['kill'])) header("Location:deco.php" );
    define('DEBUG_A', false); //get_Pageaouvriratouslescoups()
    define('DEBUG_B', true); //get_Pageaouvriratouslescoups()
    define('DEBUG_DIE', true); //die
	require_once('core/toolbox.php');
    $Page = new Page();
    $Page->do_affichelapagehtml();
?>