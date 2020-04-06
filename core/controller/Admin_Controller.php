<?php
// ConTroller principal > Controller
    // if(empty($_SESSION['profil']['username'])) {die();}
    $timer = microtime(true);
    if (isset($_GET['kill'])) header("Location:../deco.php" );
    include('../'.'core/ini/definitions.php'); // MonkeyBusiness
    include('../'.AAINI.'bdd.php');
    include('../'.AAINI.'session.php');
    require_once('../'.'core/toolbox.php');
    $pagespossibles = [
        'tableaudebord'
        ,'utilisateurs'
        ,'profilsparutilisateur'
    ]; 
    $default_pagecible = 'tableaudebord';

    // $Db = new Db();
    // $User = new User();
    // $Page = new Page();
    $PageAdmin = new Admin($timer,'admin');
    
    // $Page->do_affichelapagehtml();
    

    $replace_in_vue = [
        'admin' => [
            'TITRE'         => 'Page Admin'
            ,'USERNAME'      => $_SESSION['profil']['username']
            ,'USERAVATAR'    => '../genimage.php?img=50x50&nb'
            // ,'IMG1'          => '../genimage.php?img=60x60'
            // ,'IMG2'          => '../genimage.php?img=60x60'
            // ,'IMG3'          => '../genimage.php?img=60x60'
            ,'ICI'           => 'toto'
            ,'VUES' =>       '{{vue}}'
        ],
        'tableaudebord' => [
            'test'         => 'Tableau de bord'
        ],
        'utilisateurs' => [
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
            // print_airB($key,"key");
            $page_cible = (in_array ($key, $pagespossibles, true)) ? $key : false;
            break;
        }
        // debug
        $replace_in_vue['admin']['ICI'] = debug_quellepage($page_cible);
        
    }
    // else{
    //     $page_cible = $default_pagecible;
    //     $replace_in_vue[$page_cible]['TABLE'] = 'gogogogo';
    //     $donnees = [
    //         'un' => $replace_in_vue[$page_cible],
    //         'deux' => $page_cible
    //     ];
    //     // $UriIndex = explode('/', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
    //     // $uri = $_SERVER['REQUEST_URI'];
    //     // $r = array_filter($r);
    // }

    $PageAdmin->set_replace_vue($replace_in_vue['admin'],'admin');




    // if (!empty($_GET))
    // {
        if (!empty($page_cible))
        {
            //si on trouve une page demandée
            switch($page_cible)
            {
                case 'utilisateurs';
                    $laliste_utilisateur = $PageAdmin->get_users();
                    $articles = '';
                    foreach($laliste_utilisateur as $key => $value)
                    {   
                        $rule_icone = (
                            $value->rule_id == 1) 
                            ? '<i class="fas fa-fw fa-star" title="Admin">' 
                            :(
                                ($value->rule_id == 2) 
                                ? '<i class="fas fa-fw fa-coffee" title="Visitor">'
                                :(
                                    ($value->rule_id == 3) 
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
                        $replace_in_vue['utilisateurs']['TABLE'] = $articles;
                        $donnees = [
                            'un' => $replace_in_vue[$page_cible],
                            'deux' => $page_cible
                        ];
                    }

                break;
                case 'profilsparutilisateur';
                    // profil_id	user_id	username	firstname	email	phone	birthdate	section_id	promo_id	last_update	created	activated
                    $laliste_utilisateur = $PageAdmin->get_profilsparutilisateur();
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
                        // print_airB($replace_in_vue,'rr',1);
                    }
                break;
                case 'tableaudebord';
                    $laliste_utilisateur = $PageAdmin->get_users();
                    $articles = 'rien pour l\'instant';
                        $replace_in_vue[$page_cible]['TABLE'] = $articles;
                        $donnees = [
                            'un' => $replace_in_vue[$page_cible],
                            'deux' => $page_cible
                        ];
                break;
                // default:
                //     $page_cible = $default_page_cible;
                //     $articles = 'vide';
                //     $replace_in_vue[$page_cible]['TABLE'] = 'vide';
                // break;
            }
        }
        else
        {
            $page_cible = $default_pagecible;
            $replace_in_vue[$page_cible]['TABLE'] = 'gogogogo';
            $donnees = [
                'un' => $replace_in_vue[$page_cible],
                'deux' => $page_cible
            ];
        }
    // }

    $PageAdmin->set_replace_in_vue($donnees['un'],$donnees['deux']);




    $PageAdmin->do_affichelapagehtml();
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


?>