<?php 
    if ($_POST) {
        if (!empty($_POST['login']) && !empty($_POST['password'])) {
            $donnees = [   
                "login" => get_clean($_POST['login']),
                "password" => get_clean($_POST['password'])
            ];
            $login = new User($donnees);
            $login->get_Func_Name(
                get_clean($_POST['login']),
                get_clean($_POST['password'])
            );
            $account = $db->query('SELECT * FROM z_user WHERE email != ? AND passwrd = ?', $this->_login, $this->_mail)->fetchArray();
            echo $account['name'];
        }
        else{
            
        }
        $_SESSION['cms']['post'] = $_POST;
    }
?>