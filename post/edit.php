<?php 

error_reporting(E_ALL);
ini_set('display_errors','On');
  // start a session //
  session_start(); 
  
  // include the database Class //
  require_once '../classes/Database.class.php';

	$db=new Database();
	$db->connectSQL();
	
$user = $_SESSION["user_id"];

var_dump($_GET);

if(isset($_POST["publik"])){
	$db->executeSQL("UPDATE Users set publik='".mysql_real_escape_string($_POST["publik"])."' where id=$user","UPDATE");
}
if(isset($_GET["publik"])){
	$db->executeSQL("UPDATE Users set publik='".mysql_real_escape_string($_GET["publik"])."' where id=$user","UPDATE");
	$location="/";
}
if(isset($_POST["parti_2010"])){
	$db->executeSQL("UPDATE Users set parti2010='".mysql_real_escape_string($_POST["parti_2010"])."' where id=$user","UPDATE");
//	print("UPDATE Users set parti2010='".mysql_real_escape_string($_POST["parti_2010"])."' where id=$user");
}
if($location)
	header("Location: ".$location);	
else
	header("Location: ".$_SERVER["HTTP_REFERER"]);	
?>