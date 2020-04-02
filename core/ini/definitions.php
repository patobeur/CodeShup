<?php

    Define('DEBUG' , false);
    define('STARTT',            $_SERVER['REQUEST_TIME_FLOAT']);
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
    // fichier toolbox
    define('DEBUG_A', false);   //get_Pageaouvriratouslescoups()
    define('DEBUG_B', true);    //get_Pageaouvriratouslescoups()
    define('DEBUG_DIE', true);  //die
?>