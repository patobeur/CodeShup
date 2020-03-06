<?php

class Page{

    const PROOT         = '';
    const PCORE         = self::PROOT.'core/';
    //
    const PJSON         = self::PCORE.'json/';
    const PLOG          = self::PCORE.'log/';
    const PIMG          = self::PCORE.'img/';
    //
    const FUNKY         = self::PCORE.'functions/';
    const VIEW          = self::PCORE.'view/'.'';           // pages parsé à la volé indiqué dans le json
    const PAGEINN       = self::PCORE.'inview/_in_';        // pages a mettre dans view
    // const PIMPPREFIX    = self::PCORE.'php/_inc_';          // pour les pages en include ou require (local au moteur)
    // FILES
    const PJSONHEADER   = self::PJSON.'content.json';
    // INCLUDES
    const PNAVIGA       = self::VIEW.'navigation.php';
    const PFOOTER       = self::VIEW.'footer.php';
    //
    const PCSS          = 'theme/css/';
    const PJS           = 'js/';
    const PEXTENSION    ='.php';


    // --------------------------------------------------------------------------------	
	// $auteur =           $Structure_json_arr['auteur'];
    // $date =             $Structure_json_arr['date'];
    // $time_stamp =       $Structure_json_arr['time_stamp'];

	private $_ObjJson;
	private $_auteur;
	private $_date;
	private $_time_stamp;
    private $_logosrc;
    //
	private $_current_page='index';
	private $_Originum=0;
    
