<?php 
Class User{
    use hydratationAndCream;
    public $_mail;
    public $_passwrd;
    public $_token;
    //         

    public function __construct($donnees=['mail' => null,'passwrd' => null]){
        // if (!empty($donnees)) {
        //     $this->hydrate_index($donnees);
        // }
        $this->_mail = "";
        $this->_passwrd = "";
        $this->_token  = "totoro"; // generate_token();
        $this->_statut = "visitor";
    }
    public function get_UserDatas(){
        return [ 
            "mail"    => $this->get_Mail(),
            "token"   => $this->get_Token()
        ];
    }
    public function set_UserLoginDatas($tableau) { 
        $this->_mail    = $tableau['mail'];
        $this->_passwrd = $tableau['passwrd'];
    }

    public function set_Mail($paquet)  { $this->_mail          = $paquet;}
    public function set_Passwrd($paquet){ $this->_passwrd       = $paquet;}
    public function set_Token($paquet)  { $this->_token         = $paquet;}

    public function get_Mail()          { return $this->_mail;            }
    public function get_Passwrd()       { return $this->_passwrd;         }
    public function get_Token()         { return $this->_token;           }
}







trait hydratationAndCream{
    public function hydratation($key,$value) {
        $method = 'set_'.ucfirst($key);
        if (method_exists(parent, $method)) {
            parent::$method($value);
        }
        else {
            $_SESSION['erreurs'][] = "la methode ".get_clean($method)." n'existe pas..";
        }
    }
    




























    // tests





    // HYDRATE ARRAY
    protected function hydrate_array($array){
        for($i = 0; $i < count($array); $i++){
            $method = 'set_'.ucfirst($array[$i]);
			if (method_exists(parent, $method)) {
				parent::$method($array[$i]);
			}
        }
    }
    // HYDRATE TABLEAUX INDEXES
    private function hydrate_index($index){
        foreach($index as $key => $values){
            $method = 'set_'.ucfirst($key);
			if (method_exists($this, $method)) {
				$this->$method($values);
			}
        }
    }

}
?>