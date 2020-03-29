<?php
    class Db {

        protected $_dbDatas;
        protected $db;

        protected $tables = [
            'z_granted',
            'z_media',
            'z_mediatype',
            'z_panier',
            'z_prodcat',
            'z_prodmedia',
            'z_product',
            'z_profil',
            'z_promo',
            'z_rule',
            'z_sceance',
            'z_section',
            'z_tchat',
            'z_tcoment',
            'z_user',
            'z_vendor'
        ];
        /**
         * Class constructor
         */
        // public function __construct($host = null,$username = null,$password = null,$dbname = null)
        public function __construct()
        {   
            $this->_dbDatas = [
                'host' => '127.0.0.1',
                'dbname' => 'code_shop',
                'username' => 'root',
                'password' => "",
                'charset' => "utf8"
            ];
            try {
                $bdd = new PDO
                    (
                        'mysql:host='.$this->_dbDatas['host'].';dbname='.$this->_dbDatas['dbname'].';'.$this->_dbDatas['password'],
                        $this->_dbDatas['username'], $this->_dbDatas['password']
                    ); 
                // $bdd->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES UTF8');
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $this->db = $bdd;
            }
            catch (PDOException $e){
                // echo "Error : ";// . $e->getMessage() . "<br/>";
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
            die();
            }

            // $this->compteexiste([
            //     "email" => "patobeur41@gmail.com",
            //     "passwrd" => "toto"
            // ]);
            
        }
        public function is_exist_user($datas)
        {
            $requete = $this->db->prepare("SELECT user_id,rule_id FROM z_user WHERE email = :email AND passwrd = :passwrd");
            $requete->bindParam(':passwrd', $datas['passwrd'], PDO::PARAM_STR, 32);
            $requete->bindParam(':email',   $datas['email'],   PDO::PARAM_STR, 64);
            try {
                $requete->execute();
                $reponse = $requete->fetch();                
                $requete = null;
                
                $donnees = [
                    "user_id" => $reponse->user_id,
                    "rule_id" => $reponse->rule_id
                ];      
                $this->set_user_id_datas($donnees);
            }
            catch (PDOException $e){
                // echo "Error : ".__FILE__." ".__FUNCTION__;// . $e->getMessage() . "<br/>";
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":";//.$e->getMessage();
                die();
            }
        }
        public function set_user_id_datas($donnees)
        {
            $requete = $this->db->prepare("SELECT username,firstname,email,phone,birthdate,section_id,promo_id,last_update,created,activated FROM z_profil WHERE user_id = :user_id AND activated = 1");
            $requete->bindParam(':user_id',  $donnees['user_id'],   PDO::PARAM_INT);

            try {
                $requete->execute();
                $reponse = $requete->fetch();
            }
            catch (PDOException $e){
                // echo "Error : ".__FILE__." ".__FUNCTION__." ".__LINE__;// . $e->getMessage() . "<br/>";
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                die();
            }
                $requete = null;

            if (!empty($reponse))
            {
                    $_SESSION['cms']['user']['statut'] = 'logged';
                    $_SESSION['cms']['profil']['accred'] = $donnees['rule_id'];
                    $_SESSION['cms']['profil']['level'] = $this->get_accred_name($donnees['rule_id']);
                    $_SESSION['cms']['profil']['username'] = $reponse->username;
                    $_SESSION['cms']['profil']['email'] = $reponse->email;
                    $_SESSION['cms']['profil']['section_id'] = $reponse->section_id;
                    $_SESSION['cms']['profil']['promo_id'] = $reponse->promo_id;
                    $_SESSION['cms']['profil']['last_update'] = $reponse->last_update;
                    $_SESSION['cms']['profil']['created'] = $reponse->created;
            }
        }


        
        public function get_accred_name($datas)
        {
            $requete = $this->db->prepare("SELECT ruleset FROM z_rule WHERE rule_id = :rule_id");
            $requete->bindParam(':rule_id', $datas, PDO::PARAM_INT);
            try {
                $requete->execute();
                $reponse = $requete->fetch();                
                $requete = null;
                return $reponse->ruleset;
            }
            catch (PDOException $e){
                // echo "Error : ".__FILE__." ".__FUNCTION__;// . $e->getMessage() . "<br/>";
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":";//.$e->getMessage();
                die();
            }
        }
        public function get_fetchall_from($datas)
        {
                $table = $datas;
            
                $requete = $this->db->prepare("SELECT email,created,updated FROM ".$table); //" WHERE email = :email AND passwrd = :passwrd");
                // $requete->bindParam(':email', $email, PDO::PARAM_STR, 50);
                // $requete->bindParam(':passwrd', $passwrd, PDO::PARAM_STR, 50);
                // $requete->debugDumpParams(); 
                $requete->execute();
                $reponse = $requete->fetchall();
                // fermeture
                $requete = null;
                return $reponse;                
        }

        // INSERT INTO `z_user` (`email`, `passwrd`, `rule_id`, `created`, `updated`) VALUES ('tutu@gmail.com', 'tutu', '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)

    }
?>
