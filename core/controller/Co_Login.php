<?php
// ConTroller > Login

    $replace_in_vue = [
        'password' => '',
        'login'    => '',
        'Ilogin'    => ''
    ];
    $donnees = [
        'passwrd' => null,
        'email' => null
    ];
    if ($_POST)
    {

        if (!empty($_POST['password']))
        {
            $donnees['passwrd'] = md5(get_clean($_POST['password']));
        }
        else{
            $replace_in_vue['password'] = 'Il manque un mot de passe !';
        }
        
        if (!empty($_POST['login']))
        {
            if (  filter_var(get_clean($_POST['login']),FILTER_VALIDATE_EMAIL)  )
            {
                // $donnees['email'] = md5(get_clean($_POST['login']));
                $donnees['email'] = get_clean($_POST['login']);
                $replace_in_vue['login'] = '';
                $replace_in_vue['Ilogin'] = get_clean($_POST['login']);
            }
            else{
                $replace_in_vue['login'] = 'Email incorrect !';
            }
        }
        else{
            $replace_in_vue['login'] = 'Il manque un Email !';
        }

        if (!empty($donnees['email']) && !empty($donnees['passwrd']))
        {
                if ($this->_Db->is_exist_user($donnees)){
                    // REDIRECTION VERS INDEX
                    Page::set_Current_Page('index');
                    header('location:'.dirname($_SERVER['PHP_SELF']).'/?'.Page::get_Current_Page());
                }
        }
    }   
    Page::set_replace_in_vue($replace_in_vue);
?>