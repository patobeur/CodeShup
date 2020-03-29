<?php
// ConTroller > Login

    if ($_POST) {
        $login_Err['errors'] = [];
        echo '<br><br><br>------------> POST NOT EMPTY';
        if (!empty($_POST['login']) && !empty($_POST['password']))
        {
            $donnees = [
                "email" => get_clean($_POST['login']),
                "passwrd" => md5(get_clean($_POST['password']))
            ];      
            if (!filter_var(get_clean($donnees['email']), FILTER_VALIDATE_EMAIL))
            {
                $login_Err['errors']['mail'][] = 'mail non valide';
            }
            else 
            {
                $this->_Bdd  = new Db();
                if ($this->_Bdd->is_exist_user($donnees)){
                    $_SESSION['user']['statut'] = 'logged';
                }
                

                // $this->_User = new User();
                // print_airB($this->_User,'kkkkkkkkk',1);
                // print_airB($this->_Bdd->get_fetchall_from('z_user'),'liste des users:');

            }


    




            // if(!empty($this->User)) 
            // {
            //     print_airB($this->User,'111111111111111111111111',1);
            //     $this->User::set_UserLoginDatas($donnees);
            // }
            // else{
            //     print_airB('n\'existe pas','user');

            // }
            // if(empty($this->_Bdd))
            // {
            //     print_airB('n\'existe pas','bdd');
            // }
        }
        else
        {
            $login_Err['errors'] = [
                'passwrd' => 'vide',
                'mail'    => 'vide'
            ];
        }
        $_SESSION['cms']['post'] = $_POST;
    }
?>