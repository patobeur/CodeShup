<?php
    // Utilitaires / toolbox
    // a remettre dans les class ou pas
    /**
     * autouploader de class / stacking class
     */
    function chargerClasse($classe) {
        if(file_exists(AACLASSE.$classe . AAEXTPHP)){
            $file = AACLASSE.$classe . AAEXTPHP;
                require_once $file;
                $_SESSION['cms']['autoload'][] = "New Class $classe"."() chargée.";
        }
        else{
            $_SESSION['cms']['errors'][] = "New Class $classe"."() n'est pas chargée correctement.";
            die();
        }
    }
    spl_autoload_register('chargerClasse');

    /**
     * generateur md5 generator
     */
    function chiffrage($paquet){
        return md5($paquet);
    }
    /**
     * return a clean value for html echo ?
     */
    function get_clean($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    /**
     * clean print_r function
     * @param $paquet array give me something to print like a string
     * @param $title string give me something print like a string or integer
     */
    function print_html($paquet,$title=''){
        if (DEBUG){
            print('<hr>');
            ($title!='') ? print($title.':') : print('HTML:');
            echo($paquet);
            print('');
        }
    }
    function eco($paquet){
        echo $paquet;
    }
    /**
     * clean print_r function
     * @param $paquet array give me something to print like a string
     * @param $title string give me something print like a string or integer
     */
    function print_air($paquet,$title='',$top=null){
        if (DEBUG){
            $br = (!empty($top)) ? "<br><br><br><br><br><br>" : "";
            print('<hr>'.$br.'<pre>');
            // echo "function(".__FUNCTION__.")<br>";
            ($title!='') ? print($title.': ') : print('print_r: ');
            print_r($paquet);
            print('</pre>');
        }
    }
    /**
     * clean print_r function
     * @param $paquet array give me something to print like a string
     * @param $title string give me something print like a string or integer
     */
    function print_airB($paquet,$title='',$top=null){
        if (DEBUG){
            $br = (!empty($top)) ? "<br><br><br><br><br><br>" : "";
            print('<hr>'.$br.'<pre>');
            // echo "function(".__FUNCTION__.")<br>";
            ($title!='') ? print($title.': ') : print('print_r: ');
            print_r($paquet);
            print('</pre>');
        }
    }
    /**
     * clean var_dump() function
     * @param $paquet array give me something to dump like a array
     * @param $title string give me something print like a string or integer
     */
    function var_bump($paquet,$title=''){
        if (DEBUG){
            print('<pre><hr>');
            ($title!='') ? print($title.': ') : print('var_dump: ');
            var_dump($paquet);
            print('</pre>');
        }
    }
    function get_Ip(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    function isitrightvar($email,$type){
        switch ($type) {
            case "mail":
                if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $email)) {
                    return TRUE;
                }
        }
    }
    function generateToken()
    {
        return md5(rand(1, 10) . microtime());
    }
?>