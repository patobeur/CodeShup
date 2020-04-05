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
        $retour_articles = '';
        foreach($lesarticles as $key => $value){
            $retour_articles .= make_fiche($value);
        }
        //           debut                      fin    breakline
        $capsule_article = ['<section class="articles">','</section>',PHP_EOL];

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




    function make_fiche($tablo){
        // $retour = '<article class="article">';
        // $retour .= '<figure><img src="genimage.php?img=200x200" alt="" width="200" height="50"></figure>';
        // $retour .= $tablo->name;
        // $retour .= ' / '.$tablo->name;
        // $retour .= ' / stock: '.$tablo->stock;
        // $retour .= ' / price: '.$tablo->price."€";
        // $retour .= ' / vendor_id: '.$tablo->vendor_id;
        // $retour .= ' / content: '.$tablo->content;
        // $retour .= '<span class="badge badge-success ml-auto small badge-pill">Free</span>';
        
        // $retour .= '</article>';
        // $retour .= PHP_EOL;
        $retour = '<div class="card">
                            <a href="#">
                            <figure>
                                <img class="card-img-top" src="genimage.php?img=500x300&ol&auto=format&fit=crop" alt="'.$tablo->name.'" title="'.$tablo->name.'">
                            </figure>
                            </a>
                                <div class="card-body">
                                    <h5 class="card-title">Lorem ipsum dolor sit amet.</h5>
                                    <p class="card-text">
                                    '.$tablo->content.'
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-eye"></i>Vendor : '.$tablo->price.'€
                                            <i class="fas fa-eye"></i>Vendor : '.$tablo->vendor_id.' |
                                            <i class="far fa-user"></i>Stock : '.$tablo->stock.'
                                            </small>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt"></i>'.date('H-m-d').' avant 12:00
                                            <a href="" class="class"><span class="badge badge-success ml-auto small badge-pill">Free</span></a> 
                                        </small>
                                    </p>
                                </div>
                        </div>'.PHP_EOL;
        return $retour;
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