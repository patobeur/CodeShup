<?php
	if (isset($_GET['kill'])) header("Location:deco.php" );
	require_once('core/toolbox.php');
    $Page = new Page();
    $Page->do_affichelapagehtml();
?>