<?php
require_once 'dbconfig.php';

$aujourdhui = date("Y-m-d");
$tableau_actions = "";
$msg = "";
$msg1 = "";
$msg2 = "";
$inscription="";
$msg = "";
$opt_inscription=FALSE;

class USER{
	private $conn;
	public $db1 = "BC_utilisateurs";
	public $db2 = "BC_membres";
	public $db3 = "BC_articles";
	public $db4 = "BC_locations";

	public function __construct(){
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}

	public function runQuery($sql){
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

	public function lasdID(){
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}

	public function register($uname,$email,$upass,$code){
		try {
			$password = md5($upass);
			$stmt = $this->conn->prepare("INSERT INTO BC_utilisateurs(userName,userEmail,userPass,tokenCode) VALUES(:user_name, :user_mail, :user_pass, :active_code)");
			$stmt->bindparam(":user_name",$uname);
			$stmt->bindparam(":user_mail",$email);
			$stmt->bindparam(":user_pass",$password);
			$stmt->bindparam(":active_code",$code);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function login($email,$upass){
		//fonction appelé par index.php pendant le login
		try{
			$stmt = $this->conn->prepare("SELECT * FROM BC_utilisateurs WHERE userEmail=:email_id");
			$stmt->execute(array(":email_id"=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount() == 1){
				if($userRow['userStatus']=="Y"){
					if($userRow['userPass']==md5($upass)){
						// ici on profite pour charger des données en session
						$_SESSION['userSession'] = $userRow['userID'];
						$_SESSION['userPatronyme'] = $userRow['userName'];
						return true;
					}
					else{
						header("Location: index.php?error");
						exit;
					}
				}
				else{
					header("Location: index.php?inactive");
					exit;
				}
			}
			else{
				header("Location: index.php?error");
				exit;
			}
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function is_logged_in() {
		if(isset($_SESSION['userSession'])) {
		 return true;
		}
	}

	public function redirect($url){
		header("Location: $url");
	}

	public function logout(){
		session_destroy();
		$_SESSION['userSession'] = false;
	}

	function send_mail($email,$message,$subject){
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "ssl";
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465;
		$mail->AddAddress($email);
		$mail->Username ="gestionbarrecode@gmail.com";
		$mail->Password ="Gestion_2000";
		$mail->SetFrom('gestionbarrecode@gmail.com','Administrateur ImcAlternance.com');
		$mail->AddReplyTo("'gestionbarrecode@gmail.com","Administrateur ImcAlternance.com");
		$mail->Subject = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}

	public function quiprends($barrecodequi){
		try{
			$stmt = $this->conn->prepare("SELECT * FROM BC_utilisateurs WHERE barrecode=:qui");
			$stmt->execute(array(":qui"=>$barrecodequi));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount() == 1){
						$_SESSION['codedequi'] = $userRow['barrecode'];
						return true;
			}
			else{
				//header("Location: index.php?error");
				exit;
			}
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
	public function isitrightvar($email,$type){
		switch ($variable) {
			case "mail":
				if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $email)) {
					return TRUE;
				}
		}
	}

	public function emprunter($barrecodea,$idArticle,$c){

		$this->lastAction($idArticle,"Out");

		try {

			$aujourdhui = date("Y-m-d");
			$stmt = $this->conn->prepare("INSERT INTO BC_locations (id_article,action,id_membre,commentaires,date) VALUES(:idArticle, :action, :qui, :comment, :date)");
			$stmt->execute(array(":idArticle"=>$idArticle,":action"=>"Out",":qui"=>$barrecodea,":comment"=>$c,":date"=>$aujourdhui));
			return $stmt;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function rendre($id_article,$c){
		echo("Article ID: ".$id_article."<br>");

		$rep= $this->lastAction($id_article,"in");

		if ($rep == "NO") {
			return true;
		}
		try {
			$aujourdhui = date("Y-m-d");
			$stmt = $this->conn->prepare("INSERT INTO BC_locations(id_article,action,commentaires,date) VALUES(:id_article, :action, :comment, :date)");
			$stmt->execute(array(":id_article"=>$id_article,":action"=>"in",":comment"=>$c,":date"=>$aujourdhui));
			return $stmt;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}

	}



	//public function inventaire($id_article,$c){
		////die("Article ID: ".$id_article."<br>");

		//$this->lastAction($id_article,"inv");

		//try {
			//$sql="update BC_articles SET valide=1 WHERE article_id=:id_article";
			//$stmt = $this->conn->prepare($sql);
			//$stmt->execute(array(":id_article"=>$id_article));
		//} catch(PDOException $ex){
			//echo $ex->getMessage();
		//}


		//try {
			//$aujourdhui = date("Y-m-d");
			//$stmt = $this->conn->prepare("INSERT INTO BC_locations(id_article,action,commentaires,date) VALUES(:id_article, :action, :comment, :date)");
			//$stmt->execute(array(":id_article"=>$id_article,":action"=>"inv",":comment"=>$c,":date"=>$aujourdhui));
			//return $stmt;
		//}
		//catch(PDOException $ex){
			//echo $ex->getMessage();
		//}
	//}

	public function changeStatut($id_article,$statut){

		$sql="UPDATE BC_articles SET valide=:statut WHERE article_id=:id_article";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(array(":id_article"=>$id_article, ":statut"=>$statut));

	}

	public function rendremobile($barrecodeb,$c){
		try {
			//$password = md5($upass);
			$aujourdhui = date("Y-m-d");
			$stmt = $this->conn->prepare("INSERT INTO BC_locations(barrecode,action,commentaires,date) VALUES(:barrecode, :action, :comment, :date)");
			$stmt->execute(array(":barrecode"=>$barrecodeb,":action"=>"in",":comment"=>$c,":date"=>$aujourdhui));
			return "ok";
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function infoArticle($barreCode){

		$sql="SELECT u2.barrecode, u2.nom_article,
				case
					when u2.Valide LIKE '0' then 'HS'
					when u2.Valide LIKE '1' then 'OK'
					when u2.Valide LIKE '2' then 'En réparation'
					when u2.Valide LIKE '3' then 'Spécial'
				end as situation,
				IF(u1.date IS NULL,'Absent','OK') as etat, MAX(u3.date) as lastVue
			FROM BC_articles u2
			LEFT JOIN `BC_locations` u1 ON u2.article_id=u1.id_article AND u1.`action`='INV'
				AND DATE=(SELECT date
					FROM `BC_locations`
					WHERE action='INV'
					GROUP BY date
					ORDER BY date DESC
					LIMIT 1)
			LEFT JOIN BC_locations u3 ON u3.id_article=u2.article_id AND u3.action<>'INV'
			WHERE u2.barrecode=:barrecode OR u2.nom_article=:barrecode
			GROUP BY u2.barrecode";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(array(":barrecode"=>$barreCode));
		$rows = $stmt->fetchALL();

		return $rows;


	}
	// dans listequoi.php
	public function listeArticles(){
		try {
			$stmt = $this->conn->prepare("SELECT * FROM BC_articles ORDER BY id DESC");
			$stmt->execute();
			$rows = $stmt->fetchALL();
			return $rows;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}
	// dans listequi.php
	public function listeclients(){
		try {
			$stmt = $this->conn->prepare("SELECT * FROM BC_membres WHERE annee != 0 ORDER BY section, id DESC");
			$stmt->execute();
			$rows = $stmt->fetchALL();
			return $rows;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}


	// dans listequand.php
	public function listeactionspardate($orderN){
		try {
			$order = " DESC";
			if ($orderN == 1){$order = "";}
			$aujourdhui = date("Y-m-d");
			//$where = " WHERE date = "0000-00-00"; //. date("Y-m-d");
			$stmt = $this->conn->prepare("SELECT * FROM BC_locations WHERE date = '".$aujourdhui."' ORDER BY date".$order);
			$stmt->execute();
			$rows = $stmt->fetchALL();
			return $rows;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function listeNonRendu(){
		try {
			$sql="SELECT a.article_id, a.barrecode,a.nom_article, a.heureSortie, a.heureRetour, CONCAT(c.nom,' ',c.prenom) as utilisateur FROM (SELECT  b0.article_id, b0.barrecode,b0.nom_article, MAX(b1.dateheure) as heureSortie, MAX(b2.dateheure) as heureRetour FROM BC_articles as b0 LEFT join BC_locations as b1 ON b0.article_id=b1.id_article AND b1.action='OUT' LEFT JOIN BC_locations AS b2 ON b0.article_id = b2.id_article AND b2.action='IN' WHERE b1.dateheure IS NOT NULL GROUP BY b0.barrecode  HAVING TIMEDIFF( MAX(b1.dateheure), MAX(b2.dateheure))>0 ) as a LEFT JOIN BC_locations b ON a.article_id=b.id_article AND b.dateheure=a.heureSortie LEFT JOIN BC_membres c ON c.membre_id=b.ID_membre";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$rows = $stmt->fetchALL();
			return $rows;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function listePlusEmprunter(){

		try{
			$sql="SELECT BC_articles.barrecode, BC_articles.nom_article, MAX(date) as lastIN, DATEDIFF(CURRENT_DATE,MAX(date)) as delta
				FROM `BC_locations`
				LEFT JOIN BC_articles ON BC_articles.article_id=BC_locations.id_article
				WHERE action= 'IN' AND valide=1
				GROUP BY id_article
				HAVING DATEDIFF(CURRENT_DATE,MAX(date))>30
				ORDER by MAX(dateheure);";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$rows = $stmt->fetchALL();
			return $rows;
		} catch(PDOException $ex){
			echo $ex->getMessage();
		}

	}

	public function listeInventaire(){

		try {
			$sql="SELECT u2.barrecode, u2.nom_article, IF(u1.date IS NULL,'Absent','OK') as etat, MAX(u3.date) as lastVue
				FROM BC_articles u2
				LEFT JOIN `BC_locations` u1 ON u2.article_id=u1.id_article AND u1.`action`='INV'
				AND DATE=(SELECT date
					FROM `BC_locations`
					WHERE action='INV'
					GROUP BY date
					ORDER BY date DESC
					LIMIT 1)
				LEFT JOIN BC_locations u3 ON u3.id_article=u2.article_id AND u3.action<>'INV'
				WHERE u2.valide=1
				GROUP BY u2.barrecode
				ORDER BY etat, u2.nom_article";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$rows = $stmt->fetchALL();
			return $rows;
		}catch(PDOException $ex){
			echo $ex->getMessage();
		}

	}
	public function listeStats(){
		try {
			$sql="SELECT AVG(a.compteur) as moyenne, MAX(a.compteur) as maximum FROM (SELECT COUNT(id) as compteur FROM `BC_locations` WHERE BC_locations.action='OUT' GROUP BY date HAVING count(id)>5 ORDER By date DESC LIMIT 200) as a";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$rows = $stmt->fetchALL();
			return $rows;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function listeJourCharge(){
		$this->updateEtatSortie();

		try {
			$sql="SELECT `date`,`dateheure`,`etatSortie` FROM `BC_locations` WHERE action='OUT' ORDER BY `etatSortie` DESC LIMIT 10";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$rows = $stmt->fetchALL();
			return $rows;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function listeChargeParPromotion(){
		$this->updateEtatSortie();

		try {
			$sql="SELECT a.section, a.annee, AVG(a.total) as moyenne, MAX(a.total) as maximum FROM (SELECT u2.section,u2.annee, u1.date, COUNT(u1.id) as total FROM `BC_locations` u1 LEFT JOIN BC_membres u2 ON u1.ID_membre=u2.membre_id WHERE u1.action='OUT' GROUP BY u2.section, u2.annee, u1.date ORDER by COUNT(u1.id) DESC ) as a GROUP BY a.section, a.annee ORDER BY  AVG(a.total) DESC";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$rows = $stmt->fetchALL();
			return $rows;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}

	public function listeactions($orderN,$champs="dateheure"){
		try {
			$order = " DESC";
			if ($orderN == 1){$order = "";}
			$stmt = $this->conn->prepare("SELECT * FROM BC_locations ORDER BY ".$champs.$order);
			$stmt->execute();
			$rows = $stmt->fetchALL();
			return $rows;
		}
		catch(PDOException $ex){
			echo $ex->getMessage();
		}
	}


	public function cleanvar($mavariable,$typedevarriable){
		$tempo = $mavariable;

		switch ($typedevarriable) {
			case "espace":
				$tempo =  htmlentities(trim($tempo," "), ENT_QUOTES | ENT_IGNORE, "UTF-8");
				break;
			case "entier":
				$tempo =  intval($tempo);
				break;
			case "texte":
				$tempo = htmlentities(strip_tags($tempo), ENT_QUOTES | ENT_IGNORE, "UTF-8");
				break;
			case "passe":
				$tempo =  htmlentities(strip_tags($tempo), ENT_QUOTES , "UTF-8");
				break;
			case "mail":
				$tempo =  htmlentities($tempo);
				break;
		}
		return $tempo;
	}



	public function isgood($mavariable,$type){
		// $tempo = htmlentities($mavariable);
		$reponse = False;
		switch ($type) {
			case "plusgrandque":
				if (strlen($mavariable)>6){
					$reponse = True;
				}
				break;
			case "entier":
				if (is_integer($mavariable)){
					$reponse = True;
				}
				break;
			case "mail":
				if (filter_var($mavariable, FILTER_VALIDATE_EMAIL)){
					$reponse = True;
				}
				break;
		}
		return $reponse;
	}

	public function quiesttu($barrecode,$key){
		$listing = "";
		try{
			$stmt = $this->conn->prepare("SELECT * FROM BC_utilisateurs WHERE barrecode=:barrecode");
			$stmt->execute(array(":barrecode"=>$barrecode));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount() == 1){
				$method = "aes-256-cbc";
				// $method = "AES-256-CBC";
				$password = "laurence";
				$iv_length = openssl_cipher_iv_length($method);
				$iv = openssl_random_pseudo_bytes($iv_length);

				$listing = array(
						//'id'=>$userRow['id'],
						//'nom'=>md5($userRow['nom']),
						'a'=>$userRow['barrecode'],
						'b'=>$userRow['prenom']
						// 'codebarre'=>openssl_encrypt($userRow['barrecode'],$method,$password, OPENSSL_RAW_DATA ,$iv)
						// 'nom'=>openssl_encrypt($userRow['nom'], $method, $password,,1),
						// 'prenom'=>openssl_encrypt($userRow['prenom'], $method, $password,,1),
						// 'codebarre'=>$userRow['barrecode']
				);
				$listing = json_encode($listing);
			}
		}
		catch(PDOException $ex){
			$listing = "vide";
		}
		return $listing;
	}

	public function quiestquoi($barrecode,$key){
		$listing = "";
		try{
			$stmt = $this->conn->prepare("SELECT * FROM BC_articles WHERE barrecode=:barrecode");
			$stmt->execute(array(":barrecode"=>$barrecode));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

			if($stmt->rowCount() == 1){
				$listing = array(
						'a'=>$userRow['barrecode'],
						'b'=>$userRow['nom_article']
				);
				$listing = json_encode($listing);
			}
		}
		catch(PDOException $ex){
			$listing = "vide";
		}
		return $listing;
	}




	private function lastAction($idArticle,$actionEnCours){

		//die($idArticle);

		$sql="SELECT * FROM `BC_locations` WHERE `id_article`=:idArticle AND `action`<>'INV' ORDER BY `dateheure` DESC LIMIT 1";
		$req=$this->conn->prepare($sql);
		$req->execute(array(":idArticle"=>$idArticle));
		$lastActionReq = $req->fetch(PDO::FETCH_ASSOC);
		echo (" ".$lastActionReq['action']);
		if($actionEnCours == "in" && $lastActionReq['action'] == "in"	){
			//update de cet enregistrement

			$c="Modification de retour suite sortie non enregistré";
			try {
				//die("test".$idArticle." ".$actionEnCours." ".$lastActionReq['action']);
				$sql="UPDATE BC_locations SET dateheure=CURRENT_TIME, date=DATE_FORMAT(CURRENT_TIME, '%Y-%m-%d'),commentaires=:comment WHERE id=:id";
				$stmt = $this->conn->prepare($sql);
				$stmt->execute(array(":id"=>$lastActionReq['id'],":comment"=>$c));
				return "NO";

			} catch(PDOException $ex){
				echo $ex->getMessage();
				die();
			}

		} elseif($lastActionReq['action'] == "Out" && $actionEnCours == "Out"  ) {
			//INSERT d'une rentrée automatique
			$c="Rentrée Automatique";
			//die("dans le if");
			$sql="INSERT INTO BC_locations (`id_article`,`dateheure`,`date`,`action`,`commentaires`) VALUES (:idArticle, ADDTIME(CURRENT_TIME, '-10'), DATE_FORMAT(CURRENT_TIME, '%Y-%m-%d'), 'in',:comment)";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(array(":idArticle"=>$idArticle,":comment"=>$c));

		} elseif($lastActionReq['action'] == "Out" && $actionEnCours == "inv"  ) {
			//INSERT d'une rentrée automatique
			echo("dans le IF");
			$c="Rentrée Automatique suite inventaire";
			//die("dans le if");
			$sql="INSERT INTO BC_locations (`id_article`,`dateheure`,`date`,`action`,`commentaires`) VALUES (:idArticle, ADDTIME(CURRENT_TIME, '-10'), DATE_FORMAT(CURRENT_TIME, '%Y-%m-%d'), 'in',:c)";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute(array(":idArticle"=>$idArticle,":c"=>$c));
		}

		//die("fin de la fonction");
	}

	private function updateEtatSortie(){
		try {
			$sql="SET @sortie=0; SELECT `dateheure`,`action`, @sortie:=IF(`action`='OUT',@sortie+1,@sortie-1)  FROM `BC_locations`  ORDER BY dateheure;";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
		}
		catch(PDOException $ex){
			echo $ex->getMessage();

		}
	}

}


// FONCTIONS A INTEGRER A LA CLASS
//----------------------------------------------------------
//----------------------------------------------------------
function do_html($message,$monarray,$monarray2,$nomlist,$type){
	$listing = "";
	$contenu_entier = PHP_EOL;
	for ($i = 0; $i < count($monarray); $i++){
		if ($type == 1 and $i == 1) {

		}
		else {
			$listing .= '								<th><a title="classer par '.$monarray[$i].' Desc" href="?a='.$i.'">'.$monarray[$i].'</a>&#8239<a title="classer par '.$monarray[$i].' Asc" href="?a='.$i.$i.$i.'">-</a></th>'.PHP_EOL;
		}
	}
	$listing2 ="";
	for ($j = 0; $j < count($monarray2); $j++){
		$listing2 .= "							<tr>".PHP_EOL;
		for ($k = 0; $k < $i; $k++){
			$toto = $monarray2[$j][$monarray[$k]];
			if ($type==1){
				$toto2 = $monarray2[$j][$monarray[$k+1]];
				if ($k == 0) $toto = $toto2.".".$toto;
				if ($k != 1) $listing2 .= "								<td>".$toto."</td>".PHP_EOL;
			}
			else {
				$listing2 .= "								<td>".$toto."</td>".PHP_EOL;
			}
		}
		$listing2 .= "							</tr>".PHP_EOL;
	}
	$contenu_entier .= '			<div class="sl"></div>'.PHP_EOL;
	$contenu_entier .= '			<div class="container blanc"><!-- container dynamique -->'.PHP_EOL;
	//$contenu_entier .= '					<div class="row">'.PHP_EOL;
	$contenu_entier .= '						<h3>'.$message.'</h3>'.PHP_EOL;
	//$contenu_entier .= '					</div>'.PHP_EOL;
	//$contenu_entier .= '					<div class="row">'.PHP_EOL;
	$contenu_entier .= '						<table class="table table-striped ">'.PHP_EOL;
	$contenu_entier .= '							<thead>'.PHP_EOL;
	$contenu_entier .= '								<tr>'.PHP_EOL;
	$contenu_entier .= $listing;
	$contenu_entier .= '								</tr>'.PHP_EOL;
	$contenu_entier .= '							</thead>'.PHP_EOL;
	$contenu_entier .= '							<tbody id="'.$nomlist.'">'.PHP_EOL;
	$contenu_entier .= $listing2;
	$contenu_entier .= '							</tbody>'.PHP_EOL;
	$contenu_entier .= '						</table>'.PHP_EOL;
	//$contenu_entier .= '					</div>'.PHP_EOL;
	$contenu_entier .= '				</div> <!-- /container dynamique -->'.PHP_EOL;
	return $contenu_entier;
}
//----------------------------------------------------------
//----------------------------------------------------------
function do_container($message){
	$contenu_entier = PHP_EOL;
	$contenu_entier .= '				<div class="container"><!-- container dynamique -->';
	$contenu_entier .= '					<div class="row">';
	$contenu_entier .= '						<h3>'.$message.'</h3>';
	$contenu_entier .= '					</div>';
	$contenu_entier .= '				</div> <!-- /container dynamique -->';
	return $contenu_entier;
}
//----------------------------------------------------------
//----------------------------------------------------------
function do_form($message,$recherche,$recherche2,$qui,$url,$comment){
	if ($recherche=="") {$message="Présentez votre carte ! ";}
	if ($recherche!="") {$message="Présentez Le materiel ! ";}
	if ($recherche2!="") {$message="Enregistrement effectué ! ";}

	$contenu_entier = '		<div class="container">'.PHP_EOL;
	$contenu_entier .= '			<div class="row">'.PHP_EOL;
	$contenu_entier .= '				<h3>'.$message.'</h3>'.PHP_EOL;
	$contenu_entier .= '			</div>'.PHP_EOL;
	$contenu_entier .= '			<form name="recherche" class="form-horizontal" action="'.$url.'" method="post">	'.PHP_EOL;
	$contenu_entier .= '				<div class="controls">'.PHP_EOL;

	if ($recherche=="" and $recherche2=="") {
		$contenu_entier .= '					<input onfocus="this.select();" id="Abarrecode" name="Ibarrecode" type="text" placeholder="Code barre" value="'.$_SESSION['qui_codebarre'].'">'.PHP_EOL;
	}
	elseif ($recherche!="" and $recherche2=="") {
		//$contenu_entier .= '					<input id="comment" name="comment" type="text" value="comment">'.PHP_EOL;
		$contenu_entier .= '					<input onfocus="this.select();" id="Abarrecode2" name="Ibarrecode2" type="text" value="'.$recherche2.'">'.PHP_EOL;

		//$contenu_entier .= '					<input id="Abarrecode" name="Ibarrecode" type="text" placeholder="Code barre" value="'.$recherche.'">'.PHP_EOL;
		//$contenu_entier .= '					<input name="Aqui" type="text" value="'.$qui.'">'.PHP_EOL;
	}
	elseif (
		$recherche2!=""){$contenu_entier .= 'merci';
	}
	$contenu_entier .= '				</div>	'.PHP_EOL;
	$contenu_entier .= '			</form>'.PHP_EOL;
	$contenu_entier .= '		</div>'.PHP_EOL;
	return $contenu_entier;
}
//----------------------------------------------------------
//----------------------------------------------------------
function do_form2($recherche,$recherche2,$qui,$url,$comment){
	$message="";
	if ($recherche=="") {$message="Présentez votre carte ! ";}
	if ($recherche!="") {$message="Présentez Le materiel ! ";}
	if ($recherche2!="") {$message="Enregistrement effectué ! ";}

	$contenu_entier = PHP_EOL.'			<div class="container">'.PHP_EOL;
	$contenu_entier .= '				<form class="form-signin" method="post">'.PHP_EOL;
	$contenu_entier .= '					<h2 class="form-signin-heading">'.$message.'</h2><hr />'.PHP_EOL;





	if ($recherche=="" and $recherche2=="") {
		$contenu_entier .= '					<input id="barrecodea" type="text" onfocus="this.select();" class="input-block-level" placeholder="barrecodea" name="barrecodea" required /><hr />'.PHP_EOL;
	}
	elseif ($recherche!="" and $recherche2=="") {
		//$contenu_entier .= '					<input id="barrecodea" type="text" class="input-block-level" value="'.$recherche.'" name="barrecode" /><hr />'.PHP_EOL;
		$contenu_entier .= '					<input id="barrecodeb" type="text" onfocus="this.select();" class="input-block-level" placeholder="barrecodeb" name="barrecodeb" required /><hr />'.PHP_EOL;
	}
	elseif ($recherche2!=""){
		$contenu_entier .= 'merci';
	}
	//$contenu_entier .= '					<button class="btn btn-large btn-primary" style="float:center;" type="submit" name="btn-signup">Tester le code.</button>';
	//$contenu_entier .= '					<a href="#" style="float:right;" class="btn btn-large">Sign In</a>';


	$contenu_entier .= '				</form>'.PHP_EOL;
	$contenu_entier .= '			</div>'.PHP_EOL;
	return $contenu_entier;
}
//----------------------------------------------------------
//----------------------------------------------------------
function do_formcodebarre($recherche,$recherche2,$qui,$url,$comment,$message){
	//$message="";
	if ($message=="") {$message="(00) Qui êtes vous ???";}
	// if ($recherche!="") {$message="Présentez Le materiel ! ";}
	// if ($recherche2!="") {$message="Enregistrement effectué ! ";}

	$contenu_entier = PHP_EOL.'			<div class="container">'.PHP_EOL;
	$contenu_entier .= '				<form class="form-signin" method="post">'.PHP_EOL;
	$contenu_entier .= '					<h2 class="form-signin-heading">'.$message.'</h2><hr />'.PHP_EOL;

	if ($recherche=="" and $recherche2=="") {
		$contenu_entier .= '					<input id="barrecodea" type="text" onfocus="this.select();" class="input-block-level" placeholder="barrecode" name="barrecodea" required /><hr />'.PHP_EOL;
	}
	elseif ($recherche!="" and $recherche2=="") {
		//$contenu_entier .= '					<input id="barrecodea" type="text" class="input-block-level" value="'.$recherche.'" name="barrecode" /><hr />'.PHP_EOL;
		$contenu_entier .= '					<input id="barrecodeb" type="text" onfocus="this.select();" class="input-block-level" placeholder="barrecode" name="barrecodeb" required /><hr />'.PHP_EOL;
	}
	elseif ($recherche2!=""){
		$contenu_entier .= 'merci';
	}
	//$contenu_entier .= '					<button class="btn btn-large btn-primary" style="float:center;" type="submit" name="btn-signup">Tester le code.</button>';
	//$contenu_entier .= '					<a href="#" style="float:right;" class="btn btn-large">Sign In</a>';

	$contenu_entier .= '				</form>'.PHP_EOL;
	$contenu_entier .= '			</div>'.PHP_EOL;
	return $contenu_entier;
}

function do_formChangeSituation($recherche,$recherche2,$article,$url,$comment,$message){

	$contenu_entier = PHP_EOL.'			<div class="container">'.PHP_EOL;
	$contenu_entier .= '				<form class="form-signin" method="post">'.PHP_EOL;

	$contenu_entier .= '				<select name="changeStatut" onfocus="this.select();"><option value=1>OK</option><option value=0>HS</option><option value=2>En réparation</option><option value=3>Spécial</option></select>'.PHP_EOL;
	$contenu_entier .= '				<input type=submit>'.PHP_EOL;
	$contenu_entier .= '				</form>'.PHP_EOL;
	$contenu_entier .= '			</div>'.PHP_EOL;
	return $contenu_entier;
}
?>