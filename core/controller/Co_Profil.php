<?php
// ConTroller > Profil

if (!empty($_SESSION['profil']))
{
    $replace_in_vue = [
        'TITRE'         => 'Page Profil'
        ,'ruleset'      => ''
        ,'rule_id'      => ''
        ,'username'     => ''
        ,'firstname'    => ''
        ,'email'        => ''
        ,'section_id'   => ''
        ,'promo_id'     => ''
        ,'last_update'  => ''
        ,'created'      => ''
        ,'activated'    => ''
    ];
    $donnees = [
        'tita' => 'tita'
    ];


    // if (!$_POST)
    // {
        $replace_in_vue = [
            'TITRE'         => 'Page Profil'
            ,'ruleset'      => $_SESSION['profil']['ruleset']
            ,'username'     => $_SESSION['profil']['username']
            ,'firstname'    => $_SESSION['profil']['firstname']
            ,'email'        => $_SESSION['profil']['email']
            ,'rule_id'      => $_SESSION['profil']['rule_id']
            ,'last_update'  => $_SESSION['profil']['last_update']
            ,'section_id'   => $_SESSION['profil']['section_id']
            ,'promo_id'     => $_SESSION['profil']['promo_id']
            ,'created'      => $_SESSION['profil']['created']
            ,'activated'    => $_SESSION['profil']['activated']
        ];
        


    // }
    Page::set_replace_in_vue($replace_in_vue);
}
?>