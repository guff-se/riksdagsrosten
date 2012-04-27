<?php 
$PARTI["M"]="Moderaterna";
$PARTI["S"]="Socialdemokraterna";
$PARTI["V"]="Vänsterpartiet";
$PARTI["MP"]="Miljöpartiet";
$PARTI["KD"]="Kristdemokraterna";
$PARTI["C"]="Centerpartiet";
$PARTI["SD"]="Sverigedemokraterna";
$PARTI["FP"]="Folkpartiet";

$PARTISYMBOL["moderaterna"]="M";
$PARTISYMBOL["socialdemokraterna"]="S";
$PARTISYMBOL["vansterpartiet"]="V";
$PARTISYMBOL["miljopartiet"]="MP";
$PARTISYMBOL["kristdemokraterna"]="KD";
$PARTISYMBOL["centerpartiet"]="C";
$PARTISYMBOL["sverigedemokraterna"]="SD";
$PARTISYMBOL["folkpartiet"]="FP";

//error_reporting();
ini_set('display_errors','On');
session_set_cookie_params('1036800');
session_start();

function slug($string) {
    $unPretty = array('/å/','/ä/','/ö/','/ü/', '/Å/', '/Ä/', '/Ö/', '/Ü/','/é/');
    $pretty = array('a','a', 'o', 'u', 'A', 'A', 'O', 'U','e');
    $string = preg_replace($unPretty, $pretty, $string); // convert swedish characters
    $string = preg_replace('/[^a-zA-Z0-9-]/',' ',$string); // replace non-characters
    $string = str_replace(" ", "_", $string); // replace spaces by dashes
    $string = strtolower($string);  // Make it lowercase
    return $string;
}

  // include the database Class //
  require_once 'classes/Database.class.php';
	$db=new Database();
	$db->connectSQL();

if(isset($_SESSION["user_id"])) {
	$USER = $db->executeSQL("select * from Users where id='".$_SESSION["user_id"]."'","SELECT");
}


?>

<?php 

if(isset($_GET['page']))
	$page=$_GET['page'];
else {
	if(isset($USER) || isset($_SESSION['OINLOGGAD']))
		$page="start";				/// Avgör vilken sida som syns som förstasida
	else
		$page="landning";
}

if($page == "votering") {
        if(isset($_GET['id'])){
			$id=$_GET['id'];
			if($id == "kommande" || $id == "populara" || $id == "tidigare") {
				include 'pages/votering-lista.php';
			} else {
           		include 'pages/votering.php';
			}
        }else{
           include 'pages/votering-lista.php';
        }
}
else if($page == "kategori") {
        include 'pages/votering-lista.php';
}
else if($page == "pop") {
        include 'pages/votering-lista.php';
}
else if($page == "meck") {
        include 'pages/profil-meck.php';
}
else if($page == "profil") {
		if(isset($_GET['id']) && $_GET['id']=="redigera")
			include 'pages/profil-redigera.php';
        else
			include 'pages/profil.php';
}
else if($page == "parti") {
        include 'pages/parti.php';
}
else if($page == "om") {
        include 'pages/om.php';
}
else if($page == "live") {
        include 'pages/live.php';
}
else if($page == "valkommen") {
        include 'pages/valkommen.php';
}
else if($page == "ledamot") {
        if(isset($_GET['id'])){
           include 'pages/ledamot.php';
        }else{
           include 'pages/parti-lista.php';
        }
}
else if($page == "register") {
        include 'pages/register.php';
}
else if($page == "login") {
        include 'post/login.php';
}
else if($page == "logout") {
        include 'post/logout.php';
}
else if($page == "anvandarvillkor") {
        include 'pages/anvandarvillkor.php';
}
else if($page == "start") {
		$_SESSION['OINLOGGAD']=TRUE;
        include 'pages/start.php';
}
else if($page == "landning") {
        include 'pages/landning.php';
}
else {
		include 'error-docs/404.php';
}


?>
<?php include 'includes/footer.php'; ?>
        