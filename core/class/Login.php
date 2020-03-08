<?php 
Class Login{
    use hydratationAndCream;
    private $_login;
    private $_mail;
    private $_token;

    public function __construct($donnees){
        print_air($donnees);
        $this->hydrate_index($donnees);
        $this->_token= 'totoro'; // generate_token();
    }
    public function get_Func_Name(){
        print_air('get_Func_Name');
    }
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