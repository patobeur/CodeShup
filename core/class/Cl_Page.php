<?php
// Class Page
class Page{
    private $_Nlog = 0;
    private $_ObjJson;
    private $_flash_time;
    private $_User;
    private $_Db;

    private $_current_page='';
    private $_default_page='';
    private $_Originum=0;

    private $_replace_in_vue = [];
    
    public function __construct($timer){
        $this->_flash_time = $timer;
        if (file_exists(AAJSONCONTEN)) 
        {
            $this->_ObjJson =  $this->get_Jsondecode(AAJSONCONTEN);
        }
        else
        {
            echo 'il manque le fichier base';
        }
        // $this->_ObjJson2 = $this->get_Jsondecode(AAJSONHEADER);
        $this->_current_page = $this->_ObjJson->defaultpage[0];
        $this->_default_page = $this->_ObjJson->defaultpage[0];
        $this->_Db = new Db();
        
    }
    // PUBLIC FUNCTIONS
    // GETTER
    // --------------------------------------------------------------------------------
    public function do_affichelapagehtml(){
        $this->_current_page = $this->get_Current_Page();
        echo $this->get_Dom();
    }
    // --------------------------------------------------------------------------------
    private function get_Dom()
    {
        $dom_blocs = $this->get_Header_Html(1);
        $dom_blocs .= $this->get_Contents_Html();

        // dernière minute
        $github = DISTANT ? '<span class="credit"><a class="github-button" href="https://github.com/patobeur" data-color-scheme="no-preference: dark; light: dark; dark: dark;" aria-label="Follow @patobeur on GitHub">Follow @patobeur</a><script async defer src="https://buttons.github.io/buttons.js"></script></span>' : '';
        $dom_blocs = preg_replace('_{{GITHUB}}_',$github, $dom_blocs);
        $dom_blocs = preg_replace('_{{FLASH}}_','chargement de la page en '.($this->_flash_time-microtime(true))." Microsecondes environs, tout est relatif, hein !", $dom_blocs);
        
        return $dom_blocs;
    }

