<?php
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
                DISTANT ? die() : die($e->getMessage());
            }            
        }
        // ------------------------------------------------------------------------
        public function get_articlesByCategorieId($categorie){
            return $this->get_ParticlesByCategorieId($categorie);
        }
        private function get_ParticlesByCategorieId($categorie)
        {
            // print_airB($categorie->cat_id,'categorie',1);
            $select     = "SELECT *";
            $from       = " FROM z_product";
            $where      = " WHERE z_product.cat_id = :catid";
            // $where      .= " AND ";
            $order      = " ORDER BY z_product.name ASC";
            $limite     = "";//" LIMITE 1";
            
            $requete_categories = $this->db->prepare($select.$from.$where.$order.$limite);
            $requete_categories->bindParam(':catid', $categorie->cat_id, PDO::PARAM_INT, 32);
            try {
                $requete_categories->execute();  
                // $requete_categories->debugDumpParams();   // debug affichage   
                $reponse = $requete_categories->fetchall(); 
                return $reponse;
            }
            catch (PDOException $e){
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                DISTANT ? die() : die($e->getMessage());
                return null;
            }

        }
        // ------------------------------------------------------------------------
        public function get_categories($datas = null){return $this->get_Pcategories();}
        private function get_Pcategories()
        {
            $requete_phrase  = "SELECT distinct(z_prodcat.label),z_prodcat.cat_id from z_prodcat WHERE z_prodcat.parent_cat_id IS NULL ORDER BY z_prodcat.label ASC";
            
            if ($this->query = $this->db->prepare($requete_phrase)) {}

            $requete_categories = $this->db->prepare($requete_phrase);
            try {
                $requete_categories->execute();  
                $reponse = $requete_categories->fetchall();
                // print_airB($reponse,'fetchall',1);
                return $reponse;
            }
            catch (PDOException $e){
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage(); 
                DISTANT ? die() : die($e->getMessage());
                return null;
            }

        }
        // ------------------------------------------------------------------------
        public function get_articles(){return $this->get_Particles();}
        private function get_Particles()
        {
            $select = "SELECT *";
            // $select = "z_product.product_id,z_product.name,z_product.create_time,z_product.update_time,z_product.stock,z_product.alerte,z_product.cat_id,z_product.price,z_product.vendor_id,z_product.content";
            $from = " FROM z_product";
            $where = "";//" WHERE z_product.name >0";
            $order = "";//" ORDER BY z_product.name ASC";
            $limite = "";//" LIMITE 1";
            $requete  = $select.$from.$where.$order;
            $requete = $this->db->prepare($requete);
            try {
                $requete->execute();
                $reponse = $requete->fetchall();
                // print_airB($reponse,'fetchall',1);
                return $reponse;
            }
            catch (PDOException $e){
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                DISTANT ? die() : die($e->getMessage());
                return null;
            }

        }
        // private function get_boutique($categorie)
        // {
        //     $select  = "SELECT z_prodcat.label,z_prodcat.cat_id";
        //     $from = " FROM z_prodcat";
        //     $where = "WHERE z_prodcat.parent_cat_id IS NULL";
        //     $requete = $this->db->prepare("$select $from $where");
        //     try {
        //         $requete->execute();
        //         // $requete->debugDumpParams();   // debug affichage    
        //         $reponse = $requete->fetchall();    
        //         // print_airB($reponse,'go',1);
        //         //$requete->debugDumpParams();   // debug affichage           
        //         $requete = null;
        //         return $reponse;
        //     }
        //     catch (PDOException $e){
                // DISTANT ? die() : die($e->getMessage());
        //         // die();
        //         return false;
        //     }
        // }


