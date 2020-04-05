<?php
    if ($_SERVER['SERVER_NAME'] == '127.0.0.1' OR $_SERVER['SERVER_NAME'] == 'localhost')
    {        
        $_SESSION['cms']['serveur'] = 'local';
        define('DB', [
            'host' => '127.0.0.1',
            'username' => '',
            'password' => '',
            'dbname' => '',
            'attributs' => 'charset=utf8'
            ]
        );
    }
    else {
        $_SESSION['cms']['serveur'] = 'distant';
        define('DB',[
            'host' => '',
            'username' => '',
            'password' => '',
            'dbname' => '',
            'attributs' => ''
            ]
        );
    }
?>