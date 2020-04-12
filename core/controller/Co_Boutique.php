<?php
// ConTroller > Boutique

    $replace_in_vue = [
        'TITRE'         => 'Page Profil'
        ,'PSEUDO'      => (!empty($_SESSION['profil']) ? ' '.ucfirst(get_clean($_SESSION['profil']['username'])) : 'Visiteur')
        ,'ARIANNE'      => ''
    ];


    if (class_exists('Db')) {
        $Boutique  = new Boutique();
        $lescategories = $Boutique->get_categories();
            
        if (!empty($lescategories)) 
        {
            if (isset($_GET['boutique']) AND empty($_GET['boutique']) AND !empty($_GET['cat']))
            {   
                // print_airB($lescategories,'coucou',1);
                foreach ($lescategories as $value)
                {
                    if ($value->label === get_clean($_GET['cat']))
                    {
                        $categorie = $value;
                        break;
                    }
                }
            }
        }


        
        if (!empty($categorie))
        {
            $lesarticles = $Boutique->get_articlesByCategorieId($categorie);
        }
        else{
            $lesarticles = $Boutique->get_articles();
        }
    }


    if (!empty($lescategories)) 
    {
        $retour = [];
        $retour_categories = '';
        foreach($lescategories as $key => $value){
            $retour[] = $value->label;
            $retour_categories .= make_href($value->label);
        }
        //           debut                      fin    breakline
        $capsule = ['<ul class="cat_arianne">','</ul>',PHP_EOL];
    }

    if (!empty($lesarticles)) 
    {
        
        // on prend la vue article
        $vue_article = $this->get_File_to_use('vue',AAINVUE."article".AAEXTPHP,"file_get_contents",$this->get_errorphrase('article',__FUNCTION__,__LINE__));
        
        $retour_articles = '';

        foreach($lesarticles as $key => $value){
            $retour_articles .= make_fiche($value,$vue_article);
        }    
        //                      debut                        fin         breakline
        // $capsule_article = ['<section class="articles">','</section>',PHP_EOL];
        $capsule_article = ['','',PHP_EOL];

    }

    $replace_in_vue['TITRE']        = 'Page Boutique';
    $replace_in_vue['ARIANNE']      = (!empty($retour_categories) ? $capsule[0].$retour_categories.$capsule[1].$capsule[2] : '');
    $replace_in_vue['PSEUDO']       = (!empty($_SESSION['profil']) ? ' '.ucfirst(get_clean($_SESSION['profil']['username'])) : 'Visiteur');
    $replace_in_vue['ARTICLES']     = (!empty($retour_articles) ? $capsule_article[0].$retour_articles.$capsule_article[1].$capsule_article[2] : '');
        




    function add_or_change_parameter($parameter, $value)
    {
        /* <a href="<?php echo add_or_change_parameter("page", "2"); ?>">Click to go to page 2</a> */
      $params = array();
      $output = "?";
      $firstRun = true;
      foreach($_GET as $key=>$val)
      {
       if($key != $parameter)
       {
        if(!$firstRun)
        {
         $output .= "&";
        }
        else
        {
         $firstRun = false;
        }
        $output .= $key."=".urlencode($val);
       }
      }
      if(!$firstRun)
       $output .= "&";
      $output .= $parameter."=".urlencode($value);
      return htmlentities($output);
    } 




    function make_fiche($tablo,$vue){
        
        $vue = str_replace("{{articleImage}}" , 'genimage.php?img=600x300',$vue);
        $vue = str_replace("{{articleName}}" , $tablo->name,$vue);
        $vue = str_replace("{{articlePrix}}" , $tablo->price,$vue);
        $vue = str_replace("{{articleContent}}" , $tablo->content,$vue);
        $vue = str_replace("{{articleVendor}}" , $tablo->vendor_id,$vue);
        $vue = str_replace("{{articleStock}}" , $tablo->stock,$vue);
        $vue = str_replace("{{altImage}}" , $tablo->name,$vue);
        $vue = PHP_EOL.$vue.PHP_EOL;
        return $vue;


    }
    function make_href($tablo){
        $retour = '<li class="cat_item">';
        $retour .= '<a class="cat_lien" href="';
        $retour .= '?boutique';
        $retour .= '&cat=';
        $retour .= $tablo;
        $retour .= '" ';
        $retour .= 'title ="';
        $retour .= $tablo;
        $retour .= '">';
        $retour .= $tablo;
        $retour .= '</a>';
        $retour .= '</li>';
        $retour .= PHP_EOL;
        return $retour;
    }

    Page::set_replace_in_vue($replace_in_vue);
?>