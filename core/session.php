<?php    
    if (empty($_SESSION['cms']))
    {
        $_SESSION['cms'] = [
            'check'     => [],
            'actions'    => [],
            'log'       => [],
            'errors'    => [],
            'user'      => [
                'statut' => 'none',
                'try' => 0,
                'crea_date' => date('Y:m:d h:m:s'),
                'last_date' => null,
                'last_page' => 'none',
                'current_page' => 'index',
                'hit' => 0,
                'pages' => []
            ],
        ];
    }
    else 
    {   // deja passé par là
        $_SESSION['cms']['user']['last_page'] = $_SESSION['cms']['user']['current_page'];
        $_SESSION['cms']['user']['hit']++;
    }

    empty($_SESSION['cms']['user']['pages'][ $_SESSION['cms']['user']['current_page'] ])
        ? $_SESSION['cms']['user']['pages'][ $_SESSION['cms']['user']['current_page'] ] = 1
        : $_SESSION['cms']['user']['pages'][ $_SESSION['cms']['user']['current_page'] ] = $_SESSION['cms']['user']['pages'][$_SESSION['cms']['user']['current_page']] + 1
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
    // $_SESSION['cms']['user']['pages']['poi'][] = $_SESSION['cms']['user']['current_page'];
    $_SESSION['cms']['user']['last_date'] = date('Y:m:d h:m:s');
?>