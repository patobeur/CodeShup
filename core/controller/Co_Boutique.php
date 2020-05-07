<?php
// ConTroller > Boutique

	$replace_in_vue = [
		'TITRE'			=> 'Page Boutique'
		,'PSEUDO'		=> (!empty($_SESSION['profil']) ? ' '.ucfirst(get_clean($_SESSION['profil']['username'])) : 'Visiteur')
		,'ARIANNE'		=> ''
		,'ARTICLES'		=> ''
		,'MENUCAT'		=> ''
	];


	if (class_exists('Db')) {
		$Boutique  = new Boutique();
		$lescategories = $Boutique->get_categories();

		// --- PANIER ADD SUP
		if (!empty($lescategories)) 
		{
			if (isset($_GET['additem']) AND !empty($_GET['additem']))
			{
				$item_id = (int) get_clean($_GET['additem']);
				if (is_integer($item_id))
				{
					$Boutique->set_panier('add', $item_id);
				}
			}
			if (isset($_GET['supitem']) AND !empty($_GET['supitem']))
			{
				$item_id = (int) get_clean($_GET['supitem']);
				if (is_integer($item_id))
				{
					$Boutique->set_panier('sup', $item_id);
				}
			}



			if (isset($_GET['boutique']) AND empty($_GET['boutique']) AND !empty($_GET['cat']))
			{
				$Boutique->set_categorie(get_clean($_GET['cat']));
				foreach ($lescategories as $value)
				{
					if ($value->label === get_clean($_GET['cat']))
					{
						$categorie = $value;
						break;
					}
				}
			}
			
			// ---  Menus
			$retour = [];
			$retour_catmenu = '<a class="list-group-item" href="?boutique" title="Toutes Catégories">Toutes Catégories</a>';
			$retour_categories = '';
			$capsuleMenu = ['<a href="#" class="list-group-item">','</a>',PHP_EOL];
			foreach($lescategories as $key => $value){
				$retour[] = $value->label;
				$retour_catmenu .= make_href($value->label,'list-group-item',null);
				$retour_categories .= make_href($value->label,'cat_lien',['<li class="cat_item">','</li>']);
			}
			//						debut											fin					breakline
			$capsule = ['<ul class="cat_arianne">',	'</ul>',	PHP_EOL];

			// ---
			if (!empty($categorie))
			{
				$lesarticles = $Boutique->get_articlesByCategorieId($categorie);
			}
			else
			{
				$lesarticles = $Boutique->get_articles();
			}


		}


		// --- Liste des fiches articles
		if (!empty($lesarticles)) 
		{
			// on prend la vue article
			$vue_article = $this->get_File_to_use('vue',AAINVUE."article".AAEXTPHP,"file_get_contents",$this->get_errorphrase('article',__FUNCTION__,__LINE__));
			
			$retour_articles = '';

			foreach($lesarticles as $key => $value){
				$retour_articles .= make_fiche($value,$vue_article);
			}    
			//					debut							fin				breakline
			// $capsule_article = ['<section class="articles">',	'</section>',	PHP_EOL];
			$capsule_article = ['','',PHP_EOL];
		}
		$replace_in_vue['TITRE']		= 'Page Boutique';
		$replace_in_vue['MENUCAT']		= (!empty($retour_catmenu) ? $retour_catmenu : '');
		$replace_in_vue['ARIANNE']		= "";//(!empty($retour_categories) ? $capsule[0].$retour_categories.$capsule[1].$capsule[2] : '');
		// $replace_in_vue['PSEUDO']		= (!empty($_SESSION['profil']) ? ' '.ucfirst(get_clean($_SESSION['profil']['username'])) : 'Visiteur');
		$replace_in_vue['ARTICLES']	= (!empty($retour_articles) ? $capsule_article[0].$retour_articles.$capsule_article[1].$capsule_article[2] : '');
	}

	function is_in_stock($action, $tablo)
	{
		if(	$tablo->stock > 0)
		{
			if (isset($_SESSION['panier']) AND array_key_exists($tablo->product_id, $_SESSION['panier']))
			{
				if ($tablo->stock <= $_SESSION['panier'][$tablo->product_id]['nb'])
				{
					return false;
				}
			}
			return true;
		}
	}
	function is_in_pannier($action, $tablo)
	{
			if (isset($_SESSION['panier']) AND array_key_exists($tablo->product_id, $_SESSION['panier']))
			{
				if ($_SESSION['panier'][$tablo->product_id]['nb'] < 1)
				{
					return false;
				}
				return true;
			}
		return false;
	}
	function is_pannier($action, $tablo)
	{
		if( !isset($_SESSION['panier']) OR (isset($_SESSION['panier']) AND !count($_SESSION['panier']) > 0) )
		{
			return false;
		}
		return true;
	}

	function make_fiche($tablo,$vue){
		
		$vue = str_replace("{{articleImage}}",			'genimage.php?img=600x300',		$vue);
		$vue = str_replace("{{articleId}}",					$tablo->product_id,						$vue);
		$vue = str_replace("{{articleName}}",				$tablo->name,									$vue);
		$vue = str_replace("{{articlePrix}}",				$tablo->price,								$vue);
		$vue = str_replace("{{articleContent}}",		$tablo->content,							$vue);
		$vue = str_replace("{{articleVendor}}",			$tablo->vendor_id,						$vue);
		$vue = str_replace("{{articleStock}}",			$tablo->stock,								$vue);
		$vue = str_replace("{{altImage}}",					$tablo->name,									$vue);
		$vue = str_replace("{{articleEvaluation}}",	$tablo->evaluation,						$vue);
		$vue = str_replace("{{articleCategorie}}",	$tablo->label,								$vue);
		$vue = str_replace("{{nameVendor}}",				$tablo->vendorname,						$vue);

		$vue = str_replace("{{addPanier}}",	  is_in_stock('add', $tablo) ? '<li><a title="Ajouter au panier" href="?boutique&cat='.$tablo->label.'&additem='.$tablo->product_id.'"><i class="fa fa-shopping-cart"> +</i></a></li>' : '',	$vue);	
		$vue = str_replace("{{supPanier}}",	is_in_pannier('sup', $tablo) ? '<li><a title="Supprimer du panier" href="?boutique&cat='.$tablo->label.'&supitem='.$tablo->product_id.'"><i class="fa fa-shopping-cart"> -</i></a></li>' : '',$vue);	
		$vue = str_replace("{{urlPanier}}",	   is_pannier('pan', $tablo) ? '<li><a title="Voir le panier" href="?panier"><i class="fa fa-shopping-cart"></i></a></li>' : '',				$vue);	

		$vue = PHP_EOL.$vue.PHP_EOL;
		return $vue;
	}

	function make_href($cat,$class,$capsule=null){
		$retour = '<a class="'.$class.'" href="';
		$retour .= '?boutique';
		$retour .= '&cat=';
		$retour .= $cat;
		$retour .= '" ';
		$retour .= 'title ="';
		$retour .= $cat;
		$retour .= '">';
		$retour .= $cat;
		$retour .= '</a>';

		$retour = ($capsule != null) ? $capsule[0].$retour.$capsule[1] : $retour;
		$retour .= PHP_EOL;

		return $retour;
	}

	Page::set_replace_in_vue($replace_in_vue);
?>