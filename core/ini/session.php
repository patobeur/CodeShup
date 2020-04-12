<?php
$default_timezone = date_default_timezone_get();
date_default_timezone_set('Europe/Amsterdam');
// if (!isset($_SESSION['token'])){
//     $_SESSION['token'] = md5(rand(1, 10) . microtime());

// }


    if (!isset($_SESSION['user']))
    {   // nouveau
        $_SESSION['user'] = 
        [
            'pages' =>
            [
                'list' => [],
                'route' => []
            ],
            'statut' => 'none',
            'try' => 0,
            'crea_date' => date('Y-m-d H:i:s'),
            'last_date' => date('Y-m-d h:i:s'),
            'last_page' => 'none',
            'current_page' => 'index',
            'hit' => 0
        ];
        $_SESSION['cms'] = [];
    }

    empty($_SESSION['user']['pages']['list'][ $_SESSION['user']['current_page'] ])
        ? $_SESSION['user']['pages']['list'][ $_SESSION['user']['current_page'] ] = 1
        : $_SESSION['user']['pages']['list'][ $_SESSION['user']['current_page'] ] = $_SESSION['user']['pages']['list'][$_SESSION['user']['current_page']] + 1
    ;

    $_SESSION['cms']['errors'] = [];
    $_SESSION['cms']['autoload'] = [];
    $_SESSION['cms']['requete'] = [];
    $_SESSION['cms']['class'] = [];
    $_SESSION['cms']['require'] = [];
    $_SESSION['cms']['get_contents'] = [];
    $_SESSION['cms']['vue'] = [];
    $_SESSION['cms']['actions'] = [];
    $_SESSION['cms']['log'] = [];
    $_SESSION['cms']['local'] = [];
    $_SESSION['cms']['controller'] = [];

    // $_SESSION['user']['pages']['poi'][] = $_SESSION['user']['current_page'];
    $_SESSION['user']['hit']++;
    $_SESSION['user']['last_page'] = $_SESSION['user']['current_page'];
?>