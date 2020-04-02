<?php 
Class Template
{
    // use hydratationAndCream;
    private $_template;
    //         

    public function __construct($template) 
    {
        // if (!empty($template)) { $this->hydrate_array($template); }
        $this->_template = $this->set_Template($template);
    }
    // basic setter
    private function set_Template($paquet)       { $this->_template = $paquet;}
    // basic getter
    private function get_Template()              { return $this->_template;   }

    // temporary tools getter PUBLIC
    public function get_UserFiche() { 
        return [
            'template'      => $this->get_Template(),
        ];
    }
}







?>