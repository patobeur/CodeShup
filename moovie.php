<?php
echo 'coucou';
die;
    define('DB',[
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'code_shop_new',
        'attributs' => 'charset=utf8'
        ]
    );

        
        // 'host' => 'apteoorgdjfeun.mysql.db',
        // 'username' => 'apteoorgdjfeun',
        // 'password' => 'Feun2000',
        // 'dbname' => 'apteoorgdjfeun',
    class Db {

        protected $db;
        
        public function __construct()
        {   
            try {
                $bdd = new PDO('mysql:host='.DB['host'].';dbname='.DB['dbname'].';'.DB['attributs'],DB['username'], DB['password']); 
                $bdd->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES UTF8');
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $this->db = $bdd;
            }
            catch (PDOException $e){
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                die($e->getMessage());
            }            
        }
        public function senddatas()
        {
            $requete     = "SELECT * FROM z_profil ORDER BY z_profil.username ASC";
            $req_list = $this->db->prepare($requete);
            // $req_list->bindParam(':catid', $categorie->cat_id, PDO::PARAM_INT, 32);
            try {
                $req_list->execute();  
                $req_list->debugDumpParams();   // debug affichage   
                $reponse = $req_list->fetchall(); 
                return $reponse;
            }
            catch (PDOException $e){
                die($e->getMessage());
                return null;
            }

        }

    }

    $dataz = new Db();
    $json = $dataz->senddatas();
    print_r($json);
    $json = json_encode($json);
    // if ($json === false) {
    //     // Avoid echo of empty string (which is invalid JSON), and
    //     // JSONify the error message instead:
    //     $json = json_encode(["jsonError" => json_last_error_msg()]);
    //     if ($json === false) {
    //         // This should not happen, but we go all the way now:
    //         $json = '{"jsonError":"unknown"}';
    //     }
    //     // Set HTTP response status code to: 500 - Internal Server Error
    //     http_response_code(500);
    // }
    header('Content-type:application/json;charset=utf-8');
    echo $json;
        
?>