/*      -----------------------------------------------------------------------------------/
        ------------------                USER               -----------------------------/
        --------------------------------------------------------------------------------*/
        /* ------------------          CONNECTING           ---------------------------*/
        public function is_exist_user($datas)
        {
            $requete = $this->db->prepare(
                "SELECT
                    z_user.user_id,z_user.rule_id,
                    z_rule.ruleset,
                    z_profil.username,z_profil.firstname,z_profil.email,z_profil.section_id,
                    z_profil.promo_id,z_profil.last_update,z_profil.created,z_profil.activated
                FROM 
                    z_user,z_profil,z_rule
                WHERE 
                    z_user.user_id = z_profil.user_id AND activated = 1
                    AND z_user.rule_id = z_rule.rule_id
                    AND z_user.email = :email AND z_user.passwrd = :passwrd
                "
            );
            $requete->bindParam(':passwrd', $datas['passwrd'], PDO::PARAM_STR, 32);
            $requete->bindParam(':email',   $datas['email'],   PDO::PARAM_STR, 64);
            // $requete->bindParam(':rule_id', $datas, PDO::PARAM_INT);
            try {
                $requete->execute();
                // $requete->debugDumpParams();   // debug affichage        
                $reponse = $requete->fetch();
                //$requete->debugDumpParams();   // debug affichage           
                $requete = null;
            }
            catch (PDOException $e){
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                DISTANT ? die() : die($e->getMessage());
                return false;
            }
            if (!empty($reponse))
            {
                $this->set_last_connect($reponse->user_id);
                $_SESSION['user']['statut'] = 'logged';
                $_SESSION['profil']['ruleset'] = $reponse->ruleset;
                $_SESSION['profil']['rule_id'] = $reponse->rule_id;
                $_SESSION['profil']['username'] = ucfirst($reponse->username);
                $_SESSION['profil']['firstname'] = $reponse->firstname;
                $_SESSION['profil']['email'] = $reponse->email;
                $_SESSION['profil']['section_id'] = $reponse->section_id;
                $_SESSION['profil']['promo_id'] = $reponse->promo_id;
                $_SESSION['profil']['last_update'] = $reponse->last_update;
                $_SESSION['profil']['created'] = $reponse->created;
                $_SESSION['profil']['activated'] = $reponse->activated;
                $_SESSION['profil']['connected'] = date('Y-m-d H:i:s');
                return true;
            }
            else{
                $_SESSION['user']['try'] += 1;
                return false;
            }
        }
        /* ------------------      SET LAST CONNECT        ---------------------------*/
        private function set_last_connect($user_id)
        {

            $requete = $this->db->prepare("UPDATE z_user SET last_connect = '".date('Y-m-d H:i:s')."' WHERE z_user.user_id = :user_id");
            $requete->bindParam(':user_id', $user_id, PDO::PARAM_STR, 32);
            try {
                $requete->execute();      
                //$reponse = $requete->fetch();
                $_SESSION['cms']['requete'][] = 'oo'.__FILE__.'/'.__FUNCTION__.'/'.__LINE__;   // debug affichage           
                // $requete = null;
            }
            catch (PDOException $e){
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                DISTANT ? die() : die($e->getMessage());
            }
            $requete = null;
        }


        // public function get_fetchall_from($datas)
        // {
        //         $table = $datas;
            
        //         $requete = $this->db->prepare("SELECT email,created,updated FROM ".$table); //" WHERE email = :email AND passwrd = :passwrd");
        //         // $requete->bindParam(':email', $email, PDO::PARAM_STR, 50);
        //         // $requete->bindParam(':passwrd', $passwrd, PDO::PARAM_STR, 50);
        //         // $requete->debugDumpParams(); 
        //         $requete->execute();
        //         $reponse = $requete->fetchall();
        //         // fermeture
        //         $requete = null;
        //         return $reponse;                
        // }
        private function wtf($user_id){
            $_SESSION['cms']['check'][] = 'wtf:'.__FILE__.'/'.__FUNCTION__.'/'.__LINE__;
            $requete = $this->db->prepare("UPDATE z_user SET email = md5(email) WHERE z_user.user_id != :user_id");
            $requete->bindParam(':user_id', $user_id, PDO::PARAM_STR, 32);
            try {
                $requete->execute();   
                $requete->debugDumpParams();   // debug affichage     
            }
            catch (PDOException $e){
                $_SESSION['cms']['requete'][] = 'oo'.__FILE__.'/'.__FUNCTION__.'/'.__LINE__;   // debug affichage     
                DISTANT ? die() : die($e->getMessage());
            }
            $requete = null;
        }
    }
?>
