<?php    
    if (empty($_SESSION['cms']))
    {
        $_SESSION['cms'] = [
            'user'      => [
                'crea_date' => date('Y:m:d h:m:s'),
                'last_date' => null,
                'last_page' => 'none',
                'current_page' => 'index',
                'hit' => 0,
                'pages' => [],
            ],
            'check'     => [],
            'actions'    => [],
            'log'       => [],
            'errors'    => []
        ];
    }
    else 
    {   // deja passé par là
        $_SESSION['cms']['user']['last_page'] = $_SESSION['cms']['user']['current_page'];
        $_SESSION['cms']['user']['hit']++;
    }

    empty($_SESSION['cms']['user']['pages'][$_SESSION['cms']['user']['current_page']])
        ? $_SESSION['cms']['user']['pages'][$_SESSION['cms']['user']['current_page']] = 1
        : $_SESSION['cms']['user']['pages'][$_SESSION['cms']['user']['current_page']] = $_SESSION['cms']['user']['pages'][$_SESSION['cms']['user']['current_page']] + 1
    ;
    $_SESSION['cms']['user']['pages']['poi'][] = $_SESSION['cms']['user']['current_page'];
    $_SESSION['cms']['user']['last_date'] = date('Y:m:d h:m:s');
    $_SESSION['cms']['check'] = [];
    $_SESSION['cms']['actions'] = [];
    $_SESSION['cms']['log'] = [];
    $_SESSION['cms']['errors'] = [];
?>