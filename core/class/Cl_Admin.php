<?php
// ini_set('display_errors',1);
// Class Page
class Admin{
    public $_flash_time;
    public $_replace_in_vue = [
        'admin' => 
        [
            'titre' => 'Page Admin'
        ]
    ];
    public $_templatefile = '../'.AAINVUE.'admin'.AAEXTPHP;
    public $_pagehtml;
    public $_current_page;
    private $_DbAdmin;
    
    public function __construct($timer,$vue){
        $this->_flash_time = $timer;
        $this->_current_page = $vue;
        // $this->_pagehtml =  $this->get_vuefile('admin');
        $this->_DbAdmin = new Db();
        // $this->set_vuefile('admin');
        // $this->set_replace_vue($this->_replace_in_vue[$vue],$vue);
    }

    public function set_vuefile($vue,$rep=null){
        // $filevue = '../'.AAINVUE.$vue.AAEXTPHP;
        $filevue = 'view/_in_'.$vue.AAEXTPHP;
        if (file_exists($filevue))
        {
            $this->_pagehtml = file_get_contents($filevue);
        }
    }
    public function get_vuefile($vue,$rep=null){
        //$filevue = '../'.AAINVUE.$vue.AAEXTPHP;
        $filevue = 'view/_in_'.$vue.AAEXTPHP;
        if (file_exists($filevue))
        {
            // print_airB($filevue,' Aexiste dans '.__FUNCTION__);
            return file_get_contents($filevue);
        }
    }
    public function get_Pagehtml_templatefile(){
        if (file_exists($this->_templatefile))
        {
            return file_get_contents($this->_templatefile);
        }
    }
    // SETTER
    public function set_replace_vue($replace_in_vue,$vue){
        $this->_pagehtml = $this->get_vuefile($vue);
        $this->_replace_in_vue[$vue] = $replace_in_vue;
        if($this->_replace_in_vue[$vue])
        {   
            // print_airB($this->_replace_in_vue,'B replaceTag');
            foreach($this->_replace_in_vue[$vue] as $key => $value)
            {
                if($this->_pagehtml = str_replace("{{".$key."}}",$value,$this->_pagehtml))
                {
                    // print_airB($value,'dans '.__FUNCTION__.' {{'.$key.'}}  remplacé par ');
                };
            }
        }
    }
    public function set_replace_in_vue($replace_in_vue,$vue){
        //print_airB($replace_in_vue,'fff');
        // echo 'coucou';
        $this->_replace_in_vue[$vue] = $replace_in_vue;
        // $lavue = $this->get_vuefile($vue);

        if(!empty($this->_replace_in_vue[$vue]) AND !empty($vue))
        {   
            $lavue = $this->get_vuefile($vue);
            // print_airB($vue);
            // print_airB($this->_replace_in_vue,'B replaceTag');
            foreach($this->_replace_in_vue[$vue] as $key => $value)
            {
                
                if ($lavue = str_replace("{{".$key."}}",$value,$lavue))
                {
                    //print_airB($value,'dans '.$vue.'-'.__FUNCTION__.' {{'.$key.'}}  remplacé par ');
                };
            }
            
            if($this->_pagehtml = str_replace("{{vue"."}}",$lavue,$this->_pagehtml))
            {
                // print_airB($vue,'dans '.__FUNCTION__.' {{'.$vue.'vue}}  remplacé par la vue ');
            };
        }
        // print_airB($replace_in_vue,'existe dans '.__FUNCTION__);
        // print_airB($replace_in_vue,'existe dans '.__FUNCTION__);
    }




    // do
    public function do_affichelapagehtml(){
        echo $this->_pagehtml;
    }
    // do
    public function get_replace_in_vue(){
        if (!empty($this->_replace_in_vue[$this->_current_page]))
        {
            return $this->_replace_in_vue[$this->_current_page];
        }
    }
    // --------------------------------------------------------------------------------


    // DB    
    public function get_articles(){
        return $this->_DbAdmin->get_articles();
    }
    public function actions(){
        return $this->_DbAdmin->actions();
    }
    public function actions2(){
        return $this->_DbAdmin->actions2();
    }
    public function actions3($datas){
        return $this->_DbAdmin->actions3($datas);
    }
    public function actionsFinal($user_id,$req){
        return $this->_DbAdmin->actionsFinal($user_id,$req);
    }
    public function actions5($user_id,$req){
        return $this->_DbAdmin->actionsFinal($user_id,$req);
    }
    public function insert_actions($id,$req){
        return $this->_DbAdmin->insert_actions($id,$req);
    }

    public function get_profils(){
        return $this->_DbAdmin->get_profils();
    }
    public function get_users(){
        return $this->_DbAdmin->get_users();
    }




}
?>