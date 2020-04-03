<?php 
Class Boutique
{
    // use hydratationAndCream;
    private $_categories;
    private $_panier;
    private $_identified;
    private $_DbBoutique;
    //         

    public function __construct()
    {
        // if (!empty($donnees)) { $this->hydrate_index($donnees); }
        $this->_panier = null;
        $this->_identified = (!empty($_SESSION['profil'])) ? $_SESSION['profil']['username'] : null;
        if (class_exists('Db')) {
            $this->_DbBoutique = new Db();
        }
        else{
            Page::set_error(__FILE__."/".__CLASS__."/".__FUNCTION__,'la classe Db n\existe pas',$action='errors');
            die();
        }
    }
    public function get_categories(){
        return $this->_DbBoutique->get_categories();
    }
    public function get_articlesByCategorieId($categorie){
        return $this->_DbBoutique->get_articlesByCategorieId($categorie);
    }
    public function get_articles(){
        return $this->_DbBoutique->get_articles();
    }
}
?>