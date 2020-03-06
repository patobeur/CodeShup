<?php
    // Utilitaires / toolbox

    define('DEBUG_A', true); //get_Pageaouvriratouslescoups()
    define('DEBUG_B', true); //get_Pageaouvriratouslescoups()
    define('DEBUG_DIE', true); //die
    /**
     * autouploader de class / stacking class
     */
    function chargerClasse($classe) {
        require_once 'class/'.$classe . '.php';
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
    function print_air($paquet,$title=''){
        if (DEBUG_A){
            print('<hr><pre>');
            // echo "function(".__FUNCTION__.")<br>";
            ($title!='') ? print($title.':') : print('print_r:');
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
        print('<pre><hr>');
        ($title!='') ? print($title.':') : print('var_dump:');
        var_dump($paquet);
        print('</pre>');
    }
?>