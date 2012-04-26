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

if(isset($_GET["publik"])){
	$db->executeSQL("UPDATE Users set publik=".$_GET["publik"]." where id=$user","UPDATE");
	header("Location: /");	
}
else
	header("Location: ".$_SERVER["HTTP_REFERER"]);	
?>