    // USEFUL IN NAVIGATION MENU
    private function get_List_Menu($kelfamille)
    {
        $n=PHP_EOL;
        // NAVIGATION        
        $fichier_importe = $this->get_File_to_use('get_contents',AANAVBAR,"file_get_contents",$this->get_errorphrase('',__FUNCTION__,__LINE__));
        // ------------------------------------------------------------------------------
        $arr_pc = $this->_ObjJson->$kelfamille;                            // arr_pc = pages courantes (arr_ pour array)
        $coment_blocs = $this->get_Indent(0,4,'Navigat')."<!-- Auto in menu -->".$n;
        for ($oo=0; $oo < count($arr_pc); $oo++){
            // ici le lien active
            $famille = $arr_pc[$oo];                                       // $ARRRAIEE[$kelfamille][$oo]
            $enfant = $this->_ObjJson->$famille;                           // $ARRRAIEE[$ARRRAIEE[$kelfamille][$oo]]
            if ($this->_current_page == $famille) {$active = ' active';} else  {$active = '';}
            
            $coment_blocs .= $this->get_Indent(0,4,'Navigat').'<a class="dropdown-item'.$active.'" href="?'. $famille.'"><span>📄</span> '.$enfant->title.'</a>'.$n;
        } 
        $coment_blocs .= $this->get_Indent(0,4,'Navigat')."<!-- Fin Auto in menu -->";
        if ($this->_current_page == 'index') {$is_activ = ' active';} else  {$is_activ = '';} 

        $blocLogin = '';

        //BLOC LOGIN (blocLogin)
        if ($this->_current_page != 'login')
        {
            // 
            if (empty($_SESSION['profil']))
            {
                $blocLogin = '
                    <!-- login menu -->
                    <li class="nav-item">
                        <a class="nav-link" href="?login" title="Accueil">☉ Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="deco.php" title="Deco">☉ Deco</a>
                    </li>'.PHP_EOL;

                if (!empty($_SESSION['profil']['level']) && $_SESSION['profil']['level'] == 'admin' AND DEBUG){  
                    $blocLogin .= '
                    <li class="nav-item">
                        <a class="nav-link" href="deco.php" title="Deco">☉ Deco</a>
                    </li>'.PHP_EOL;
                }

                $blocLogin .= '
                    <!-- Fin login menu -->'.PHP_EOL;
            }
            else
            {
                $blocLogin = '
                    <!-- Auto out menu -->
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="gestionprofil"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                            Profil
                        </a>
                        <div class="dropdown-menu" aria-labelledby="gestionprofil">
                            <!-- Profil -->
                                <a class="dropdown-item" href="?profil" title="Profil">☉ Profil</a>
                                <a class="dropdown-item" href="?panier" title="Panier">☉ Panier</a>
                            <!-- Fin Profil -->
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" title="Deco" href="deco.php">☉ Deco</a>
                        </div>
                    </li>
                    <!-- Fin Auto out menu -->'.PHP_EOL;
            }
        }
        elseif ($this->_current_page == 'login')// && $this->_User['statut'] == 'visitor')
        {
            $blocLogin = PHP_EOL;

        }
        
        $valeurderetour = preg_replace("_NAVIGATATOR_",$coment_blocs, $fichier_importe); 
        $valeurderetour = preg_replace("_ACTIVITE_",$is_activ, $valeurderetour);
        // ------------------------------------------------------------------------------
        $valeurderetour = preg_replace('_ACTOBEURTWO_',$this->get_Navigation_Menu('pagesgestion','files'), $valeurderetour);
        $valeurderetour = preg_replace('_ACTOBEUR_',$this->get_Navigation_Menu('pagesext','files'), $valeurderetour);
        // ------------------------------------------------------------------------------

        $valeurderetour = preg_replace("_LOGINATOR_",$blocLogin, $valeurderetour); 
        return $valeurderetour; 
    }
    // --------------------------------------------------------------------------------
    /**
     * 
     */
    private function get_Navigation_Menu($kelfamille,$sousfamille){        
        // $Tablo_Familles = $this->_ObjJson->$kelfamille;
        $Tablo_Enfants = $this->_ObjJson->$kelfamille->$sousfamille;
        $A_remplacement = '';
        // printair( $this->_ObjJson->$kelfamille->$sousfamille);
        // print(count($this->_ObjJson->$kelfamille->$sousfamille));
        for ($numFam=0; $numFam < count($this->_ObjJson->$kelfamille->$sousfamille); $numFam++)
        {
            // ici le lien active
            $target = ($Tablo_Enfants[$numFam]->target!='') ? 
                ' target="'.$Tablo_Enfants[$numFam]->target.'"' : 
                '';
            // ici le title
            $title = ($Tablo_Enfants[$numFam]->title!='') ? 
                ' title="'.$Tablo_Enfants[$numFam]->title.'"' : 
                '';
            //echo($target);
            $A_remplacement .= $this->get_Indent(0,5,'get_Navigation_Menu');
            $A_remplacement .= '<a class="dropdown-item" href="'.$Tablo_Enfants[$numFam]->href.'"'.$target.$title.'>'.$Tablo_Enfants[$numFam]->title.'</a>'.PHP_EOL;
        } 


        $A_html = '                                <!-- Auto out menu -->
                                <li class="nav-item dropdown">
                                    <a
                                        class="nav-link dropdown-toggle"
                                        href="#"
                                        id="'.$this->_ObjJson->$kelfamille->nomlabel.'"
                                        role="button"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">☉ '.$this->_ObjJson->$kelfamille->nommenu.'</a>
                                    <div class="dropdown-menu" aria-labelledby="'.$this->_ObjJson->$kelfamille->nomlabel.'">
                                        <!-- Auto out '.$this->_ObjJson->$kelfamille->nommenu.' -->
MENUACTOBEUR
                                        <!-- Fin out '.$this->_ObjJson->$kelfamille->nommenu.' -->
                                        <!-- <div class="dropdown-divider"></div>
                                        <a class="dropdown-item">End</a> -->
                                    </div>
                                </li>
                                <!-- Fin Auto out menu -->';
        return $this->get_RemplacePar('_MENUACTOBEUR_',$A_remplacement, $A_html);
    }
    // --------------------------------------------------------------------------------
    private function get_RemplacePar($txtaenlever,$txtamettre,$html){        
        return ($txtamettre and $txtamettre!='') ? preg_replace($txtaenlever,$txtamettre,$html) : preg_replace($txtaenlever,'',$html);
    }
    // --------------------------------------------------------------------------------
	private function get_Header_Html($nb_space=0){
        $n=PHP_EOL;$jusquaHead="";
        $jusquaHead .= $this->get_all_metas('head',"meta","head",0);                // get all meta from json
        $jusquaHead .= $this->get_head_meta_html('atouslescoups','css',1);          // get meta from json 
        $jusquaHead .= $this->get_head_meta_html($this->_current_page,'css',1);     // get css from the wanted page
        $jusquaHead .= $this->get_head_meta_html($this->_current_page,'title',1);     // get css from the wanted page

        // head html
        $jusquaHead = $this->get_Indent($this->_Originum,1,'Gen_Head1')
            .'<head>'.$jusquaHead.$n.$this->get_Indent($this->_Originum,1,'Gen_Head2').'</head>';

        $jusquaHead = $this->get_Indent($this->_Originum,1,'Gen_Head3').
            "<!-- ". $this->_current_page ." -->".$n.$this->get_Indent($this->_Originum,$nb_space,'Gen_Head4').
            $jusquaHead.$n.$this->get_Indent($this->_Originum,$nb_space,'Gen_Head5')."<!-- End's  ".
            $this->_current_page ." -->".$n;

        if (isset($this->_ObjJson->structure->meta->lang)){    $jusquaHead =  $this->_ObjJson->structure->meta->lang.$n.$jusquaHead;}
        if (isset($this->_ObjJson->structure->meta->doctype)){ $jusquaHead =  $this->_ObjJson->structure->meta->doctype.$n.$jusquaHead;}
        // eco($jusquaHead);
        return $jusquaHead;
    }
    // GETTER
    private function get_Contents_Html(){                                           // ici on construit le body
        $n=PHP_EOL;
        $body_blocs="\n";
        $body_blocs .= $this->get_Indent($this->_Originum,1,'get_Contents_Html').'<body id="top">'.$n;
        $body_blocs .= $this->get_Indent($this->_Originum,2,'get_Contents_Html').'<div class="fullpage">'.$n;


        //
        $header = $this->get_RemplacePar('_URLROOT_', AAROOT, $this->get_List_Menu('pages'));
        $header = $this->get_RemplacePar('_LOGOSRC_', $this->_ObjJson->CHARTE->NAV->IMGROOT . $this->_ObjJson->CHARTE->NAV->LOGOSRC, $header);


        // generation des pages a integrer dans le body en dessous de navigation mais en dessus du footer
        $cp = $this->_current_page;                                                 // cp = current page
        // --------------------------------------------------------------------------------------------
        // ----------------------------------- GET CURRENT CLASS --------------------------------------
        if (!empty($this->_ObjJson->$cp->class)){                                   // si il y'a des pages dans class
            $cc = $this->_ObjJson->$cp->class;                                      // cc = current bloc/class
            $tempovalue = count($cc);                                               // je prend la liste des class a intégrer

            for ($numFichier = 0; $numFichier < $tempovalue; $numFichier++){        // on boucle sur les class trouvés 
                $class_file = AACLASSE.ucfirst($cc[$numFichier]).AAEXTPHP;          // fichier a require
                $this->get_File_to_use('class',$class_file,"include",$this->get_errorphrase('',__FUNCTION__,__LINE__));
            }
        }
        // --------------------------------------------------------------------------------------------
        // ----------------------------------- GET CURRENT CONTROLLER ---------------------------------
        if (!empty($this->_ObjJson->$cp->controller)){                              // si il y'a des pages dans require
            $tempovalue = count($this->_ObjJson->$cp->controller);                  // je prend la liste des Controller a intégrer
            $requires = $this->_ObjJson->$cp->controller;                           // cp = current bloc/require

            for ($numFichier = 0; $numFichier < $tempovalue; $numFichier++){        // on boucle sur les require trouvés 
                $req_file = AACONTROLEUR.ucfirst($requires[$numFichier]).AAEXTPHP;  // fichier a require
                $this->get_File_to_use('controller',$req_file,"include",$this->get_errorphrase('',__FUNCTION__,__LINE__));
            }
        }
        // --------------------------------------------------------------------------------------------
        // ----------------------------------- GET CURRENT require ----------------------------------
        if (!empty($this->_ObjJson->$cp->require)){                                 // si il y'a des pages dans require
            $tempovalue = count($this->_ObjJson->$cp->require);                     // je prend la liste des Controller a intégrer
            $requires = $this->_ObjJson->$cp->require;                              // cp = current bloc/require

            for ($numFichier = 0; $numFichier < $tempovalue; $numFichier++){        // on boucle sur les require trouvés 
                $req_file = AAFONCTION.$requires[$numFichier].AAEXTPHP;             // fichier a require
                $this->get_File_to_use('require',$req_file,"include",$this->get_errorphrase('',__FUNCTION__,__LINE__));
            }
        }
        // --------------------------------------------------------------------------------------------
        // ----------------------------------- GET CURRENT PAGES BLOCS --------------------------------
        if ($this->_ObjJson->$cp->blocs){                                           // si il y'a des pages dans blocs dans le json
            $tempovalue = count($this->_ObjJson->$cp->blocs);                       // je prend la liste des pages _in_ a intégrer
            $fichiers = $this->_ObjJson->$cp->blocs;                                // cp = current bloc/page

            for ($numFichier = 0; $numFichier < $tempovalue; $numFichier++){        // on boucle sur les pages trouvées 
                $get_file = AAINVUE.$fichiers[$numFichier].AAEXTPHP;                // fichier pour file_get_contents

                $body_blocs .= $this->get_File_to_use('vue',$get_file,"file_get_contents",$this->get_errorphrase('',__FUNCTION__,__LINE__));
                // ------------------------------------------
                // ------------------------------------------
                // ------------------------------------------
                // ici test pour modifier la vue a la volée
                if($this->get_replace_in_vue())
                {   
                    foreach($this->get_replace_in_vue() as $key => $value)
                    {
                        $body_blocs = str_replace("{{".$key."}}" , $value, $body_blocs);
                    }
                }
                // ------------------------------------------
                // ------------------------------------------
                // ------------------------------------------
                // ------------------------------------------ 
            }
        }
        // $body_blocs .= $header;
        // --------------------------------------------------------------------------------------------
        // --------------------------------------------------------------------------------------------
        $body_blocs = $header.$this->get_Pageaouvriratouslescoups('files').$n.$body_blocs;
        // --------------------------------------------------------------------------------------------

        // FOOTER
        $body_blocs .= file_exists(AAFOOTER)                                         // bloc footer
            ? $this->get_File_to_use('get_contents',AAFOOTER,"file_get_contents",$this->get_errorphrase('',__FUNCTION__,__LINE__))
            : false; 
        // --------------------------------------------------------------------------------------------
        $body_blocs .= $this->get_end_js_html($this->_current_page,'js',2);       // bloc js header de la page appelée
        $body_blocs .= $this->get_Indent($this->_Originum,2,'rien').'</div>'.$n;     // on ferme le div du début <div class="fullpage">
        $body_blocs .= $this->get_end_js_html($this->_current_page,'js',1);          // bloc js a mettre en fin de page
        $body_blocs .= $this->get_end_js_html('atouslescoups','js',1);               // bloc js a mettre en fin de page

        $body_blocs .= $this->get_Indent($this->_Originum,1,'rien').'</body>'.$n;// on ferme le body
        $body_blocs .= '</html>'.$n;
        // --------------------------------------------------------------------------------------------
        // --------------------------------------------------------------------------------------------
        // --------------------------------------------------------------------------------------------
        // ON A NOS CLASS ET FONCTION 
        return $body_blocs;
    }
    // --------------------------------------------------------------------------------
	private function get_end_js_html($famille='atouslescoups',$nomchamp,$Origine=0){
        $n=PHP_EOL;$message = '';
        $sourceJson = $this->_ObjJson->$famille;
            $cc = $sourceJson->$nomchamp; // cc = current champs
            $tempovalueout = count($cc);
            if ($tempovalueout > 0 ){
                for ($num_item = 0; $num_item < $tempovalueout; $num_item++){
                    $message .= $this->get_Indent($Origine,1,'get_end_js_html').'<script src="'.AAJS.$cc[$num_item].'" type="text/javascript"></script>'.$n;
                }
            }
        $message = $this->get_Indent($Origine,1,'get_end_js_html')."<!-- Début ".$famille." ".$nomchamp." -->".$n.$message.$this->get_Indent($Origine,1,'get_end_js_html')."<!-- Fin ".$famille." ".$nomchamp." -->".$n;
		return $message;
    }
    // ------------------------------------------------------------------------------
	private function get_head_meta_html($famille,$nomchamp,$Origine){
        $n=PHP_EOL;$message = '';
        if ($list = $this->_ObjJson->$famille){
            if (is_array($list->$nomchamp)){
                $tempovalueout = count($list->$nomchamp);
                if ($tempovalueout > 0 ){
                    for ($num_item = 0; $num_item < $tempovalueout; $num_item++){
                        $fichierutilise = AACSS.$list->$nomchamp[$num_item];
                        //
                        
                        $message .= file_exists($fichierutilise) ? 
                            $this->get_Indent($Origine,1,'get_head_meta_html').'<link rel="stylesheet" href="'.$fichierutilise.'">'.$n :
                            $this->set_error($this->get_errorphrase('',__FUNCTION__,__LINE__),' le fichier '.$fichierutilise." n'existe pas");
                    }
                }
            }
            elseif (is_string($list->title)){
                $message .= $this->get_Indent($Origine,1,'get_head_meta_html').'<title>'.$list->title.'</title>'.$n;
            }

            $message =  $this->get_Indent($Origine,1,'get_head_meta_html')."<!-- ".$famille." ".$nomchamp." -->".$n.$message. $this->get_Indent($Origine,1,'get_head_meta_html')."<!-- ".$famille." ".$nomchamp." -->".$n;
        }
		return $message;
    }
    // ------------------------------------------------------------------------------
    private function get_Indent($Origine=0,$nb=1,$from=''){
        $space="   ";
        if (gettype($Origine)!='integer'){
            $Origine = intval(1); 
            $txt ='error get_Indent '.gettype($Origine).' == '.$Origine.' ('.$from.')<br>';
        }
        $nb += $Origine;
        for ($i=1; $i<$nb; $i++){$space .= $space;}
        return $space;
    }
    // ------------------------------------------------------------------------------
    private function get_all_metas(
            $koi='head',
            $choix="meta",
            $balise="head",
            $Origine=0
        )
        {
        // fonction pour la création des metas : title, script, stylesheet
        $n=PHP_EOL;
        $phrase = $n.$this->get_Indent($Origine,2,'rien')."<!-- json-b-". $koi ."/" . $choix . " -->".$n; // html comment 
		for ($i=0;$i<count($this->_ObjJson->$koi->$choix);$i++){
            $html = "\n";
            $tag = "";$type="";$contentype="";$item="";$contentitem="";

            if (isset($this->_ObjJson->$koi->$choix[$i]->typeof)){          $typeof =          $this->_ObjJson->$koi->$choix[$i]->typeof;}
            if (isset($this->_ObjJson->$koi->$choix[$i]->nameof)){          $nameof =          $this->_ObjJson->$koi->$choix[$i]->nameof;}
            // --------------
            if (isset($this->_ObjJson->$koi->$choix[$i]->tag)){             $tag =              $this->_ObjJson->$koi->$choix[$i]->tag;}
            if (isset($this->_ObjJson->$koi->$choix[$i]->type)){            $type =             $this->_ObjJson->$koi->$choix[$i]->type;}
            if (isset($this->_ObjJson->$koi->$choix[$i]->contentype)){      $contentype =       $this->_ObjJson->$koi->$choix[$i]->contentype;}
            if (isset($this->_ObjJson->$koi->$choix[$i]->item)){            $item =             $this->_ObjJson->$koi->$choix[$i]->item;}
            if (isset($this->_ObjJson->$koi->$choix[$i]->contentitem)){     $contentitem =      $this->_ObjJson->$koi->$choix[$i]->contentitem;}
            if (isset($this->_ObjJson->$koi->$choix[$i]->typeitem)){        $typeitem =         $this->_ObjJson->$koi->$choix[$i]->typeitem;}

            $ValidationMeta = false;
            switch ($typeof){
                case 'src':
                    if ($contentype!="") $html = $tag." ".$typeof."=\"".$contentype."\"";
                    if ($contentype!="" && $item!="") $html .= " ".$item."=\"".$contentitem."\"";
                    if ($contentype!="") $html .= "></".$tag;
                    $ValidationMeta = true;
                break;
                case 'charset':
                    if ($contentype!="") $html = $tag." ".$typeof."=\"".$contentype."\"/";
                    $ValidationMeta = true;
                break;
                case 'title':
                    if ($contentype!="") $html = $tag.">".$contentype."</".$tag;
                    $ValidationMeta = true;
                break;
                case 'name':
                    if ($contentype!="") $html = $tag." ".$typeof."=\"".$contentype."\"";
                    if ($item!="") $html .= " ".$item."=\"".$contentitem."\"";
                    $ValidationMeta = true;
                break;
                case 'http-equiv':
                    if ($contentype!="") $html = $tag." ".$typeof."=\"".$contentype."\"";
                    if ($item!="") $html .= " ".$item."=\"".$contentitem."\"";
                    $ValidationMeta = true;
                break;
                case 'rel':
                    if ($contentype!="") $html = $tag." ".$typeof."=\"".$contentype."\"";
                    if ($item!="") $html .= " ".$item."=\"".$contentitem."\"";
                    $ValidationMeta = true;
                break;
                case 'favicon':
                    if ($contentype!="") $html = $tag." ".$typeof."=\"".$contentype."\"";
                    if ($item!="") $html .= " ".$item."=\"".$contentitem."\"";
                    if ($type!="") $html .= " ".$type."=\"".$typeitem."\"";
                    $ValidationMeta = true;
                break;
            }
            if ($ValidationMeta) $phrase .= $this->get_Indent($Origine,2,'rien')."<".$html.">".$n;
        } 
        $phrase .= $this->get_Indent($Origine,2,'rien').'<!-- json-b- End\'s '. $koi .'/' . $choix . ' -->'.$n;     // end html comment 

        // GENERATION des CSS TROUVés DANS LE JSON
        $cp = $this->_current_page;
        if ($koi=="head" && count($this->_ObjJson->$cp->css)>0) {
            $phrase .= $this->get_Indent($Origine,2,'rien').'<!-- json-a-'. $koi .'/' . $choix . ' -->'.$n;         // html comment            
            for ($j=0;$j<count($this->_ObjJson->$cp->css);$j++){
                if ($this->_ObjJson->$cp->css[$j]!="") {
                    $phrase .= $this->get_Indent($Origine,2,'rien').'<link rel="stylesheet" href="'.AACSS.$this->_ObjJson->$cp->css[$j].'">'.$n;
                }
            }
            $phrase .= $this->get_Indent($Origine,2,'rien').'<!-- json-a-End\'s '. $koi .'/' . $choix . ' -->'.$n;  // end html comment 
        }
        // fin css
        // encapsulage dans un tag head ($balise) // desactivé
        // if (!empty($phrase) && !empty($balise)) {
        //     $phrase = $this->get_Indent($Origine,1,'rien')."<".$balise.">".$n.$phrase.$this->get_Indent($Origine,1,'rien')."</".$balise.">";
        // }

		return $phrase;
    }
    // ------------------------------------------------------------------------------
    private function get_Pageaouvriratouslescoups($CHILDy){
        // print_airB($CHILDy,'get_Pageaouvriratouslescoups : '.$a.$b);
        $nbCheck = 0;
        $paquethtml = '';
        if ($this->_ObjJson->aouvriratouslescoups->actif){

            $files = $this->_ObjJson->aouvriratouslescoups->files;
            $nbFichierout = count($files);
            if ($nbFichierout > 0 ){
                for ($numFichierout = 0; $numFichierout < $nbFichierout; $numFichierout++){
                    
                    $require_fonction =     !empty($files[$numFichierout]->fonction) ?      AAFONCTION.$files[$numFichierout]->require.AAEXTPHP    : null;
                    $require_class =        !empty($files[$numFichierout]->class) ?         AACLASSE.$files[$numFichierout]->class.AAEXTPHP    : null;
                    
                    $require_controller=    !empty($files[$numFichierout]->controller) ?    AACONTROLEUR.$files[$numFichierout]->controller.AAEXTPHP    : null;
                    // $include_file =      !empty($files[$numFichierout]->include) ?       AAFONCTION.$files[$numFichierout]->include.AAEXTPHP    : null;                    
                    $vue_file =             !empty($files[$numFichierout]->vue) ?           AAINVUE.$files[$numFichierout]->require.AAEXTPHP     : null;

                    $aremplacer =           !empty($files[$numFichierout]->aremplacer) ?    $files[$numFichierout]->aremplacer                              : null;
                    $visible =              !empty($files[$numFichierout]->visible) ?       $files[$numFichierout]->visible                                 : null;
                    
                    
                    if ($require_fonction) // si une fonctions de traitememt php est demandé en require
                    {   
                        $requiredFile = $this->get_File_to_use('function',$require_fonction,"require_once",$this->get_errorphrase('',__FUNCTION__,__LINE__));
                    }

                    if ($require_class) // si une class php est demandé en require
                    {   
                        $requiredclass = $this->get_File_to_use('class',$require_class,"require_once",$this->get_errorphrase('',__FUNCTION__,__LINE__));
                    }
                    if ($require_controller) // si un controller php est demandé en require
                    {   
                        $requiredcontroller = $this->get_File_to_use('controller',$require_controller,"require_once",$this->get_errorphrase('',__FUNCTION__,__LINE__));
                    }
                   
                    if ($vue_file){ // vue
                        if ($visible){
                            $vueFile = file_exists($vue_file) 
                                ? $this->get_File_to_use('vue',$vue_file,"file_get_contents",$this->get_errorphrase('',__FUNCTION__,__LINE__))
                                : false;
                            
                            if (!empty($requiredFile) && $vueFile){
                                if (!empty($aremplacer)){




                                    $paquethtml .= preg_replace('{{'.$files[$numFichierout]->aremplacer."}}", '010{{COUCOU}}1000101', $vueFile);
                                }
                            }
                            //$check[$nbCheck++] = "VUE > file_get_contents(:".$vue_file.",,)";
                        }
                    }
                }
            }
        }
        // if (!empty($check))
        // {
        //     $_SESSION['cms']['check'] = $check;
        // }
        return $paquethtml;
    }
    // ------------------------------------------------------------------------------
    /**
     * 
     */
    private function get_Current_Page(){
        $new_current_page = null; // page vide

    
            // print_airB([
            //     'REQUEST_URI' => $a
            //     ,'PHP_SELF' => $b
            //     ,'c' => $c
            //     ,'d' => $c
            //     ,'SERVER_NAME' => $_SERVER['SERVER_NAME']
            //     ,'QUERY_STRING' => $_SERVER['QUERY_STRING']
            //     ,'HTTPS' => $_SERVER['HTTPS']
            //     ,'HTTP_HOST' => $_SERVER['HTTP_HOST']
            //     ,'HTTP_CONNECTION' => $_SERVER['HTTP_CONNECTION']
            //     ,'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT']
            //     ,'REQUEST_TIME_FLOAT' => $_SERVER['REQUEST_TIME_FLOAT']
            //     ,'PHP_SELF' => $_SERVER['PHP_SELF']
            //     ,'GET' => $_GET
            //     // ,'argv' => $argv
            //     // ,'argc' => $argc
            // ],'url',1);
            
            // parse_str($_SERVER['QUERY_STRING'], $array_arg);
            // // print_r($array_arg);

            // $r = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; 
            // $r = explode('/', $r);
            // $r = array_filter($r);
            
            

            $Posted = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
            // on ne prend qu'une page existante dans la liste
            for ($i=0; $i < count($this->_ObjJson->pages); $i++){                    // on prend la liste des pages existantes dans le json
                if (preg_match("'".$this->_ObjJson->pages[$i]."'",$Posted)){         // la page est elle dans l'url ??
                    $new_current_page = $this->_ObjJson->pages[$i];                  // si oui on prend le nom 
                    // break;                                                        // on stop ou pas pour chopper la derniere
                }
            }








            /* -------------------- REGLES ---------------------/
               -----------------------------------------------*/
            // on evite de réafficher la page login une fois loggué 
            if (!empty($_SESSION['profil']) AND $new_current_page=='login'){
                $new_current_page='index';
            }
            // on evite d'afficher la page profil sans être loggué
            if (empty($_SESSION['profil']) AND $new_current_page=='profil'){
                $new_current_page='login';
            }
            // on evite d'afficher le panier sans être loggué
            if (empty($_SESSION['profil']) AND $new_current_page=='panier'){
                $new_current_page='login';
            }
            /* ------------------ FIN REGLES ------------------*/










            // si on trouve une page dans l'url
            if (!empty($new_current_page)) 
            {   
                $this->set_Current_Page($new_current_page); 
                $_SESSION['user']['current_page'] = $this->_current_page;
                $_SESSION['user']['pages']['road'][] = $this->_current_page;    
            }
            else
            {   // sinon on met la page par defaut ou pas
                $_SESSION['cms']['log'][] = $this->get_errorphrase('',__FUNCTION__,__LINE__,null," Action => initialisation de current_page ".$new_current_page.") : ");
                $this->set_Current_Page($this->_default_page);  // page par default
                $new_current_page = $this->_default_page;       // page par default
                $_SESSION['user']['pages']['road'][] = $_SESSION['user']['current_page'];    
            }




        return $new_current_page;
    }
    // ------------------------------------------------------------------------------
    /**
     * 
     */
    private function get_Jsondecode($url_file){
        //print_air($url_file,__CLASS__."->".__FUNCTION__);
        return (is_string($url_file) && file_exists($url_file)) ? json_decode(file_get_contents($url_file,true)) : False;
    }

