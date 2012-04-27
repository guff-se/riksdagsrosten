<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
  // start a session //
  session_start(); 
  
  // include the database Class //
  require_once '../classes/Database.class.php';

	$db=new Database();
	$db->connectSQL();
if(isset($_POST["dok_id"]))
	$dok_id=$_POST["dok_id"];
else
	die("no dok_id");

if(isset($_POST["visible"])) {
	if($_POST["visible"]==1)
		$SQL="update Utskottsforslag set visible=1 where dok_id='$dok_id'";
	else
		$SQL="update Utskottsforslag set visible=0 where dok_id='$dok_id'";
	$db->executeSQL($SQL,"UPDATE");
	
	$result=$db->executeSQLRows("select organ from Utskottsforslag where visible=1 group by organ");

	foreach($result as $r) {
		$db->executeSQL("update Organ set aktiv=1 where organ='$r->organ'","UPDATE");
	}
			
	header("Location: ".$_SERVER["HTTP_REFERER"]);
}

if(isset($_POST["titel_orig"])) {
	$titel=htmlspecialchars($_POST["titel"]);
	$bik=htmlspecialchars($_POST["bik"]);
	$fraga=htmlspecialchars($_POST["fraga"]);
	$SQL="update Utskottsforslag set titel='$titel', bik='$bik', fraga='$fraga' where dok_id='$dok_id'";
	$db->executeSQL($SQL,"UPDATE");
}
?>
