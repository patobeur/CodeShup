<?php

class User{

    public $_user = [];
	private $_Originum=0;
    
    public function __construct(){
        $this->_user['statut'] = 'visitor';
        // $this->hydrate_info_var(array('_auteur','_date','_time_stamp','_logosrc'));
    }
}
?>