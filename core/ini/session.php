<?php    
    if (empty($_SESSION))
    {
        $_SESSION['user'] = 
        [
            'pages' =>
            [
                'poi' => []
            ],
            'statut' => 'none',
            'try' => 0,
            'crea_date' => date('Y:m:d h:m:s'),
            'last_date' => null,
            'last_page' => 'none',
            'current_page' => 'index',
            'hit' => 0,
            'pages' => []
        ];
        $_SESSION['cms'] = [
            'check'     => [],
            'actions'    => [],
            'log'       => [],
            'errors'    => []
        ];
        // print_r($_SESSION);
    }
    else 
    {   // deja passé par là
        // print_r($_SESSION);
        $_SESSION['user']['last_page'] = $_SESSION['user']['current_page'];
        $_SESSION['user']['hit']++;
    }

    empty($_SESSION['user']['pages'][ $_SESSION['user']['current_page'] ])
        ? $_SESSION['user']['pages'][ $_SESSION['user']['current_page'] ] = 1
        : $_SESSION['user']['pages'][ $_SESSION['user']['current_page'] ] = $_SESSION['user']['pages'][$_SESSION['user']['current_page']] + 1
    ;

    $_SESSION['cms']['errors'] = [];
    $_SESSION['cms']['autoload'] = [];
    $_SESSION['cms']['class'] = [];
    $_SESSION['cms']['vue'] = [];
    $_SESSION['cms']['actions'] = [];
    $_SESSION['cms']['log'] = [];
    $_SESSION['cms']['require'] = [];
    $_SESSION['cms']['get_contents'] = [];
    $_SESSION['cms']['local'] = [];
    // $_SESSION['user']['pages']['poi'][] = $_SESSION['user']['current_page'];
    $_SESSION['user']['last_date'] = date('Y:m:d h:m:s');
?>