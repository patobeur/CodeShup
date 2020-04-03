<?php
 
    define('STARTT',            $_SERVER['REQUEST_TIME_FLOAT']);
    
    if ($_SERVER['SERVER_NAME'] == '127.0.0.1' OR $_SERVER['SERVER_NAME'] == 'localhost')
    {        
        define('DISTANT', false);
        Define('DEBUG' , true);
        define('DEBUG_B', true);
        define('DEBUG_DIE', true);
    }
    else {
        define('DISTANT', true);
        Define('DEBUG' , false);
        define('DEBUG_B', false);
        define('DEBUG_DIE', false);
    }

    // definitions
    define('AAROOT',            '');
    define('AACORE',            AAROOT.'core/');
    define('AAINI',             AACORE.'ini/');
    // REP
    define("AAJSON",            AACORE.'json/');
    define("AALOG",             AACORE.'log/');
    // Formatage des fichiers
    define("AAFONCTION",        AACORE.'functions/'.'F_');
    define("AACLASSE",          AACORE.'Class/'.'Cl_');
    define("AATRAIT",           AACORE.'Trait/'.'Tr_');
    define("AACONTROLER",       AACORE.'Controller/'.'Co_');
    define("AACONTROLEUR",      AACORE.'Controller/'.'Co_');
    define("AAVUE",             AACORE.'View/'.'Vu_');          // pages parsées à la volé indiqué dans le json
    define("AAINVUE",           AACORE.'inview/'.'_in_');       // pages a mettre dans view
    // FILES
    define("AAJSONHEADER",      AAJSON.'structure.json');
    define("AAJSONCONTEN",      AAJSON.'content.json');
    // INCLUDES
    define("AANAVBAR",          AAVUE.'navigation.php');
    define("AAFOOTER",          AAVUE.'footer.php');
    // externes
    define("AAIMAGE",           AAROOT.'img/');
    define("AACSS",             AAROOT.'theme/css/');
    define("AAJS",              AAROOT.'js/');
    // TOOLS
    define("AAEXTPHP",       '.php');   
?>