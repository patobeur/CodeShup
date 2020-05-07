<?php
// ini_set('display_errors',1);
// ConTroller principal > Controller
    // if(empty($_SESSION['profil']['username'])) {die();}
    $timer = microtime(true);
    if (isset($_GET['kill'])) header("Location:../deco.php" );
    // include('../'.'core/ini/definitions.php'); // MonkeyBusiness
    require_once(ADDINI.'bdd.php');
    require_once(ADDINI.'session.php');
    require_once(ADDCORE.'toolbox.php');

    $default_pagecible = 'tableaudebord';
    $pagespossibles = [
        'tableaudebord'
        ,'myprofil'
        ,'actions'
        ,'utilisateurs'
        ,'articles'
        ,'profils'
        ,'profilsparutilisateur'
    ]; 
    
    $DbAdmin = new Admin($timer,'admin');

    $replace_in_vue = [
        'myprofil' => [
            'TITRE'         => 'Mon profil Utilisateur'
            ,'USERNAME'      => $_SESSION['profil']['username']
        ],
        'admin' => [
            'TITRE'         => 'Page Admin'
            ,'USERNAME'      => $_SESSION['profil']['username']
            ,'USERAVATAR'    => '../genimage.php?img=50x50&nb'
            ,'ICI'           => 'toto'
            ,'VUES' =>       '{{vue}}'
        ],
        'tableaudebord' => [
            'test'         => 'Tableau de bord'
        ],
        'actions' => [
            'titre'         => 'Page actions SQL'
            ,'test1'         => 'Tableau des actions'
        ],
        'utilisateurs' => [
            'test'          => 'Gestion des Utilisateurs'
        ],
        'articles' => [
            'test'         => 'Tableau des articles'
        ],
        'profils' => [
            'test'          => 'Gestion des Utilisateurs'
        ],
        'profilsparutilisateur' => [
            'test'          => 'Gestion des profils par utilisateur'
        ]
    ];
  
    if (!empty($_GET))
    {
        // to do
        // $UriIndex = explode('/', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        parse_str($_SERVER['QUERY_STRING'], $array_arg);
        $page_cible = null;
        foreach($array_arg as $key => $value)
        {
            $page_cible = (in_array ($key, $pagespossibles, true)) ? $key : false;
            break;
        }
        // debug
        $replace_in_vue['admin']['ICI'] = debug_quellepage($page_cible);
        
    }

    $DbAdmin->set_replace_vue($replace_in_vue['admin'],'admin');







        if (!empty($page_cible))
        {


            $req_file = str_replace("{{REP}}" ,'admin', ADCONTROLER).ucfirst($page_cible).AAEXTPHP;  // fichier a require
            //si on trouve une page demandée
            switch($page_cible)
            {
                case 'myprofil';
                // print_airB(Page::get_PCurrent_Page(),'wazaaaa');
                // // // print_airB($req_file,'req_file');
        

                include($req_file);
                $articles = 'toto';
                if (!empty($articles))
                {
                    $replace_in_vue[$page_cible]['TABLE'] = $articles;
                    $donnees = [
                        'un' => $replace_in_vue[$page_cible],
                        'deux' => $page_cible
                    ];
                }

                
            


                break;
                case 'actions';
                    // $req_file = str_replace("{{REP}}" ,$page_cible, ADCONTROLER).ucfirst($page_cible).AAEXTPHP;  // fichier a require
                    // // // // print_airB($req_file,'req_file');
                    // Page::get_File_to_use(
                    //     'controller',
                    //     $req_file,
                    //     "include",
                    //     Page::get_errorphrase(__FILE__,__FUNCTION__,__LINE__)
                    // );

                    // include($req_file);
                    // print_airB($replace_in_vue,'replace_in_vue');


                    // 2
                    $lettre = "p";
                    $idd = null;
                    $limite = " LIMIT 10";
                    $req = "SELECT * ".PHP_EOL;
                    $req .= "   ,case ".PHP_EOL;
                    $req .= "       when z_profil.activated LIKE '1' then 'Ok' ".PHP_EOL;
                    $req .= "       when z_profil.activated LIKE '0' then 'Ko' ".PHP_EOL;
                    $req .= "   end as situation ".PHP_EOL;
                    $req .= "   ,IF(z_profil.last_update IS NULL,'nomod','mod') as modifie ".PHP_EOL;
                    $req .= "FROM z_profil ".PHP_EOL;
                    $req .= "WHERE z_profil.username LIKE '".$lettre."%'".$limite;


                    $laliste_items = $DbAdmin->GetDbActions($idd,$req);
                    $intitules_array = ['profil_id','username','email','promo_id','situation','modifie'];
                    $data_recuphtml = ResponseToHtml($intitules_array,$laliste_items);

                    $replace_in_vue[$page_cible]['intitules2'] = $data_recuphtml['intitules'];
                    $replace_in_vue[$page_cible]['titre2'] = 'Profils d\'utilisateur dont le nom commence par '.$lettre;   
                    $replace_in_vue[$page_cible]['test2'] =  $data_recuphtml['items'];
                    $replace_in_vue[$page_cible]['requete2'] = $req;

                    // 3
                    $lettre = "p";
                    $idd = null;
                    $limite = "";
                    $req = "SELECT * ".PHP_EOL;
                    $req .= "FROM z_panier ".PHP_EOL;
                    $req .= "LEFT JOIN z_user ON z_user.user_id = z_panier.user_id ".PHP_EOL;
                    $req .= "LEFT JOIN z_product ON z_product.product_id = z_panier.product_id".$limite;

                    $laliste_items = $DbAdmin->GetDbActions($idd,$req);
                    $intitules_array = ['panier_id','user_id','email','name','content','price','panier_id','create_time','update_time'];
                    $data_recuphtml = ResponseToHtml($intitules_array,$laliste_items);
                    //
                    $replace_in_vue[$page_cible]['intitules3'] = $data_recuphtml['intitules'];
                    $replace_in_vue[$page_cible]['requete3'] = $req;                    
                    $replace_in_vue[$page_cible]['titre3'] = 'liste de tous les utilisateurs avec un panier actif. Aussi les articles et prix';
                    $replace_in_vue[$page_cible]['test3'] =  $data_recuphtml['items'];

                    // 4 --------------------------------------------------------------------------------------------------------------------------------
                    $lettre = "p";
                    $idd = 1;
                    $limite = "";
                    $req = "SELECT * ".PHP_EOL;
                    $req .= "FROM z_panier ".PHP_EOL;
                    $req .= "LEFT JOIN z_user ON z_user.user_id = z_panier.user_id ".PHP_EOL;
                    $req .= "LEFT JOIN z_product ON z_product.product_id = z_panier.product_id ".PHP_EOL;
                    $req .= "WHERE z_panier.user_id = :user_id".$limite.PHP_EOL;

                    $laliste_items = $DbAdmin->GetDbActions($idd,$req);
                    $intitules_array = ['panier_id','user_id','email','name','content','price','panier_id','create_time','update_time',];
                    $data_recuphtml = ResponseToHtml($intitules_array,$laliste_items);
                    //
                    $replace_in_vue[$page_cible]['intitules4'] = $data_recuphtml['intitules'];
                    $replace_in_vue[$page_cible]['requete4'] = $req;                    
                    $replace_in_vue[$page_cible]['titre4'] = ' Panier de l\'utilisateur avec l\'user_id = '.$idd; 
                    $replace_in_vue[$page_cible]['test4'] =  $data_recuphtml['items'];

                    // 5 --------------------------------------------------------------------------------------------------------------------------------
                    $req =PHP_EOL."SELECT * ".PHP_EOL;
                    $req .="FROM z_panier ".PHP_EOL;
                    $req .="LEFT JOIN z_user ON z_user.user_id = z_panier.user_id ".PHP_EOL;
                    $req .="LEFT JOIN z_product ON z_product.product_id = z_panier.product_id ".PHP_EOL;
                    $req .="WHERE z_user.user_id = :user_id".PHP_EOL;

                    $four = $DbAdmin->GetDbActions(1,$req);

                    $items_four = '';
                    $liste_intitules = ['panier_id','user_id','email','name','content','price','panier_id','create_time','update_time',];
                    foreach($four as $key => $value)
                    {   
                        $tempo  = '';
                        $intitules  = '';
                        $tempo .= '<td>tools</td>';
                        $intitules .= '<td>tools</td>';
                        foreach ($liste_intitules as $key => $value2)
                        {
                            $temppo = (strlen($value->$value2) > 32) ? substr($value->$value2, 0, 30).'<pan title="'.$value->$value2.'">[+]</pan>' : $value->$value2;   
                            $tempo .= '<td>'.$temppo.'</td>';
                            $intitules .= '<td>'.$value2.'</td>';
                        }
                        // print_airB($value);
                        $items_four = '
                        <tr>
                            '.$tempo.'        
                        </tr>'.PHP_EOL;
                        $intitules = '
                        <tr>
                            '.$intitules.'        
                        </tr>'.PHP_EOL;
                    }
                    $replace_in_vue[$page_cible]['intitules5'] = $intitules ; 
                    $replace_in_vue[$page_cible]['titre5'] = ' )Combien d\'utilisateur on un panier actif, une image associé au profil et un O dans leur mail.'; 
                    $replace_in_vue[$page_cible]['test5'] = $items_four;
                    $replace_in_vue[$page_cible]['requete5'] = $req;




                    // 6 --------------------------------------------------------------------------------------------------------------------------------
                    $req1 ="INSERT INTO z_user ".PHP_EOL;
                    $req1 .="(user_id, email, passwrd, rule_id, created, updated, last_connect) ".PHP_EOL;
                    $req1 .= "VALUES (NULL, 'patobeur666@gmail.com', MD5('toto'), '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP); ".PHP_EOL;
                    
                    $req2  ="INSERT INTO z_profil  ".PHP_EOL;
                    $req2 .= "(profil_id, user_id, username,firstname, email, phone, birthdate, section_id, promo_id, last_update, created, activated)  ".PHP_EOL;
                    $req2 .= "VALUES  ".PHP_EOL;
                    $req2 .= "(NULL, :user_id, 'patobeur', 'etlardons', 'patobeur666@gmail.com', '0609080808', '2019-04-16', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '1'); ".PHP_EOL;
                    
                    $req3 ="SELECT * FROM z_profil WHERE email = 'patobeur666@gmail.com' LIMIT 1".PHP_EOL;

                    $lastid = $DbAdmin->InsertDbActions(null,$req1);
                    $profil = $DbAdmin->InsertDbActions($lastid ,$req2);
                    // $profil = $DbAdmin->insert_actions(["col" => "user_id", "val" => '$lastid'] ,$req2);

                    $six = $DbAdmin->GetDbActions(1,$req3);

                    $items6 = '';
                    $liste_intitules = ['profil_id','user_id','username','firstname','email','phone','birthdate','section_id','promo_id','last_update','created','activated'];

                    // intitulés
                    $intitules  = '
                    <tr>
                        <td>tools</td>';
                    foreach ($liste_intitules as $key => $value2)
                    {
                        $intitules .= '<td>'.$value2.'</td>';
                    }
                    $intitules = $intitules.'        
                    </tr>'.PHP_EOL;


                    foreach($six as $key => $value)
                    {   
                        // print_airB($six[$key],'value');
                        $tempo  = '';
                        $tempo .= '<td>tools</td>';
                        $data = $six[$key];

                        foreach ($liste_intitules as $key2 => $value2)
                        {
                            // $temppo = (strlen($value->$value2) > 32) ? substr($value->$value2, 0, 30).'<pan title="'.$value->$value2.'">[+]</pan>' : $value->$value2;   
                            $tempname = $liste_intitules[$key2];
                            // print_airB( $data->$tempname,'tempname') ;
                            $tempo .= '<td>-'.$data->$tempname.'-</td>';
                            // $intitules .= '<td>'.$value2.'</td>';
                        }

                        $items6 .= '
                        <tr>
                            '.$tempo.'        
                        </tr>'.PHP_EOL;


                    }

                    $replace_in_vue[$page_cible]['intitules6'] = $intitules ; 
                    $replace_in_vue[$page_cible]['titre6'] = 'Ajout d\'un compte user et de son profil -'.$lastid ; 
                    $replace_in_vue[$page_cible]['test6'] = $items6;
                    $replace_in_vue[$page_cible]['requete6'] = 'nouveau user_id : '.$lastid."<br>".'nouveau profil_id : '.$profil."<br>".$req1."<br>".$req2."<br>".$req3;

















                    // sept
                    $req="SELECT *
                    FROM z_panier
                    LEFT JOIN z_user ON z_user.user_id = z_panier.user_id
                    LEFT JOIN z_product ON z_product.product_id = z_panier.product_id
                    WHERE z_user.user_id = :user_id";
                    $four = $DbAdmin->GetDbActions(1,$req);
                    $items_four = '';
                    $liste_intitules = ['panier_id','user_id','email','name','content','price','panier_id','create_time','update_time',];
                    foreach($four as $key => $value)
                    {   
                        $tempo  = '';
                        $intitules  = '';
                        $tempo .= '<td>tools</td>';
                        $intitules .= '<td>tools</td>';
                        foreach ($liste_intitules as $key => $value2)
                        {
                            $temppo = (strlen($value->$value2) > 32) ? substr($value->$value2, 0, 30).'<pan title="'.$value->$value2.'">[+]</pan>' : $value->$value2;   
                            $tempo .= '<td>'.$temppo.'</td>';
                            $intitules .= '<td>'.$value2.'</td>';
                        }
                        // print_airB($value);
                        $items_four = '
                        <tr>
                            '.$tempo.'        
                        </tr>'.PHP_EOL;
                        $intitules = '
                        <tr>
                            '.$intitules.'        
                        </tr>'.PHP_EOL;
                    }
                    $replace_in_vue[$page_cible]['intitules7'] = $intitules ; 
                    $replace_in_vue[$page_cible]['titre7'] = 'Combien d\'utilisateur on un panier actif, une image associé au profil et un O dans leur mail.'; 
                    $replace_in_vue[$page_cible]['test7'] = $items_four;
                    $replace_in_vue[$page_cible]['requete7'] ="a faire";


                    // -------------------------------------------------------
                    // ----- paquetage des données ----------
                    // -------------------------------------------------------

                    $donnees = [
                        'un' => $replace_in_vue[$page_cible],
                        'deux' => $page_cible
                    ];
                    // }



                    // -------------------------------------------------------
                    // ----- nettoyage des créations de profil ----------
                    // -------------------------------------------------------
                    $req="DELETE FROM z_profil WHERE email = 'patobeur666@gmail.com' AND username = 'patobeur'";
                    $DbAdmin->LocalNettoyage('',$req);
                    $req="DELETE FROM z_user WHERE email = 'patobeur666@gmail.com' AND passwrd = MD5('toto')";
                    $DbAdmin->LocalNettoyage('',$req);
                    



                break;
                case 'articles';
                    $laliste_items = $DbAdmin->get_articles();
                    $items = '';
                    foreach($laliste_items as $key => $value)
                    {   
                        // $rule_icone = (
                        //     $value->rule_id === 1) 
                        //     ? '<i class="fas fa-fw fa-star" title="Admin">' 
                        //     :(
                        //         ($value->rule_id === 2) 
                        //         ? '<i class="fas fa-fw fa-coffee" title="Visitor">'
                        //         :(
                        //             ($value->rule_id === 3) 
                        //             ? '<i class="fas fa-fw fa-user" title="User">' 
                        //             : '<i class="fas fa-fw fa-unlink" title="problème ?">'
                        //         )
                        //     );
                        $items .= '
                        <tr>         
                            <td>#'.$value->product_id.'</td>';
                            // <td>$value->user_id</td>
                            // .product_id,.name,.create_time,.update_time,.stock,.alerte,.cat_id,.price,.vendor_id,.content
                        $items .= '
                            <td>'.$value->name.'</td>
                            <td>'.$value->create_time.'</td>
                            <td>'.$value->update_time.'</td>
                            <td>'.$value->stock.'</td>
                            <td>'.$value->alerte.'</td>
                            <td>'.$value->cat_id.'</td>
                            <td>'.$value->price.'</td>
                            <td>'.$value->vendor_id.'</td>
                            <td>'.$value->content.'</td>';
                            // 
                            // 
                        $items .= '                
                        </tr>'.PHP_EOL;
                    }
                    if (!empty($items))
                    {
                        $replace_in_vue[$page_cible]['TABLE'] = $items;
                        $donnees = [
                            'un' => $replace_in_vue[$page_cible],
                            'deux' => $page_cible
                        ];
                    }

                break;
                case 'profils';
                    $laliste_profils = $DbAdmin->get_profils();
                    $articles = '';
                    foreach($laliste_profils as $key => $value)
                    {   
                        // $rule_icone = (
                        //     $value->rule_id === 1) 
                        //     ? '<i class="fas fa-fw fa-star" title="Admin">' 
                        //     :(
                        //         ($value->rule_id === 2) 
                        //         ? '<i class="fas fa-fw fa-coffee" title="Visitor">'
                        //         :(
                        //             ($value->rule_id === 3) 
                        //             ? '<i class="fas fa-fw fa-user" title="User">' 
                        //             : '<i class="fas fa-fw fa-unlink" title="problème ?">'
                        //         )
                        //     );
                        $articles .= '
                        <tr>
                            <td>
                                <a href="?profilsparutilisateur&user='.$value->user_id.'" class="btn btn-success btn-icon-split btn-sm">
                                <!-- <span class="icon text-white-50"> 
                                    <i class="fas fa-edit"></i>
                                </span> -->
                                <span class="text">Edit</span></a>
                            </td>            
                            <td>#'.$value->profil_id.'</td>';
                            // <td>$value->user_id</td>
                        $articles .= '
                            <td>'.$value->username.'</td>
                            <td>'.$value->firstname.'</td>
                            <td class="masquemail">'.md5($value->email).'</td>
                            <td>'.$value->phone.'</td>
                            <td>'.$value->birthdate.'</td>
                            <td>'.$value->section_id.'</td>
                            <td>'.$value->promo_id.'</td>
                            <td>'.$value->last_update.'</td>
                            <td>'.$value->created.'</td>';
                            // 
                            // 
                        $articles .= '                
                        </tr>'.PHP_EOL;
                    }
                    if (!empty($articles))
                    {
                        $replace_in_vue[$page_cible]['TABLE'] = $articles;
                        $donnees = [
                            'un' => $replace_in_vue[$page_cible],
                            'deux' => $page_cible
                        ];
                    }

                break;
                case 'utilisateurs';
                    $laliste_utilisateur = $DbAdmin->get_users();
                    $articles = '';
                    foreach($laliste_utilisateur as $key => $value)
                    {   
                        $rule_icone = (
                            $value->rule_id === 1) 
                            ? '<i class="fas fa-fw fa-star" title="Admin">' 
                            :(
                                ($value->rule_id === 2) 
                                ? '<i class="fas fa-fw fa-coffee" title="Visitor">'
                                :(
                                    ($value->rule_id === 3) 
                                    ? '<i class="fas fa-fw fa-user" title="User">' 
                                    : '<i class="fas fa-fw fa-unlink" title="problème ?">'
                                )
                            );
                        $articles .= "
                        <tr>
                            <td>$value->user_id</td>
                            <td>$value->email</td>
                            <td>".$rule_icone."</i></td>
                            <td>$value->created</td>
                            <td>$value->updated</td>
                            <td>$value->last_connect</td>

                            <td>".'<a href="?profilsparutilisateur&user='.$value->user_id.'" class="btn btn-success btn-icon-split btn-sm">
                                <!-- <span class="icon text-white-50"> 
                                    <i class="fas fa-edit"></i>
                                </span> -->
                                    <span class="text">Edit</span>
                                </a>'."
                            </td>
                        </tr>
                        ";
                    }
                    if (!empty($articles))
                    {
                        $replace_in_vue[$page_cible]['TABLE'] = $articles;
                        $donnees = [
                            'un' => $replace_in_vue[$page_cible],
                            'deux' => $page_cible
                        ];
                    }

                break;
                case 'profilsparutilisateur';
                    // profil_id	user_id	username	firstname	email	phone	birthdate	section_id	promo_id	last_update	created	activated
                    $laliste_utilisateur = $DbAdmin->LocalProfilsParUtilisateur(1);
                    $articles = '';
                    foreach($laliste_utilisateur as $key => $value)
                    {
                        $articles .= '
                        <tr>
                        <td>#'.$value->profil_id.'</td>';
                        // <td>$value->user_id</td>
                        $articles .= '
                        <td></i>'.$value->username.'</td>
                        <td>'.$value->firstname.'</td>
                        <td>'.$value->email.'</td>
                        <td></i>'.$value->phone.'</td>
                        <td></i>'.$value->birthdate.'</td>';
                        // <td><i class="fas fa-fw fa-coffee">'.$value->section_id.'</td>
                        // <td>'.$value->promo_id.'</td>
                        // <td>'.$value->last_update.'</td>
                        // <td>'.$value->created.'</td>
                        // <td>'.$value->activated.'</td>
                        $articles .= '<td><i class="fas fa-fw fa-edit"></i></td>
                        </tr>
                        ';
                    }
                    if (!empty($articles))
                    {
                        $replace_in_vue[$page_cible]['TABLE'] = $articles;
                        $donnees = [
                            'un' => $replace_in_vue[$page_cible],
                            'deux' => $page_cible
                        ];
                    }
                break;
                case 'tableaudebord';
                    $laliste_utilisateur = $DbAdmin->get_users();
                    $articles = 'rien pour l\'instant';
                        $replace_in_vue[$page_cible]['TABLE'] = $articles;
                        $donnees = [
                            'un' => $replace_in_vue[$page_cible],
                            'deux' => $page_cible
                        ];
                break;
                default:
                    $laliste_utilisateur = $DbAdmin->get_users();
                    $articles = 'rien pour l\'instant';
                        $replace_in_vue[$page_cible]['TABLE'] = $articles;
                        $donnees = [
                            'un' => $replace_in_vue[$page_cible],
                            'deux' => $page_cible
                        ];
                break;
            }
        }
        else
        {
            $page_cible = $default_pagecible;
            $replace_in_vue[$page_cible]['TABLE'] = 'pas de page cible';
            $donnees = [
                'un' => $replace_in_vue[$page_cible],
                'deux' => $page_cible
            ];
        }
    // }
    if (!empty($donnees)){
        $DbAdmin->set_replace_in_vue($donnees['un'],$donnees['deux']);
    }
    $DbAdmin->do_affichelapagehtml();










    function debug_quellepage($page_cible)
    {
        return ($page_cible) 
            ? '<a href="#" class="btn btn-success btn-icon-split btn-sm"><span class="text">Page demandée : '.$page_cible.'</span></a></li>' 
            : '<a href="#" class="btn btn-info btn-icon-split btn-sm"><span class="text">'.$page_cible.'Pas de page demandée -> on ouvre le tableau de bord....</span></a></li>'
            ; 
    }


    function ResponseToHtml($intitules_array,$laliste_items){
        // intitulés
        $intitules  = '
        <tr>
            <td>tools</td>';
        foreach ($intitules_array as $key => $value)
        {
            $intitules .= '<td>'.$value.'</td>';
        }
        $intitules = $intitules.'        
        </tr>'.PHP_EOL;


        $items ='';
        foreach($laliste_items as $key => $value)
        {   
            // print_airB($laliste_items6[$key],'value');
            $tempo  = '';
            $tempo .= '<td>tools</td>';
            $data = $laliste_items[$key];

            foreach ($intitules_array as $key2 => $value2)
            {
                $tempname = $intitules_array[$key2];
                // $tempname = (strlen($tempname) > 32) ? substr($tempname, 0, 30).'<pan title="'.$tempname.'">[+]</pan>' : $tempname;   
                $tempo .= '<td>'.$data->$tempname.'</td>';
            }

            $items .= '
            <tr>
                '.$tempo.'        
            </tr>'.PHP_EOL;

        }
        return [
            'intitules' => $intitules,
            'items' => $items
        ];
    }















    
    // IF DEBUG
    // ici on peut forcer l'affichage du debug si DISTANT est false (en local donc !)
    (!empty($_SESSION['profil'])   AND DEBUG) ? print_airB($_SESSION['profil'],  'PROFIL SESSION') : '';
    (!empty($_SESSION['user'])     AND DEBUG) ? print_airB($_SESSION['user'],    'USER SESSION') : '';
    (!empty($_SESSION['cms'])      AND DEBUG) ? print_airB($_SESSION['cms'],     'CMS SESSION') : '';

?>