<?php

    // $taille = [];//theme/font/Braille
    // $font = 'arial.ttf';
    // if (!empty($_GET) and !empty($_GET['img']))
    // {
    //     $demande = $_GET['img'];
    //     $taille = explode("x", $demande);
    //     $taille[0] = (int) $taille[0];
    //     $taille[1] = (int) $taille[1];    
    // }
    // else{
    //     $taille[0] = 200;
    //     $taille[1] = 200;
    // }
    // (!is_int($taille[0]) || !is_int($taille[0]) || (count($taille) > 2)) ? die() : ''; 
    // $x = 5;//(int) ($largeur / 2);
    // $y = 5;//(int) ($hauteur / 2);
    // $content = $taille[0].'x'.$taille[1]; 


    // $image = imagecreatetruecolor($taille[0],$taille[1]);

    // $gris = imagecolorallocate($image, 211, 211, 211);
    // $noir = imagecolorallocate($image, 0, 0, 0);
    // $blanc = imagecolorallocate($image, 255,255,255);
    
    // imagefilledrectangle($image, 1, 1, ($taille[0]-2), ($taille[1]-2), $blanc);
    // $font_size = 5;
    
    // header ("Content-type: image/png");
    // imagestring($image, $font_size, $x, $y, $content, $noir);
    // // $font = 'FFF_Tusj.ttf';
    // // imagettftext($image, 20, 0, 11, 21, $grey, $font, $content);
    
    // imagepng($image); // afficher l'image
    // imagedestroy($image); // destroy





















    
    $taille = [];//theme/font/Braille
    //$font = 'arial.ttf';
    if (!empty($_GET) and !empty($_GET['img']))
    {
        $demande = $_GET['img'];
        $taille = explode("x", $demande);

        $taille[0] = (int) $taille[0];
        $taille[1] = (int) $taille[1];  

    }
    else{
        $taille[0] = 200;
        $taille[1] = 50;
    }
    (!is_int($taille[0]) || !is_int($taille[1]) || (count($taille) > 2) || (!empty($_GET['ol']) AND isset($_GET['ol']))) ? die() : ''; 
    $x = (int) ($taille[0] / 2)-20;
    $y = (int) ($taille[1] / 2)-5;
    $content = $taille[0].'x'.$taille[1]; 
    $image = imagecreatetruecolor($taille[0],$taille[1]);


    $gris = imagecolorallocate($image, 211, 211, 211);
    $noir = imagecolorallocate($image, 0, 0, 0);
    $blanc = imagecolorallocate($image, 255,255,255);

    $couleurfond ='blanc';
    $couleurtexte = 'noir';

    if (isset($_GET['nb']) )
    {
        $couleurfond ='noir';
        $couleurtexte = 'blanc';
    }
    if ( !empty($taille[0]) AND !empty($taille[1]) AND isset($_GET['ol']) )
    {
        imagefilledrectangle($image, 1, 1, ($taille[0]-2), ($taille[1]-2), $$couleurfond);
        $texte_color = $$couleurtexte;
    }
    elseif ( !empty($taille[0]) AND !empty($taille[1]) AND !isset($_GET['ol']) )
    {
        imagefilledrectangle($image, 0, 0, ($taille[0]), ($taille[1]), $$couleurfond);
        $texte_color = $$couleurtexte;
    }

    $font_size = 5;
    header ("Content-type: image/png");
    imagestring($image, $font_size, $x, $y, $content, $texte_color);
    // $font = 'FFF_Tusj.ttf';
    // imagettftext($image, 20, 0, 11, 21, $grey, $font, $content);
    
    imagepng($image); // afficher l'image
    // imagedestroy($image); // destroy
?>