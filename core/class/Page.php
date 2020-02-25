<?php

class Page{

    const PROOT         = '';
    const PCORE         = self::PROOT.'core/';
    //
    const PJSON         = self::PCORE.'json/';
    const PLOG          = self::PCORE.'log/';
    const PIMG          = self::PCORE.'log/';
    //
    const FUNKY         = self::PCORE.'f_';
    const VIEW          = self::PCORE.'view/'.'';           // pages parsé à la volé indiqué dans le json
    const PAGEINN       = self::PCORE.'inview/_in_';        // pages a mettre dans view
    const PIMPPREFIX    = self::PCORE.'php/_inc_';     // pour les pages en include ou require (local au moteur)
    //
    const PJSONHEADER   = self::PJSON.'content.json';
    const PNAVIGA       = self::VIEW.'navigation.php';
    const PFOOTER       = self::VIEW.'footer.php';
    //
    const PCSS          = 'theme/css/';
    const PJS           = 'js/';
    const PEXTENSION    ='.php';


    // -----------------------------------------------------------------------------------------------------------------------	
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
            $this->_ObjJson =    $this->get_Jsondecode(self::PJSONHEADER);
            $this->hydrate_info_var(array('_auteur','_date','_time_stamp','_logosrc'));
    }
    // PUBLIC FUNCTIONS
    public function do_affichelapagehtml(){
        print $this->get_Dom();
     }
    // -----------------------------------------------------------------------------------------------------------------------
    // PUBLIC FUNCTIONS
    // -----------------------------------------------------------------------------------------------------------------------
	private function get_List_Menu($kelfamille){
        $n=PHP_EOL;
        $fichier_importe = file_get_contents(self::PNAVIGA,TRUE).$n;       // lecture du fichier a inclure 
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
        return $valeurderetour; 
    }
    // -----------------------------------------------------------------------------------------------------------------------
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
    // -----------------------------------------------------------------------------------------------------------------------
    private function get_RemplacePar($txtaenlever,$txtamettre,$html){        
        return ($txtamettre and $txtamettre!='') ? preg_replace($txtaenlever,$txtamettre,$html) : preg_replace($txtaenlever,'',$html);
    }
    // -----------------------------------------------------------------------------------------------------------------------
    private function get_Dom(){
        $this->_current_page = $this->get_current_pagename();
        $bloc = $this->get_Header_Html(1);
        //
        $bloc .= $this->get_Contents_Html();
        return $bloc;
    }
    // -----------------------------------------------------------------------------------------------------------------------
	private function get_Header_Html($nb_space=0){
        $n=PHP_EOL;$message="";
        $message .= $this->get_all_content_html('head',"meta","head",0); // get all meta
        $message .= $this->get_head_meta_html('atouslescoups','css',1);
        $message .= $this->get_head_meta_html($this->_current_page,'css',1);

        // head html
        $message = $this->get_Indent($this->_Originum,1,'Gen_Head1')
            .'<head>'.$message.$n.$this->get_Indent($this->_Originum,1,'Gen_Head2').'</head>';

        $message = $this->get_Indent($this->_Originum,1,'Gen_Head3').
            "<!-- ". $this->_current_page ." -->".$n.$this->get_Indent($this->_Originum,$nb_space,'Gen_Head4').
            $message.$n.$this->get_Indent($this->_Originum,$nb_space,'Gen_Head5')."<!-- End's  ".
            $this->_current_page ." -->".$n;

        if (isset($this->_ObjJson->structure->meta->lang)){    $message =  $this->_ObjJson->structure->meta->lang.$n.$message;}
        if (isset($this->_ObjJson->structure->meta->doctype)){ $message =  $this->_ObjJson->structure->meta->doctype.$n.$message;}
        return $message;
    }
	// -----------------------------------------------------------------------------------------------------------------------
    // SETTER
    private function set_Current_Page($newvalue){
        $this->_current_page = $newvalue;
    }
    
    // GETTER
    private function get_Contents_Html(){
        $n=PHP_EOL;$bloc="\n";
        $bloc .= $this->get_Indent($this->_Originum,1,'get_Contents_Html').'<body>'.$n;
        $bloc .= $this->get_Indent($this->_Originum,2,'get_Contents_Html').'<div class="fullpage">'.$n;

        
        $surcouche = preg_replace('_URLROOT_', $this::PROOT, $this->get_List_Menu('pages'));
        $surcouche = preg_replace('_LOGOSRC_', $this->_ObjJson->CHARTE->NAV->IMGROOT . $this->_ObjJson->CHARTE->NAV->LOGOSRC, $surcouche);
        $bloc .=$surcouche;

        // generation des pages a integrer dans le body en dessous de navigation mais en dessus du footer
        // ici je cherche $CURR_PAGE dans le json
        $cp = $this->_current_page;
        $tempovalue = count($this->_ObjJson->$cp->blocs);
        // je prend la liste des pages _in_ a intégrer

        for ($nbfichier = 0; $nbfichier < $tempovalue; $nbfichier++){            
            $cb = $this->_ObjJson->$cp->blocs; // cb = current bloc
            //echo(self::PAGELOC.$cb[$nbfichier].self::PEXTENSION);
            $bloc .= file_get_contents(self::PAGEINN.$cb[$nbfichier].self::PEXTENSION,TRUE).$n;

        }

        // en fin de page
        // ouvrir les pages a include a tous les coups comme visitor
        // à remplacer par un cookies
        $bloc .= $this->get_Pageaouvriratouslescoups($this->_ObjJson->aouvriratouslescoups,'files').$n;

        // FOOTER
        if (file_exists(self::PFOOTER)) {
            $bloc .= file_get_contents(self::PFOOTER,TRUE).$n;  
        }

        
        $bloc .= $this->get_js_html($this->_current_page,'js',2);
        $bloc .= $this->get_Indent($this->_Originum,2,'rien').'</div>'.$n;    // on ferme le div du début <div class="fullpage">
        $bloc .= $this->get_js_html('atouslescoups','js',1);

        $bloc .= $this->get_Indent($this->_Originum,1,'rien').'</body>'.$n;   // on ferme le body
        $bloc .= '</html>'.$n;
		return $bloc;
    }
    // -----------------------------------------------------------------------------------------------------------------------
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

    // -----------------------------------------------------------------------------------------------------------------------
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
    // -----------------------------------------------------------------------------------------------------------------------
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
    // -----------------------------------------------------------------------------------------------------------------------
    private function get_all_content_html($koi='head',$choix="meta",$balise="head",$Origine=0){
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
    // -----------------------------------------------------------------------------------------------------------------------
    private function get_Pageaouvriratouslescoups($ARRRRAIE, $CHILDy){

        $n=PHP_EOL;
        $rootactif2 =  'in/_in_';
        // printair($CHILDy); 
        // printair($this->_ObjJson->aouvriratouslescoups->$CHILDy); 
        // if ($ARRRRAIE['actif']){
            $tempovalueout = count($this->_ObjJson->aouvriratouslescoups->$CHILDy);
            if ($tempovalueout > 0 ){
                for ($nbfichierout = 0; $nbfichierout < $tempovalueout; $nbfichierout++){
                    // function file to include
                    $cb = $this->_ObjJson->aouvriratouslescoups->$CHILDy;
                    // printair( $cb);
                    // echo $nbfichierout;
                    // printair( $cb[$nbfichierout]->page);

                    if ($cb[$nbfichierout]->page!="") $ext_file = self::FUNKY.$cb[$nbfichierout]->page.self::PEXTENSION;
                    // file to get_contents from
                    if ($cb[$nbfichierout]->page!="") $ink_file = self::PAGEINN.$cb[$nbfichierout]->page.self::PEXTENSION;
                    // file to get_contents from
                    if ($cb[$nbfichierout]->aremplacer!="") $aremplacer = $cb[$nbfichierout]->aremplacer;
                    if ($cb[$nbfichierout]->session!="") $lasesssion = $cb[$nbfichierout]->session;
                    if ($cb[$nbfichierout]->require!="") $require = $cb[$nbfichierout]->require;
                    if ($cb[$nbfichierout]->visible!="") $visible = $cb[$nbfichierout]->visible;

                    // ------------------- DANGER ! ----------------------------------
                    if ($require) require_once($ext_file); // on appel le fichier demander dans le json (ça craint !!! mais ??? )
                    if ($visible) return preg_replace($aremplacer, $valeurderetour, file_get_contents($ink_file, TRUE)).$n; // re ça craint )
                }
            }
        // }
    }
    // -----------------------------------------------------------------------------------------------------------------------
    private function get_current_pagename(){
        $NEW_CURR_PAGE = $this->_ObjJson->defaultpage[0];   // page par default
        $Posted = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        
        // un pti coup de sécu ici        
        for ($i=0; $i < count($this->_ObjJson->pages); $i++){                    // on prend la liste dess page existantes dans le json
            if (preg_match("'".$this->_ObjJson->pages[$i]."'",$Posted)){         // la page est elle dans l'url ??
                $NEW_CURR_PAGE = $this->_ObjJson->pages[$i];                     // si oui on prend le nom 
                // break;                                                        // on stop ou pas pour chopper la dernier
            }
        }
        $_SESSION['CURR_PAGE'] = $NEW_CURR_PAGE;
        return $NEW_CURR_PAGE;
    }
    // -----------------------------------------------------------------------------------------------------------------------
    private function get_Jsondecode($url_file){
        if (is_string($url_file)){
            if (file_exists($url_file)) {
                // $resultjson =  json_decode(file_get_contents($url_file,true));
                // printair($resultjson);
                return json_decode(file_get_contents($url_file,true));;
            }
            else {
                return False;
            }
        }
        else {
            return False;
        }
    }
    // -----------------------------------------------------------------------------------------------------------------------
    private function hydrate_info_var($arr_json){
        for($i = 0; $i < count($arr_json); $i++){
            $method = 'set'.ucfirst($arr_json[$i]);
            // echo $method;
			if (method_exists($this, $method)) {
				$this->$method($arr_json[$i]);
			}
        }
    }

}
?>