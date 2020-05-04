<?php 
Class User
{
    // use hydratationAndCream;
    private $_mail;
    private $_passwrd;
    private $_token;
    private $_statut;
    private $_connected;
    //         

    public function __construct($donnees=['mail' => null,'passwrd' => null])
    {
        print_airB('wtf user');
        // if (!empty($donnees)) { $this->hydrate_index($donnees); }
        $this->_mail = "ko";
        $this->_passwrd = "ko";
        // $this->_token  = $this->set_Token(); // generate_token();
        $this->_statut = "visitor";
        $this->_connected = false;
    }
    // basic setter
    public function set_Mail($paquet)       { $this->_mail          = $paquet;}
    public function set_Passwrd($paquet)    { $this->_passwrd       = $paquet;}
    public function set_Token()             { $this->_token         = '';     }
    public function set_Statut($paquet)     { $this->_statut        = $paquet;}
    public function set_Connected($paquet)  { $this->_connected     = $paquet;}
    // basic getter
    public function get_Mail()              { return $this->_mail;            }
    public function get_Passwrd()           { return $this->_passwrd;         }
    public function get_Token()             { return $this->_token;           }
    public function get_Statut()            { return $this->_statut;          }
    public function get_Connected()         { return $this->_connected;       }

    // basic setter
    public function set_UserLoginDatas($donnees)
    { 
        $this->_mail    = $this->set_Mail($donnees['mail']);
        $this->_passwrd = $this->set_Passwrd($donnees['passwrd']);
        // print_airB(
        //     'mail : '.      (                  !empty($this->_mail)   ? 'ok' : 'ko'                  ).
        //     'passwrd : '.   (                  !empty($this->passwrd) ? 'ok' : 'ko'                  ),
        //     'test login'
        //     ,1
        // );
    }
    // temporary tools getter PUBLIC
    public function get_UserFiche() { 
        return [
            'mail'      => $this->get_Mail(),
            // 'passwrd'   => Md5($this->get_Passwrd()),
            'token'     => $this->get_Token(),
            'statut'    => $this->get_Statut(),
            'connected' => $this->get_Connected()
        ];
    }
}




?>