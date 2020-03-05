<?php 
    if (!empty($_POST)) {
        if (!empty($_POST['login']) && !empty($_POST['password'])) {
            $données = [   
                "login" = get_clean($_POST['login']),
                "password" = get_clean($_POST['password'])
            ];
            $login = new Login($données);
            $login->get_Func_Name(
                get_clean($_POST['login']),
                get_clean($_POST['password'])
            );
        }
    }
?>