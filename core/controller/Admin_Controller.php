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
        ,'actions'
        ,'utilisateurs'
        ,'articles'
        ,'profils'
        ,'profilsparutilisateur'
    ]; 
    
    $DbAdmin = new Admin($timer,'admin');

    $replace_in_vue = [
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

    // if (!empty($_GET))
    // {
        if (!empty($page_cible))
        {
            //si on trouve une page demandée
            switch($page_cible)
            {
                case 'actions';
                    // 1
                    $lettre = "";
                    $idd = null;
                    $limite = '';//" LIMIT 10";
                    $req = "SELECT
product_id,cat_id,vendor_id
FROM z_product
ORDER BY product_id DESC".$limite;
                    $laliste_items = $DbAdmin->actionsFinal($idd,$req);
                    $intitules_array = ['product_id','vendor_id','cat_id'];
                    
                    $product_id = ""; $cat_id = ""; $vendor_id = "";  $num = 0;
                    foreach($laliste_items as $key => $value)
                    {
                        $virg = ($num==0) ? '' : ",";
                        if ( !empty($value->product_id) && !empty($value->product_id) && !empty($value->vendor_id)) {
                            $product_id .= $virg.''.$value->product_id;
                            $cat_id     .= $virg.''.$value->cat_id;
                            $vendor_id  .= $virg.''.$value->vendor_id;
                        $num++;
                        } 
                    }
                    $plus1 = PHP_EOL.'<br>product_id : ('.$product_id.')<br/>';
                    $plus1 .= 'vendor_id : ('.$vendor_id.')<br/>';
                    $plus1 .= 'cat_id : ('.$cat_id.')<br/><br/>';

                    $data_recuphtml = ResponseToHtml($intitules_array,$laliste_items);

                    $replace_in_vue[$page_cible]['intitules1'] = $data_recuphtml['intitules'];
                    $replace_in_vue[$page_cible]['titre1'] = 'liste des paniers (création d\'array pour les futures WHERE IN';   
                    $replace_in_vue[$page_cible]['test1'] =  (!empty($data_recuphtml['items']) ? $data_recuphtml['items'] : null);
                    $replace_in_vue[$page_cible]['requete1'] = (!empty($req) ? $req.$plus1 : 'votre demande est vide !');


                    // 2
                    $lettre = "p";
                    $idd = null;
                    $limite = " LIMIT 10";
                    $req = "SELECT *
,case
    when z_profil.activated LIKE '1' then 'Ok'
    when z_profil.activated LIKE '0' then 'Ko'
    end as situation
,IF(z_profil.last_update IS NULL,'nomod','mod') as modifie
FROM z_profil
WHERE z_profil.username
LIKE '".$lettre."%'".$limite;
                    $laliste_items = $DbAdmin->actionsFinal($idd,$req);
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
                    $req = "SELECT *
FROM z_panier
LEFT JOIN z_user ON z_user.user_id = z_panier.user_id
LEFT JOIN z_product ON z_product.product_id = z_panier.product_id".$limite;
                    $laliste_items = $DbAdmin->actionsFinal($idd,$req);
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
                    $req = "SELECT *
FROM z_panier
LEFT JOIN z_user ON z_user.user_id = z_panier.user_id
LEFT JOIN z_product ON z_product.product_id = z_panier.product_id
WHERE z_panier.user_id = :user_id".$limite;
                    $laliste_items = $DbAdmin->actionsFinal($idd,$req);
                    $intitules_array = ['panier_id','user_id','email','name','content','price','panier_id','create_time','update_time',];
                    $data_recuphtml = ResponseToHtml($intitules_array,$laliste_items);
                    //
                    $replace_in_vue[$page_cible]['intitules4'] = $data_recuphtml['intitules'];
                    $replace_in_vue[$page_cible]['requete4'] = $req;                    
                    $replace_in_vue[$page_cible]['titre4'] = ' Panier de l\'utilisateur avec l\'user_id = '.$idd; 
                    $replace_in_vue[$page_cible]['test4'] =  $data_recuphtml['items'];



                    // cinquième
                    $req="SELECT *
FROM z_panier
LEFT JOIN z_user ON z_user.user_id = z_panier.user_id
LEFT JOIN z_product ON z_product.product_id = z_panier.product_id
WHERE z_user.user_id = :user_id";
                    $laliste_items4 = $DbAdmin->actionsFinal(1,$req);
                    $items4 = '';
                    $current_array = ['panier_id','user_id','email','name','content','price','panier_id','create_time','update_time',];
                    foreach($laliste_items4 as $key => $value)
                    {   
                        $tempo  = '';
                        $intitules  = '';
                        $tempo .= '<td>tools</td>';
                        $intitules .= '<td>tools</td>';
                        foreach ($current_array as $key => $value2)
                        {
                            $temppo = (strlen($value->$value2) > 32) ? substr($value->$value2, 0, 30).'<pan title="'.$value->$value2.'">[+]</pan>' : $value->$value2;   
                            $tempo .= '<td>'.$temppo.'</td>';
                            $intitules .= '<td>'.$value2.'</td>';
                        }
                        // print_airB($value);
                        $items4 = '
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
                    $replace_in_vue[$page_cible]['test5'] = $items4;
                    $replace_in_vue[$page_cible]['requete5'] ="a faire";





                    // sixième
                    $req1="INSERT INTO z_user
(user_id, email, passwrd, rule_id, created, updated, last_connect)
VALUES (NULL, 'patobeur@gmail.com', MD5('toto'), '3', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
";
$req2  ="INSERT INTO z_profil 
(profil_id, user_id, username,firstname, email, phone, birthdate, section_id, promo_id, last_update, created, activated) 
VALUES 
(NULL, :user_id, 'patobeur', 'etlardons', 'patobeur2@gmail.com', '0609080808', '2019-04-16', '2', '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '1');
";

$req3="SELECT * FROM z_profil LIMIT 1";

$vide='';
                    $lastid = $DbAdmin->insert_actions(null,$req1);
                    $profil = $DbAdmin->insert_actions($lastid ,$req2);
                    // $profil = $DbAdmin->insert_actions(["col" => "user_id", "val" => '$lastid'] ,$req2);


                    $laliste_items6 = $DbAdmin->actionsFinal(1,$req3);

                    // print_airB($laliste_items6,'jj');

                    $items6 = '';
                    $current_array = ['profil_id','user_id','username','firstname','email','phone','birthdate','section_id','promo_id','last_update','created','activated'];

                    // intitulés
                    $intitules  = '
                    <tr>
                        <td>tools</td>';
                    foreach ($current_array as $key => $value2)
                    {
                        $intitules .= '<td>'.$value2.'</td>';
                    }
                    $intitules = $intitules.'        
                    </tr>'.PHP_EOL;


                    foreach($laliste_items6 as $key => $value)
                    {   
                        // print_airB($laliste_items6[$key],'value');
                        $tempo  = '';
                        $tempo .= '<td>tools</td>';
                        $data = $laliste_items6[$key];

                        foreach ($current_array as $key2 => $value2)
                        {
                            // $temppo = (strlen($value->$value2) > 32) ? substr($value->$value2, 0, 30).'<pan title="'.$value->$value2.'">[+]</pan>' : $value->$value2;   
                            $tempname = $current_array[$key2];
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
                    $laliste_items4 = $DbAdmin->actionsFinal(1,$req);
                    $items4 = '';
                    $current_array = ['panier_id','user_id','email','name','content','price','panier_id','create_time','update_time',];
                    foreach($laliste_items4 as $key => $value)
                    {   
                        $tempo  = '';
                        $intitules  = '';
                        $tempo .= '<td>tools</td>';
                        $intitules .= '<td>tools</td>';
                        foreach ($current_array as $key => $value2)
                        {
                            $temppo = (strlen($value->$value2) > 32) ? substr($value->$value2, 0, 30).'<pan title="'.$value->$value2.'">[+]</pan>' : $value->$value2;   
                            $tempo .= '<td>'.$temppo.'</td>';
                            $intitules .= '<td>'.$value2.'</td>';
                        }
                        // print_airB($value);
                        $items4 = '
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
                    $replace_in_vue[$page_cible]['test7'] = $items4;
                    $replace_in_vue[$page_cible]['requete7'] ="a faire";

                    




                        // -------------------------------------------------------
                        // ----- paquetage des données ----------
                        // -------------------------------------------------------
                        $donnees = [
                            'un' => $replace_in_vue[$page_cible],
                            'deux' => $page_cible
                        ];
                    // }













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
                    $laliste_utilisateur = $DbAdmin->get_profilsparutilisateur();
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

    $DbAdmin->set_replace_in_vue($donnees['un'],$donnees['deux']);
    $DbAdmin->do_affichelapagehtml();
    // IF DEBUG
    // ici on peut forcer l'affichage du debug si DISTANT est false (en local donc !)
    // (!empty($_SESSION['profil'])   AND DEBUG) ? print_airB($_SESSION['profil'],  'PROFIL SESSION') : '';
    // (!empty($_SESSION['user'])     AND DEBUG) ? print_airB($_SESSION['user'],    'USER SESSION') : '';
    // (!empty($_SESSION['cms'])      AND DEBUG) ? print_airB($_SESSION['cms'],     'CMS SESSION') : '';


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
                    // $replace_in_vue[$page_cible]['intitules2'] = $intitules;   
                    // $replace_in_vue[$page_cible]['titre2'] = 'Profils d\'utilisateur dont le nom commence par a';   
                    // $replace_in_vue[$page_cible]['test2'] = $items;
                    // $replace_in_vue[$page_cible]['requete2'] = $req2;
    }
?>