    public function __construct(){
            $this->_ObjJson = $this->get_Jsondecode(self::PJSONHEADER);
            //print_air(($this->_ObjJson) ? "json file ok" : "error json file",__CLASS__."->".__FUNCTION__);
            $this->hydrate_info_var(array('_auteur','_date','_time_stamp','_logosrc'));
    }
    // PUBLIC FUNCTIONS
    // --------------------------------------------------------------------------------
    // GETTER
    // --------------------------------------------------------------------------------
    public function do_affichelapagehtml(){
        //print_airB("start",__CLASS__."->".__FUNCTION__);
        echo $this->get_Dom();
    }
    // --------------------------------------------------------------------------------
    private function get_Dom(){
        $this->_current_page = $this->get_current_pagename();
        //print_air($this->_current_page,__CLASS__."->".__FUNCTION__);
        // integration d'une page php a la volée
        $this->do_RequireFile($this->_current_page);
        
        $bloc = $this->get_Header_Html(1);
        //print_html("bloc : ".$bloc);//,__CLASS__."->".__FUNCTION__);
        //
        $bloc .= $this->get_Contents_Html();
        print_html("bloc : ".$bloc);//,__CLASS__."->".__FUNCTION__);
        return $bloc;
    }
	private function get_List_Menu($kelfamille){
        //print_airB($kelfamille,__CLASS__."->".__FUNCTION__);
        $n=PHP_EOL;
        // NAVIGATION
        if (file_exists(self::PNAVIGA)) {
            $fichier_importe = file_get_contents(self::PNAVIGA,TRUE).$n;       // lecture du fichier a inclure 
        }
        else {
            DEBUG_DIE ? die('le fichier "'.self::PNAVIGA.'" est manquant. !?!') : die();
        }
        // ------------------------------------------------------------------------------
        $arr_pc = $this->_ObjJson->$kelfamille;                            // arr_pc = pages courantes (arr_ pour array)
        $blocm = $this->get_Indent(0,4,'Navigat')."<!-- Auto in menu -->".$n;
        for ($oo=0; $oo < count($arr_pc); $oo++){
            // ici le lien active
            $famille = $arr_pc[$oo];                                       // $ARRRAIEE[$kelfamille][$oo]
            $enfant = $this->_ObjJson->$famille;                           // $ARRRAIEE[$ARRRAIEE[$kelfamille][$oo]]
            // echo $_SESSION['CURR_PAGE'].'=='.$famille.'<br>';
            if ($_SESSION['CURR_PAGE'] == $famille) {$active = ' active';} else  {$active = '';}
            
            $blocm .= $this->get_Indent(0,4,'Navigat').'<a class="dropdown-item'.$active.'" href="?'. $famille.'">'.$enfant->title.'</a>'.$n;
        } 
        $blocm .= $this->get_Indent(0,4,'Navigat')."<!-- Fin Auto in menu -->";
        if ($_SESSION['CURR_PAGE'] == 'index') {$ISACTIF = ' active';} else  {$ISACTIF = '';}










        $valeurderetour = preg_replace("_NAVIGATATOR_",$blocm, $fichier_importe); 
        $valeurderetour = preg_replace("_ACTIVITE_",$ISACTIF, $valeurderetour);
        // ------------------------------------------------------------------------------
        $valeurderetour = preg_replace('_ACTOBEURTWO_',$this->get_Navigation_Menu('pagesgestion','files'), $valeurderetour);
        $valeurderetour = preg_replace('_ACTOBEUR_',$this->get_Navigation_Menu('pagesext','files'), $valeurderetour);
        // ------------------------------------------------------------------------------
        //print_airB($valeurderetour,'valeurderetour');
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
        for ($oo=0; $oo < count($this->_ObjJson->$kelfamille->$sousfamille); $oo++){
            // ici le lien active
            $target='';
            ($Tablo_Enfants[$oo]->target!='') ? 
                $target=' target="'.$Tablo_Enfants[$oo]->target.'"' : 
                $target='';
            //echo($target);
            $A_remplacement .= $this->get_Indent(0,5,'get_Navigation_Menu');
            $A_remplacement .= '<a class="dropdown-item" href="'.$Tablo_Enfants[$oo]->href.'"'.$target.'>'.$Tablo_Enfants[$oo]->title.'</a>'.PHP_EOL;
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
                                        aria-expanded="false">'.$this->_ObjJson->$kelfamille->nommenu.'</a>
                                    <div class="dropdown-menu" aria-labelledby="'.$this->_ObjJson->$kelfamille->nomlabel.'">
                                        <!-- Auto out '.$this->_ObjJson->$kelfamille->nommenu.' -->
MENUACTOBEUR                                        <!-- Fin out '.$this->_ObjJson->$kelfamille->nommenu.' -->
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
        $jusquaHead .= $this->get_all_content_html('head',"meta","head",0); // get all meta
        $jusquaHead .= $this->get_head_meta_html('atouslescoups','css',1);
        $jusquaHead .= $this->get_head_meta_html($this->_current_page,'css',1);

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
    private function get_Contents_Html(){
        $n=PHP_EOL;$bloc="\n";
        $bloc .= $this->get_Indent($this->_Originum,1,'get_Contents_Html').'<body>'.$n;
        $bloc .= $this->get_Indent($this->_Originum,2,'get_Contents_Html').'<div class="fullpage">'.$n;

        
        $header = preg_replace('_URLROOT_', $this::PROOT, $this->get_List_Menu('pages'));
        $header = preg_replace('_LOGOSRC_', $this->_ObjJson->CHARTE->NAV->IMGROOT . $this->_ObjJson->CHARTE->NAV->LOGOSRC, $header);
        
        $bloc .= $header;

        // generation des pages a integrer dans le body en dessous de navigation mais en dessus du footer
        // ici je cherche dans le json
        $cp = $this->_current_page; // cb = current page
        if ($this->_ObjJson->$cp->blocs){
            $tempovalue = count($this->_ObjJson->$cp->blocs);
            // je prend la liste des pages _in_ a intégrer

            for ($numFichier = 0; $numFichier < $tempovalue; $numFichier++){            
                $fichiers = $this->_ObjJson->$cp->blocs; // cb = current bloc
                $bloc .= file_get_contents(self::PAGEINN.$fichiers[$numFichier].self::PEXTENSION,TRUE).$n;
print_air(self::PAGEINN.$fichiers[$numFichier].self::PEXTENSION,"blocs à afficher : ");
            }
        }
        //print_airB($this->_ObjJson->aouvriratouslescoups);//->aouvriratouslescoups);
        // en fin de page
        // ouvrir les pages a include a tous les coups comme visitor
        // à remplacer par un cookies
// print_airB($bloc,'one');
        $bloc .= $this->get_Pageaouvriratouslescoups('files').$n;
// print_airB($bloc,__FUNCTION__);
        // FOOTER
        if (file_exists(self::PFOOTER)) {
            $bloc .= file_get_contents(self::PFOOTER,TRUE).$n;  
        }
        else {
            DEBUG_DIE ? die('le fichier "'.self::PFOOTER.'" est manquant. !?!') : die();
        }
        // eco($bloc);

        
        $bloc .= $this->get_js_html($this->_current_page,'js',2);
        $bloc .= $this->get_Indent($this->_Originum,2,'rien').'</div>'.$n;    // on ferme le div du début <div class="fullpage">
        $bloc .= $this->get_js_html('atouslescoups','js',1);

        $bloc .= $this->get_Indent($this->_Originum,1,'rien').'</body>'.$n;   // on ferme le body
        $bloc .= '</html>'.$n;
        // eco($bloc);
		return $bloc;
    }
    // --------------------------------------------------------------------------------
	private function get_js_html($famille='atouslescoups',$nomchamp,$Origine=0){
        $n=PHP_EOL;$message = '';
        $ARRRRAIE = $this->_ObjJson->$famille;
        // printair($ARRRRAIE,'famille: ');


            $cc = $ARRRRAIE->$nomchamp; // cc = current champs
            $tempovalueout = count($cc);
            if ($tempovalueout > 0 ){
                for ($num_item = 0; $num_item < $tempovalueout; $num_item++){
                    $message .= $this->get_Indent($Origine,1,'js_html').'<script src="'.self::PJS.$cc[$num_item].'" type="text/javascript"></script>'.$n;
                }
            }
        $message = $this->get_Indent($Origine,1,'js_html')."<!-- Début ".$famille." ".$nomchamp." -->".$n.$message.$this->get_Indent($Origine,1,'js_html')."<!-- Fin ".$famille." ".$nomchamp." -->".$n;
		return $message;
    }

    // --------------------------------------------------------------------------------
	private function get_head_meta_html($famille='atouslescoups',$nomchamp='css',$Origine){
        $n=PHP_EOL;$message = '';
        $list = $this->_ObjJson->$famille;
        $tempovalueout = count($list->$nomchamp);
        if ($tempovalueout > 0 ){
            for ($num_item = 0; $num_item < $tempovalueout; $num_item++){
                $message .= $this->get_Indent($Origine,1,'get_head_meta_html').'<link rel="stylesheet" href="'.self::PCSS.$list->$nomchamp[$num_item].'">'.$n;
            }
        }
        $message =  $this->get_Indent($Origine,1,'get_head_meta_html')."<!-- ".$famille." ".$nomchamp." -->".$n.$message. $this->get_Indent($Origine,1,'get_head_meta_html')."<!-- ".$famille." ".$nomchamp." -->".$n;
		return $message;
    }
    // --------------------------------------------------------------------------------
    private function get_Indent($Origine=0,$nb=1,$from=''){
        $space="   ";
        if (gettype($Origine)!='integer'){
            $Origine = intval(1); 
            $txt ='error OkSpacer '.gettype($Origine).' == '.$Origine.' ('.$from.')<br>';
            //print $txt;
        }


        $nb += $Origine;
        for ($i=1; $i<$nb; $i++){
            $space .= $space;
        }
        return $space;
    }
    // --------------------------------------------------------------------------------
    private function get_all_content_html(
            $koi='head',
            $choix="meta",
            $balise="head",
            $Origine=0
        )
        {
        // fonction pour la création des metas : title, script, stylesheet
        $n=PHP_EOL;
        $phrase = $n.$this->get_Indent($Origine,2,'rien')."<!-- json-b-". $koi ."/" . $choix . " -->".$n;
		for ($i=0;$i<count($this->_ObjJson->$koi->$choix);$i++){
            $html = "\n";
            $tag = "";$type="";$contentype="";$item="";$contentitem="";
            //printair($this->_ObjJson->$koi->$choix);
            //echo $this->_ObjJson->$koi->$choix[$i]->typeof;
            // -------------- 
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
        $phrase .= $this->get_Indent($Origine,2,'rien').'<!-- json-b- End\'s '. $koi .'/' . $choix . ' -->'.$n;


        // GENERATION CSS TROUVE DANS LE JSON
        //this->_current_page
        $cp = $this->_current_page;
        if ($koi=="head" && count($this->_ObjJson->$cp->css)>0) {
            $phrase .= $this->get_Indent($Origine,2,'rien').'<!-- json-a-'. $koi .'/' . $choix . ' -->'.$n;
            
            for ($j=0;$j<count($this->_ObjJson->$cp->css);$j++){
                if ($this->_ObjJson->$cp->css[$j]!="") {
                    $phrase .= $this->get_Indent($Origine,2,'rien').'<link rel="stylesheet" href="'.self::PCSS.$this->_ObjJson->$cp->css[$j].'">'.$n;
                }
            }
            $phrase .= $this->get_Indent($Origine,2,'rien').'<!-- json-a-End\'s '. $koi .'/' . $choix . ' -->'.$n;
        }



        // fin css
        // encapsulage dans un tag head // desactivé
        //if ($phrase!="") $phrase = $this->get_Indent($Origine,1,'rien')."<".$balise.">".$n.$phrase.$this->get_Indent($Origine,1,'rien')."</".$balise.">";

		return $phrase;
    }
    // --------------------------------------------------------------------------------
    private function get_Pageaouvriratouslescoups($CHILDy){
        $n=PHP_EOL;
        $rootactif2 =  'in/_in_';
        $check = [];
        $paquethtml = '';
        // printair($CHILDy); 
        //  print_airB($this->_ObjJson->aouvriratouslescoups->actif,'actif'); 
        if ($this->_ObjJson->aouvriratouslescoups->actif){

            $files = $this->_ObjJson->aouvriratouslescoups->$CHILDy;
            $nbFichierout = count($files);
            if ($nbFichierout > 0 ){
                for ($numFichierout = 0; $numFichierout < $nbFichierout; $numFichierout++){

                    if ($files[$numFichierout]->page!="")       {$ext_file = self::FUNKY.$files[$numFichierout]->page.self::PEXTENSION;     $check[] = "ext_file(:".$ext_file.")";}

                    if ($files[$numFichierout]->visible!="")    {$visible = $files[$numFichierout]->visible;                                $check[] = "visible(:".$visible.",,)";}
                    // file to get_contents from

                    if ($files[$numFichierout]->page!="")       {$ink_file = self::PAGEINN.$files[$numFichierout]->page.self::PEXTENSION;   $check[] = "ink_file(:".$ext_file.")";}

                    // file to get_contents from
                    // file to get_contents from
                    if ($files[$numFichierout]->aremplacer!="") {$aremplacer = $files[$numFichierout]->aremplacer;                          $check[] = "aremplacer(:".$aremplacer.",,)";}

                    if ($files[$numFichierout]->session!="")    {$lasesssion = $files[$numFichierout]->session;                             $check[] = "session(:".$lasesssion.",,)";}
 
                    if ($files[$numFichierout]->require!="")    {$require = $files[$numFichierout]->require;                                $check[] = "require(:".$require.",,)";}

                    // ------------------- DANGER ! ----------------------------------
                    if ($require){ // on appel le fichier demander dans le json 

                        $test = include_once($ext_file);
                        $check[] = "included (:".$ext_file.",,)";
                    };
                    if ($visible) {

                        $paquethtml .= preg_replace($aremplacer, $valeurderetour, file_get_contents($ink_file, TRUE)).$n;
                        $check[] = "file_get_contents(:".$ink_file.",,)";

                    }
                }
            }
        }
        print_airB($check,'check');
        return $paquethtml;
        // print_airB($check,'check');
    }
    // --------------------------------------------------------------------------------
    private function get_current_pagename(){
        $new_current_page = $this->_ObjJson->defaultpage[0];   // page par default

        $Posted = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        
        
        // un pti coup de sécu ici        
        for ($i=0; $i < count($this->_ObjJson->pages); $i++){                    // on prend la liste dess page existantes dans le json
            if (preg_match("'".$this->_ObjJson->pages[$i]."'",$Posted)){         // la page est elle dans l'url ??
                $new_current_page = $this->_ObjJson->pages[$i];                  // si oui on prend le nom 
                // break;                                                        // on stop ou pas pour chopper la dernier
            }
        }
        $_SESSION['CURR_PAGE'] = $new_current_page;

        return $new_current_page;
    }
    // --------------------------------------------------------------------------------
    private function do_RequireFile($new_current_page){
        
        //print_air($this->_ObjJson->$new_current_page,__CLASS__."->".__FUNCTION__."['$new_current_page']");
        if (!empty($this->_ObjJson->$new_current_page->require) ){
            print_air($new_current_page,__CLASS__."->".__FUNCTION__);
            $listedesRequire = $this->_ObjJson->$new_current_page->require;
            //print_air($listedesRequire,__CLASS__."->".__FUNCTION__);
            for ( $i = 0; $i < count($listedesRequire); $i++ ){
                $fichierrequire = self::FUNKY.$listedesRequire[$i].self::PEXTENSION;
                if (file_exists($fichierrequire)) {
                    require_once($fichierrequire);  
                }
                else{
                    print_air('il manque le fichier '.$fichierrequire,"erreur get_current_pagename");
                }
            }
        }
        else{
            print_air($new_current_page." require no file",__CLASS__."->".__FUNCTION__);
        }
    }
    // --------------------------------------------------------------------------------
    private function get_Jsondecode($url_file){
        //print_air($url_file,__CLASS__."->".__FUNCTION__);
        return (is_string($url_file) && file_exists($url_file)) ? json_decode(file_get_contents($url_file,true)) : False;
    }
	// --------------------------------------------------------------------------------
    // SETTER
    private function set_Current_Page($newvalue){
        $this->_current_page = $newvalue;
    }
	// --------------------------------------------------------------------------------
    // HYDRATE
    private function hydrate_info_var($arr_json){
        print_air($arr_json,__CLASS__."->".__FUNCTION__);
        for($i = 0; $i < count($arr_json); $i++){
            $method = 'set'.ucfirst($arr_json[$i]);
			if (method_exists($this, $method)) {
				$this->$method($arr_json[$i]);
			}
        }
    }

}
?>