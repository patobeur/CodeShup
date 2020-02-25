<?php
    function VISITOR(){                                             // La fonction c'est pratique
            $file = 'log/visites.txt';                                  // le fichier est planqué avant le root (www)
            
            if(file_exists($file)){                                 // si le fichier existe
                    $compteur_f = fopen($file, 'r+');               // on ouvre le fichier en ecriture
                    $compte = fgets($compteur_f);}                  // on prends la valeur stockée (on attend un numérique bien entendus)
            else {                                                  // si le fichier n'existe pas
                    die();
                    //$compteur_f = fopen($file, 'a+');               // sinon on le crée
                    //$compte = 0;                                    //  et on colle 0 dedans ;)
            }
            if( !isset($_SESSION['VISITOR']) || $_SESSION['VISITOR'] != 'VISITOR' ){
                    // SI PAS DE SESSION on te compte
                    $compte++;                                      // on ajoute 1 au compteur ++
                    $_SESSION['VISITOR'] = 'VISITOR';               // on colle une betise en session pour pouvoir la tester au passage suivant
                    $_SESSION['TICKET'] = $compte;                  // je rajoute le numero en session, ca ne me sert a rien.... pour l'instant ;)
                    fseek($compteur_f, 0);                          // je remet le curseur au début du fichier ouvert (je crois ??!!)
                    fputs($compteur_f, $compte);                    // je remplace le contenu (compte) du fichier par le nouveau (compte++)
            }
            fclose($compteur_f);                                    // je ferme de fichier txt !
            //return '<div id="visites">'.$compte.' visites</div>';   // un return mais ca ne me sert a rien.... pour l'instant ;)
            return $compte;
    }
    $valeurderetour = 'Hit:'.VISITOR();
?>