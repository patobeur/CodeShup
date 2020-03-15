<?php 
Class User{
    use hydratationAndCream;
    private $_login;
    private $_mail;
    private $_token;

    public function __construct($donnees=['login' => 'testlogin','mail' => 'testmail']){
        $this->hydrate_index($donnees);
        $this->_token= 'totoro'; // generate_token();
        print_airB($this,'Class');
    }
    public function get_Func_Name(){
        print_airB(__FUNCTION__);
    }
    public function set_Login($paquet)  { $this->_login   = $paquet;}
    public function set_Mail($paquet)   { $this->_mail    = $paquet;}
    public function set_Token($paquet)  { $this->_token   = $paquet;}
    public function get_Login()         { return $this->_login;     }
    public function get_Mail()          { return $this->_mail;      }
    public function get_Token()         { return $this->_token;     }
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