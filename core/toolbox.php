<?php

    // Utilitaires / toolbox
    // a remettre dans les class ou pas
    /**
     * autouploader de class / stacking class
     */
    function chargerClasse($classe) {
        //exeptions
        // print_airB($_SERVER['PHP_SELF'],$classe);

        switch ($_SERVER['PHP_SELF']){
            case '/git/github/cms_poo/admin/index.php':
                $rooter = '../';
            break;

            default:
                $rooter = '';
            break;
        }
        if(file_exists($rooter.AACLASSE.$classe . AAEXTPHP)){
            $file = $rooter.AACLASSE.$classe . AAEXTPHP;
                require_once $file;
                $_SESSION['cms']['autoload'][] = "New Class $classe"."() chargée.";
        }
        else{
            $_SESSION['cms']['errors'][] = "New Class $classe"."() n'est pas chargée correctement.";
            die('la classe ?');
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
    function GetLocals()
    {
        $indicesServer = 
        [
            'PHP_SELF',
            'argv',
            'argc',
            'GATEWAY_INTERFACE',
            'SERVER_ADDR',
            'SERVER_NAME',
            'SERVER_SOFTWARE',
            'SERVER_PROTOCOL',
            'REQUEST_METHOD',
            'REQUEST_TIME',
            'REQUEST_TIME_FLOAT',
            'QUERY_STRING',
            'DOCUMENT_ROOT',
            'HTTP_ACCEPT',
            'HTTP_ACCEPT_CHARSET',
            'HTTP_ACCEPT_ENCODING',
            'HTTP_ACCEPT_LANGUAGE',
            'HTTP_CONNECTION',
            'HTTP_HOST',
            'HTTP_REFERER',
            'HTTP_USER_AGENT',
            'HTTPS',
            'REMOTE_ADDR',
            'REMOTE_HOST',
            'REMOTE_PORT',
            'REMOTE_USER',
            'REDIRECT_REMOTE_USER',
            'SCRIPT_FILENAME',
            'SERVER_ADMIN',
            'SERVER_PORT',
            'SERVER_SIGNATURE',
            'PATH_TRANSLATED',
            'SCRIPT_NAME',
            'REQUEST_URI',
            'PHP_AUTH_DIGEST',
            'PHP_AUTH_USER',
            'PHP_AUTH_PW',
            'AUTH_TYPE',
            'PATH_INFO',
            'ORIG_PATH_INFO'
        ] ; 
        foreach($indicesServer as $key => $value)
        {
            print_airB(((!empty($_SERVER[$value])) ? $_SERVER[$value] : "$value est vide"),$value);
        }
    }
    function wtf($isthis=null){echo 'jusqu\'ici tout vas bien !'.(!empty($isthis) ? ' '.$isthis.' ' : '')."<br/>";}
?>