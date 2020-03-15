<?php 
    if ($_POST) {
        if (!empty($_POST['login']) && !empty($_POST['password'])) {
            $donnees = [   
                "login" => get_clean($_POST['login']),
                "password" => get_clean($_POST['password'])
            ];
            $login = new Login($donnees);
            $login->get_Func_Name(
                get_clean($_POST['login']),
                get_clean($_POST['password'])
            );
        }
        else{
            
        }
        $_SESSION['cms']['post'] = $_POST;
    }
?>