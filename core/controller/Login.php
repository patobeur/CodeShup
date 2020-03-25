<?php
    if ($_POST) {
        echo '<br><br><br>----------------------------------post';
        if (!empty($_POST['mail']) && !empty($_POST['passwrd']))
        {
            $donnees = [   
                "mail" => get_clean($_POST['mail']),
                "passwrd" => get_clean($_POST['passwrd'])
            ];
            User::set_UserLoginDatas($donnees);



            // echo User::get_Token();
            // $account = Db::query('SELECT * FROM z_user WHERE email != ? AND passwrd = ?', '','' )->fetchArray();
            // print_airB($account);
        }
        // else
        // {
            
        // }
        $_SESSION['cms']['post'] = $_POST;
    }
?>