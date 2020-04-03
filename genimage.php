<?php
$taille = [];
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
    (!is_int($taille[0]) || !is_int($taille[0]) || (count($taille) > 2)) ? die() : ''; 
    $x = 5;//(int) ($largeur / 2);
    $y = 5;//(int) ($hauteur / 2);
    $content = $taille[0].'x'.$taille[1].'px'; 


    $image = imagecreate($taille[0],$taille[1]);

    $blanc = imagecolorallocate($image, 255,255,255);
    $noir = imagecolorallocate($image, 0, 0, 0);
    $font_size = 10;




    imagestring($image, $font_size, $x, $y, $content, $noir);

    






    header ("Content-type: image/png");
    imagepng($image); // 4 : on a fini de faire joujou, on demande à afficher l'image
?>