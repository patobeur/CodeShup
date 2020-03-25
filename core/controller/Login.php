<?php
    if ($_POST) {
        echo '<br><br><br>----------------------------------> POST NOT EMPTY';
        if (!empty($_POST['mail']) && !empty($_POST['passwrd']))
        {
            echo '<br><br><br>----------------------------------> mail && passwrd NOT EMPTY';
            $donnees = [   
                "mail" => get_clean($_POST['mail']),
                "passwrd" => get_clean($_POST['passwrd'])
            ];
            if(!empty($User)) 
            {
                print_airB($donnees,'111111111111111111111111',1);
                $User::set_UserLoginDatas($donnees);
            }
            $_SESSION['cms']['user']['statut'] = 'logging';
            $_SESSION['cms']['user']['try'] += 1;
        }
        // else
        // {
            

        // }
        $_SESSION['cms']['post'] = $_POST;
    }
?>