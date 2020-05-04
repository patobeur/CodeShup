<?php

$replace_in_vue = [
    $page_cible => [
        'TITRE' =>          'Mon profil Utilisateur',
        'USERNAME' =>       '',  
        'profil_id' =>      null,
        'username' =>       '',
        'firstname' =>      '',
        'email' =>          '',
        'phone' =>          '',
        'birthdate' =>      '',
        'section_id' =>     null,
        'promo_id' =>       null,
        'last_update' =>    '',
        'created' =>        '',
        'activated' =>      null,
        'pays' =>           '',
        'ville' =>          '',
        'zip' =>            '',
        'adresse' =>        '',
        'adresse2' =>       '',
        'zip' =>            '',
        'pseudo' =>         '',
        'postattributs' =>  'action="?myprofil" method="post"'
    ]
];
$p = PHP_EOL;
        
$intitules_array = ['profil_id','username','firstname','email','phone','birthdate','section_id','promo_id','last_update','created','activated','pays','ville','zip','adresse'];
$select = implode(",", $intitules_array);

$idd = 1;
$limite = '';
$req = "SELECT ".$select.$p."FROM z_profil".$p."WHERE z_profil.user_id = ".$idd.$p."ORDER BY z_profil.created ASC" ;

$laliste_items = $DbAdmin->GetDbActions($idd,$req);

foreach($laliste_items[0] as $key => $value){
    $replace_in_vue[$page_cible][$key] = $value;
}

?>