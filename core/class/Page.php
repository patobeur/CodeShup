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
    const VUES          = self::PCORE.'inview/_in_';        // pages a mettre dans view
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

	private $_Nlog = 0;
	private $_ObjJson;
	private $_auteur;
	private $_date;
	private $_time_stamp;
    private $_logosrc;
    //
	private $_current_page='';
	private $_default_page='';
	private $_Originum=0;
    
    public function __construct(){
        $this->_ObjJson = $this->get_Jsondecode(self::PJSONHEADER);
        $this->_current_page = $this->_ObjJson->defaultpage[0];
        $this->_default_page = $this->_ObjJson->defaultpage[0];
        // $this->hydrate_info_var(array('_auteur','_date','_time_stamp','_logosrc'));
    }
    // PUBLIC FUNCTIONS
    // --------------------------------------------------------------------------------
    // GETTER
    // --------------------------------------------------------------------------------
    public function do_affichelapagehtml(){
        $this->_current_page = $this->get_Current_Page();
        echo $this->get_Dom();
    }
    // --------------------------------------------------------------------------------
    private function get_Dom(){
        $bloc = $this->get_Header_Html(1);
        //
        $bloc .= $this->get_Contents_Html();
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
            if ($this->_current_page == $famille) {$active = ' active';} else  {$active = '';}
            
            $blocm .= $this->get_Indent(0,4,'Navigat').'<a class="dropdown-item'.$active.'" href="?'. $famille.'">'.$enfant->title.'</a>'.$n;
        } 
        $blocm .= $this->get_Indent(0,4,'Navigat')."<!-- Fin Auto in menu -->";
        if ($this->_current_page == 'index') {$ISACTIF = ' active';} else  {$ISACTIF = '';}










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
        for ($numFam=0; $numFam < count($this->_ObjJson->$kelfamille->$sousfamille); $numFam++){
            // ici le lien active
            $target='';
            ($Tablo_Enfants[$numFam]->target!='') ? 
                $target=' target="'.$Tablo_Enfants[$numFam]->target.'"' : 
                $target='';
            //echo($target);
            $A_remplacement .= $this->get_Indent(0,5,'get_Navigation_Menu');
            $A_remplacement .= '<a class="dropdown-item" href="'.$Tablo_Enfants[$numFam]->href.'"'.$target.'>'.$Tablo_Enfants[$numFam]->title.'</a>'.PHP_EOL;
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
        $jusquaHead .= $this->get_all_metas('head',"meta","head",0); // get all meta
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

        
        $header = $this->get_RemplacePar('_URLROOT_', $this::PROOT, $this->get_List_Menu('pages'));
        $header = $this->get_RemplacePar('_LOGOSRC_', $this->_ObjJson->CHARTE->NAV->IMGROOT . $this->_ObjJson->CHARTE->NAV->LOGOSRC, $header);
        
        $bloc .= $header;

        // generation des pages a integrer dans le body en dessous de navigation mais en dessus du footer
        // ici je cherche dans le json
        $cp = $this->_current_page;                                             // cp = current page
        if ($this->_ObjJson->$cp->blocs){
            $tempovalue = count($this->_ObjJson->$cp->blocs);                   // je prend la liste des pages _in_ a intégrer

            for ($numFichier = 0; $numFichier < $tempovalue; $numFichier++){            
                $fichiers = $this->_ObjJson->$cp->blocs;                        // cb = current bloc
                
                $require_file = self::VUES.$fichiers[$numFichier].self::PEXTENSION;
                $bloc .= file_exists($require_file) ?  $this->get_File_to_use('local',$require_file,"require_once",$this->get_errorphrase(__FILE__,__FUNCTION__,__LINE__)) : false;
            }
        }
        // --------------------------------------------------------------------------------------------
        $bloc .= $this->get_Pageaouvriratouslescoups('files').$n;
// print_airB($bloc,__FUNCTION__);
        // FOOTER
        $bloc .= file_exists(self::PFOOTER) ?  $this->get_File_to_use('local',self::PFOOTER,"require_once",$this->get_errorphrase(__FILE__,__FUNCTION__,__LINE__)) : false;
        // JS
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
    private function get_all_metas(
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
        // print_airB($CHILDy,'get_Pageaouvriratouslescoups : '.$a.$b);
        $nbCheck = 0;
        $paquethtml = '';
        if ($this->_ObjJson->aouvriratouslescoups->actif){

            $files = $this->_ObjJson->aouvriratouslescoups->$CHILDy;
            $nbFichierout = count($files);
            if ($nbFichierout > 0 ){
                for ($numFichierout = 0; $numFichierout < $nbFichierout; $numFichierout++){
                    
                    $require_file =     !empty($files[$numFichierout]->require) ?       self::FUNKY.$files[$numFichierout]->require.self::PEXTENSION    : null;
                    // $include_file =     !empty($files[$numFichierout]->include) ?       self::FUNKY.$files[$numFichierout]->include.self::PEXTENSION    : null;                    
                    $vue_file =         !empty($files[$numFichierout]->vue) ?           self::VUES.$files[$numFichierout]->require.self::PEXTENSION     : null;

                    $aremplacer =       !empty($files[$numFichierout]->aremplacer) ?    $files[$numFichierout]->aremplacer                              : null;
                    $visible =          !empty($files[$numFichierout]->visible) ?       $files[$numFichierout]->visible                                 : null;
                    
                    
                    if ($require_file) // si une page de traitememt php est demandé en require
                    {   
                        $requiredFile = file_exists($require_file) ?  $this->get_File_to_use('actions',$require_file,"require_once",$this->get_errorphrase(__FILE__,__FUNCTION__,__LINE__)) : false;
                    }

                    // if ($include_file) // si une page de traitememt php est demandé en include
                    // {   
                    //     $includeFile = file_exists($include_file) ?  $this->get_File_to_use('actions',$include_file,"include_once",$this->get_errorphrase(__FILE__,__FUNCTION__,__LINE__)) : false;
                    // }
                    
                    if ($vue_file)// vues
                    {
                        if ($visible)
                        {
                            $vueFile = file_exists($vue_file) ?  $this->get_File_to_use('actions',$vue_file,"file_get_contents",$this->get_errorphrase(__FILE__,__FUNCTION__,__LINE__)) : false;
                            
                            if ($aremplacer && $requiredFile && $vueFile)
                            {
                                $paquethtml .= preg_replace('{{'.$files[$numFichierout]->aremplacer."}}", '010{{COUCOU}}1000101', $vueFile);
                            }
                            $check[$nbCheck++] = "file_get_contents(:".$vue_file.",,)";
    
                        }
                    }
                }
            }
        }
        if (!empty($check))
        {
            $_SESSION['cms']['check'] = $check;
        }
        return $paquethtml;
    }
    // --------------------------------------------------------------------------------
    private function get_Current_Page(){
        
        $new_current_page = null;               // page vide

        $Posted = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

        // on ne prend qu'une page existante dan la liste
        for ($i=0; $i < count($this->_ObjJson->pages); $i++){                    // on prend la liste dess page existantes dans le json
            if (preg_match("'".$this->_ObjJson->pages[$i]."'",$Posted)){         // la page est elle dans l'url ??
                $new_current_page = $this->_ObjJson->pages[$i];                  // si oui on prend le nom 
                // break;                                                        // on stop ou pas pour chopper la dernier
            }
        }

        // si on trouve une page dans l'url
        if (!empty($new_current_page)) 
        {   
            $this->set_Current_Page($new_current_page); 
            $_SESSION['cms']['user']['current_page'] = $this->_current_page;         
        }
        else
        {   // sinon on met la page par defaut ou pas
            $_SESSION['cms']['log'][] = $this->get_errorphrase(__FILE__,__FUNCTION__,__LINE__,null," Action => initialisation de current_page ".$new_current_page.") : ");
            $this->set_Current_Page($this->_default_page);  // page par default
            $new_current_page = $this->_default_page;       // page par default
        }
        return $new_current_page;
    }
    // --------------------------------------------------------------------------------
    private function do_RequireFile($new_current_page){
        
        print_air($this->_ObjJson->$new_current_page,__CLASS__."->".__FUNCTION__."['$new_current_page']");
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
                    print_air('il manque le fichier '.$fichierrequire,"erreur do_RequireFile");
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
        $this->_current_page = get_clean($newvalue);
    }
    private function get_Default_Page(){
        return $this->_default_page;
    }
    // --------------------------------------------------------------------------------
    /**
     * getter de fichiers exterieurs
     * @param string $file fichier a traiter
     * @param string $type require_once|include_once|file_get_contents
     * @return mixed content|true if succes|false if fail
     */
    private function get_File_to_use($action,$file,$type,$coment=null){
        if (file_exists($file))
        {
            switch($type)
            {
                case "require_once":
                    require_once($file);
                    $_SESSION['cms'][$action][$this->_Nlog++] = $this->_Nlog."]".$coment." ".$type."($file) in:".__FUNCTION__.".";
                    return true;
                break;
                
                case "include_once":
                    include_once($file);
                    $_SESSION['cms'][$action][$this->_Nlog++] = $this->_Nlog."]".$coment." ".$type."($file) in:".__FUNCTION__.".";
                    return true;
                break;
                
                case "file_get_contents":
                    $_SESSION['cms'][$action][$this->_Nlog++] = $this->_Nlog."]".$coment." ".$type."($file) in:".__FUNCTION__.".";
                    return file_get_contents($file);
                break;
                
                default :
                    $_SESSION['cms']['errors'][$this->_Nlog++] = $this->_Nlog."]".$coment." ERROR SWITCH in:".__FUNCTION__.".";
                    return false;
                break;
            }
        }
        else
        {
            $_SESSION['cms']['errors'][$this->_Nlog++] = $this->_Nlog."]"."Le fichier n'existe pas... ".$coment." la methode $type($file) n'a pas fonctionnée";
            return false;
        }
    }
    // --------------------------------------------------------------------------------
    // MODULE ERREURS
    /**
     * use : get_errorphrase(__FILE__,__FUNCTION__,__LINE__,{'one','more'},'commentaires')
     * @param string $file use to be __FILE__ with FULL url
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
     * use : get_errorphrase(__FILE__,__FUNCTION__,__LINE__)
     * @param string $file use to be __FILE__ with FULL url
     * @param string $function use to be __FUNCTION__
     * @param string $line use to be __LINE__
     * @param string $arguments use to be indexed arguments from function
     * @param string $coment use string
     * @return array
     */
    // --------------------------------------------------------------------------------
    private function get_errorphraseindex($file,$function,$line,$arguments=null,$coment=null){
        // if ('array' == gettype($arguments)) 
        // {
        //     $splitedarguments = '';
        //     $i=0;
        //     foreach($arguments as $key => $value)
        //     {
        //         $splitedarguments .= (++$i < count($arguments)) ? $value.',' : $value;
        //     }
        //     $arguments = $splitedarguments;
        // }
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