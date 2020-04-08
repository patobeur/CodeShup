<?php
    // class Db for Bdd
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
            
            // if ($this->query = $this->db->prepare($requete_phrase)) {}

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

        }// ------------------------------------------------------------------------
        public function get_articlesFull(){return $this->get_ParticlesFull();}
        private function get_ParticlesFull()
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
















        
        // ------------------------------------------------------------------------
        public function get_users($datas = null){return $this->get_Pusers();}
        private function get_Pusers()
        {
            $select     = "SELECT *";
            // $select = "z_user.user_id";
            $from       = " FROM z_user";
            $where      = "";//" WHERE z_product.name >0";
            $order      = "";//" ORDER BY z_product.name ASC";
            $limite     = "";//" LIMITE 1";
            $requete    = $select.$from.$where.$order;
            $requete    = $this->db->prepare($requete);

            // if ($this->query = $this->db->prepare($requete)) {}

            // $requete = $this->db->prepare($requete);
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

        
        // ------------------------------------------------------------------------
        // ------------------------------------------------------------------------
        public function get_profils(){return $this->get_Pprofils();}
        private function get_Pprofils()
        {
            $select     = "SELECT *";
            // $select = "z_user.user_id";
            $from       = " FROM z_profil";
            $where      = (!empty($_SESSION['profil']['rule_id']) AND $_SESSION['profil']['rule_id'] === 1) ? " WHERE z_profil.user_id != 1" : "";
            $order      = " ORDER BY z_profil.user_id ASC";
            $limite     = "";//" LIMITE 1";
            $requete    = $select.$from.$where.$order;
            $requete    = $this->db->prepare($requete);

            // if ($this->query = $this->db->prepare($requete)) {}

            // $requete = $this->db->prepare($requete);
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



        
        // ------------------------------------------------------------------------
        public function get_profilsparutilisateur($user_id){return $this->get_Pprofilsparutilisateur($user_id);}
        private function get_Pprofilsparutilisateur($user_id)
        {
            $select     = "SELECT *";
            // $select = "z_user.user_id";
            $from       = " FROM z_profil";
            $where      = " WHERE z_profil.user_id = :user_id";
            $order      = " ORDER BY z_profil.user_id ASC";
            $limite     = "";//" LIMITE 1";
            $requete    = $select.$from.$where.$order;
            $requete    = $this->db->prepare($requete);
            $requete->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            // if ($this->query = $this->db->prepare($requete)) {}

            // $requete = $this->db->prepare($requete);
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






        private function md5UserMailById($user_id){
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
        public function md5UserMailByIdExcept($user_id){
            $_SESSION['cms']['check'][] = 'wtf:'.__FILE__.'/'.__FUNCTION__.'/'.__LINE__;
            $requete = $this->db->prepare("UPDATE z_user SET email = 'toto@mail.eu' WHERE z_user.user_id != :user_id");
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
        
        // ------------------------------------------------------------------------
        public function actions(){return $this->Pactions();}
        private function Pactions()
        {
            $requete = "SELECT product_id,cat_id,vendor_id FROM z_product ORDER BY product_id DESC";
            $requete = $this->db->prepare($requete);
            try {
                $requete->execute();
                $reponse = $requete->fetchall();
            }
            catch (PDOException $e) {
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                DISTANT ? die() : die($e->getMessage());
            }
            return $reponse;
        }
        // ------------------------------------------------------------------------
        public function actions2(){return $this->Pactions2();}
        private function Pactions2()
        {

            $requete=
                "SELECT *
                    ,case
                        when z_profil.activated LIKE '1' then 'Ok'
                        when z_profil.activated LIKE '0' then 'Ko'
                    end as situation                  
                    ,IF(z_profil.last_update IS NULL,'nomod','mod') as modifie
                    -- ,MAX(z_profil.birthdate) as dernvue
                FROM z_profil
                WHERE z_profil.username LIKE 'a%'";

            $requete = $this->db->prepare($requete);
            try {
                $requete->execute();
                $reponse = $requete->fetchall();
            }
            catch (PDOException $e) {
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                DISTANT ? die() : die($e->getMessage());
            }
            return $reponse;
        }
        // ------------------------------------------------------------------------
        public function actions3($datas){return $this->Pactions3($datas);}
        private function Pactions3($datas)
        {
            $product_id = $datas['product_id'];
            $cat_id     = $datas['cat_id'];
            $vendor_id  = $datas['vendor_id'];

            $requete="SELECT *
                FROM z_panier
                LEFT JOIN z_user ON z_user.user_id = z_panier.user_id
                LEFT JOIN z_product ON z_product.product_id = z_panier.product_id";

            $requete = $this->db->prepare($requete);
            try {
                $requete->execute();
                $reponse = $requete->fetchall();
            }
            catch (PDOException $e) {
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                DISTANT ? die() : die($e->getMessage());
            }
            return $reponse;
        }
        // ------------------------------------------------------------------------
        public function actions4($user_id){return $this->Pactions4($user_id);}
        private function Pactions4($user_id)
        {
            $requete="SELECT *
                FROM z_panier
                LEFT JOIN z_user ON z_user.user_id = z_panier.user_id
                LEFT JOIN z_product ON z_product.product_id = z_panier.product_id
                WHERE z_user.user_id = :user_id";

            $requete = $this->db->prepare($requete);
            $requete->bindParam(':user_id', $user_id, PDO::PARAM_STR, 32);
            try {
                $requete->execute();
                $reponse = $requete->fetchall();
            }
            catch (PDOException $e) {
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                DISTANT ? die() : die($e->getMessage());
            }
            return $reponse;
        }
        // ------------------------------------------------------------------------
        public function actions5($user_id){return $this->Pactions5($user_id);}
        private function Pactions5($user_id)
        {
            $requete="SELECT *
                FROM z_panier
                LEFT JOIN z_user ON z_user.user_id = z_panier.user_id
                LEFT JOIN z_product ON z_product.product_id = z_panier.product_id
                WHERE z_user.user_id = :user_id";

            $requete = $this->db->prepare($requete);
            $requete->bindParam(':user_id', $user_id, PDO::PARAM_STR, 32);
            try {
                $requete->execute();
                $reponse = $requete->fetchall();
            }
            catch (PDOException $e) {
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                DISTANT ? die() : die($e->getMessage());
            }
            return $reponse;
        }
        // ------------------------------------------------------------------------
        public function actions6($user_id){return $this->Pactions6($user_id);}
        private function Pactions6($user_id)
        {
            $requete="SELECT *
                FROM z_panier
                LEFT JOIN z_user ON z_user.user_id = z_panier.user_id
                LEFT JOIN z_product ON z_product.product_id = z_panier.product_id
                WHERE z_user.user_id = :user_id";

            $requete = $this->db->prepare($requete);
            $requete->bindParam(':user_id', $user_id, PDO::PARAM_STR, 32);
            try {
                $requete->execute();
                $reponse = $requete->fetchall();
            }
            catch (PDOException $e) {
                $_SESSION['cms']['errors'][] = __FILE__." ".__FUNCTION__.":".$e->getMessage();
                DISTANT ? die() : die($e->getMessage());
            }
            return $reponse;
        }





























		// $sql="SELECT u2.barrecode, u2.nom_article,
        //             case
        //                 when u2.Valide LIKE '0' then 'HS'
        //                 when u2.Valide LIKE '1' then 'OK'
        //                 when u2.Valide LIKE '2' then 'En réparation'
        //                 when u2.Valide LIKE '3' then 'Spécial'
        //             end as situation,

        //             IF(u1.date IS NULL,'Absent','OK') as etat, MAX(u3.date) as lastVue
        //         FROM BC_articles u2
        //         LEFT JOIN `BC_locations` u1 ON u2.article_id=u1.id_article AND u1.`action`='INV'
        //             AND DATE=(SELECT date
        //                 FROM `BC_locations`
        //                 WHERE action='INV'
        //                 GROUP BY date
        //                 ORDER BY date DESC
        //                 LIMIT 1)
        //         LEFT JOIN BC_locations u3 ON u3.id_article=u2.article_id AND u3.action<>'INV'
        //         WHERE u2.barrecode=:barrecode OR u2.nom_article=:barrecode
        //         GROUP BY u2.barrecode";
    }
?>