    // SETTER
	// ------------------------------------------------------------------------------
    /**
     * setter de la current page
     * @param string $newvalue nom de la page / page's name
     */
    public function set_Current_Page($newvalue){
        $this->_current_page = get_clean($newvalue);
    }
	// ------------------------------------------------------------------------------
    public function set_replace_in_vue($data){
        $this->_replace_in_vue[$this->_current_page] = $data;
    }
	// ------------------------------------------------------------------------------
    // public function set_replace_in_vue_obj($obj){
    //     foreach($obj as $key as $value)
    //     {
    //         $this->_replace_in_vue[$this->_current_page] = $data;
    //     }
    // }
    // ------------------------------------------------------------------------------
    public function get_replace_in_vue(){
        if (!empty($this->_replace_in_vue[$this->_current_page]))
        {
            return $this->_replace_in_vue[$this->_current_page];
        }
        // else
        // {
        //     return false;
        // }
    }
	// ------------------------------------------------------------------------------
    private function get_Default_Page(){
        return $this->_default_page;
    }
    // 
    /**
     * affichage des info dans la session
     * @param string $action famille dans SESSION (errors par defaut)
     * @param string $message texte a afficher
     * @param string $from source de l'appel
     */
    public function set_error($from,$message,$action='errors'){
        $_SESSION['cms'][$action][++$this->_Nlog] = "[N° $this->_Nlog] $from.$message";
    }
    // --------------------------------------------------------------------------------
    /**
     * getter de fichiers exterieurs + sender d'erreurs
     * @param string $action famille dans SESSION
     * @param string $type require_once|include_once|file_get_contents
     * @param string $from source call
     * @return mixed content|true if succes|false if fail
     */
    private function get_File_to_use($action,$file,$type,$from=null){
        if (file_exists($file))
        {
            switch($type)
            {
                case "include":
                    include($file);
                    $this->set_error($from,$type."($file)",$action);
                    return true;
                break;
                
                case "require_once":
                    require_once($file);
                    $this->set_error($from,$type."($file)",$action);
                    return true;
                break;
                
                case "include_once":
                    include_once($file);
                    $this->set_error($from,$type."($file)",$action);
                    return true;
                break;
                
                case "file_get_contents":
                    $this->set_error($from,$type."($file)",$action);
                    return file_get_contents($file);
                break;
                
                default :
                    $this->set_error($from,$type."($file) ->:".__FUNCTION__.".",'errors');
                    return false;
                break;
            }
        }
        else
        {   
            $this->set_error($from,' le fichier '.$file." n'existe pas. la methode ".$type." (".$file.") n'a pas fonctionnée");
            return false;
        }
    }
    // --------------------------------------------------------------------------------
    // MODULE ERREURS
    /**
     * use : get_errorphrase('',__FUNCTION__,__LINE__,{'one','more'},'commentaires')
     * @param string $file use to be '' with FULL url
     * @param string $function use to be __FUNCTION__
     * @param string $line use to be __LINE__
     * @param string $arguments use to be array arguments from function
     * @param string $coment use string
     */
    private function get_errorphrase($file,$function,$line,$arguments=null,$coment=null)
    {
        if ('array' == gettype($arguments)) 
        {
            $splitedarguments = '';
            $i=0;
            foreach($arguments as $key => $value)
            {
                $splitedarguments .= (++$i < count($arguments)) ? $value.',' : $value;
            }
            $arguments = $splitedarguments;
        }
        return "$file:[$line] $function($file) ->".$coment;
    }
    /**
     * use : get_errorphrase('',__FUNCTION__,__LINE__)
     * @param string $file use to be '' with FULL url
     * @param string $function use to be __FUNCTION__
     * @param string $line use to be __LINE__
     * @param string $arguments use to be indexed arguments from function
     * @param string $coment use string
     * @return array
     */
    // --------------------------------------------------------------------------------
    private function get_errorphraseindex($file,$function,$line,$arguments=null,$coment=null){
        return [
            'line' => $line,
            'fichier' => $file,
            'function' => $function."($arguments)",
            'coment' => $coment
        ];
    }
    // --------------------------------------------------------------------------------
	// --------------------------------------------------------------------------------
    // HYDRATE
    private function hydrate_info_var($array){
        // print_air($array,__CLASS__."->".__FUNCTION__);
        for($i = 0; $i < count($array); $i++){
            $method = 'set'.ucfirst($array[$i]);
			if (method_exists($this, $method)) {
				$this->$method($array[$i]);
			}
        }
    }
}
?>