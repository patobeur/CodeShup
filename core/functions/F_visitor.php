<?php
    $valeurderetour = '';
        function VISITOR(){                                                     // La fonction c'est pratique 
                if( !empty($_SESSION['cms']['user']['done'])){
                        $file = 'core/log/visites.txt';                         // le fichier est planqué avant le core ?? pas encore
                        
                        if(file_exists($file)){                                 // si le fichier existe
                                $fileCounter = fopen($file, 'r+');              // on ouvre le fichier en ecriture
                                $compte = (int) fgets($fileCounter);            // on prends la valeur stockée (on attend un numérique bien entendu)      
                        }
                        else {                                                  // si le fichier n'existe pas
                                // EXTRA ------------------------------------------------------------
                                $_SESSION['cms']['errors'][] = "Le fichier '$file' n'existe pas...";
                                // FIN EXTRA --------------------------------------------------------

                                $fileCounter = fopen($file, 'a+');              // sinon on le crée
                                $compte = 1;                                    // et met compte à 1
                        }
                        if(file_exists($file)){                                 // si le fichier existe
                                $compte++;                                      // on ajoute 1 au compteur ++
                                $_SESSION['cms']['user']['done'] = true;        // on colle true en session pour pouvoir la tester au passage suivant
                                $_SESSION['cms']['user']['count'] = $compte;    // on colle true en session pour pouvoir la tester au passage suivant
                                $_SESSION['cms']['user']['tekken'] = getToken();// je rajoute le numero en session, ca ne me sert a rien.... pour l'instant ;)

                                fseek($fileCounter, 0);                         // je remet le curseur au début du fichier ouvert (je crois ??!!)
                                fputs($fileCounter, $compte);                   // je remplace le contenu (compte) du fichier par le nouveau (compte++)
                                fclose($fileCounter);                           // je ferme le fichier txt !
                                return $compte;
                        }
                        else
                        {
                                $_SESSION['cms']['errors'][] = "Le fichier '$file' n'a pas été créé...";  
                        }
                }
        }
        function getToken()
        {
            return md5(rand(1, 10) . microtime());
        }
    $valeurderetour = 'Hit:'.VISITOR();
?>