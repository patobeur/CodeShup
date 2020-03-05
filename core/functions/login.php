<?php 
    $login = new Login();
    if (!empty($_POST)) {
        if (!empty($_POST['login']) && !empty($_POST['password'])) {
            $login->get_Func_Name();
        }
    }
?>