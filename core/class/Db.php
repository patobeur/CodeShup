<?php
        // if ($_SERVER['HTTP_HOST'] == ''){
        //     define ('DBDATAS', [
        //         'host' => '',
        //         'db_name' => '',
        //         'username' => '',
        //         'password' => ''
        //     ]);
        // }
        // elseif ($_SERVER['HTTP_HOST'] == 'localhost'){
        //     define ('DBDATAS', [
        //         'host' => '127.0.0.1',
        //         'db_name' => 'codeshop',
        //         'username' => 'localhost',
        //         'password' => ''
        //     ]);
        // }
        // else{
        //   // DIE
        //   die('un problème avec votre BDD !');
        // }

    class Db {
        private $_host = 'localhost';
        private $_username = 'localhost';
        private $_password = '';
        private $_dbname = 'code_shop';
        public $db;

        /**
         * Class constructor
         */
        public function __construct( $host = null, $username = null,$password = null, $dbname = null ) {
            if ($host != null){
                $this->host = $host;
                $this->username = $username;
                $this->password = $password;
                $this->dbname = $dbname;
            }
            try {
                $this->db = new PDO(
                    'mysql:host='.$this->host.';dnbame='.$this->dbname,
                    $this->username,
                    $this->password, 
                    array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
                        )
                );
            }
            catch(PDOException $e)
            {
                die('Problème avec la Bdd');
            }
        }
    }
?>