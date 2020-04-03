<?php
// ConTroller > Profil

if (!empty($_SESSION['profil']))
{
    $replace_in_vue = [
        'TITRE'         => 'Page Panier'
    ];
    $donnees = [
        'tita' => 'tita'
    ];


    // if (!$_POST)
    // {
        $replace_in_vue = [
            'TITRE'         => 'Page Panier'
        ];
        


    // }
    Page::set_replace_in_vue($replace_in_vue);
}
?>