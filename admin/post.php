<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
  // start a session //
  session_start(); 
  
  // include the database Class //
  require_once '../classes/Database.class.php';

	$db=new Database();
	$db->connectSQL();

foreach($_POST['ids'] as $key => $value) {
	if(isset($_POST['checks'][$key]))
		$SQL="update Utskottsforslag set status=1 where dok_id='$key'";
	else
		$SQL="update Utskottsforslag set status=0 where dok_id='$key'";
	$db->executeSQL($SQL,"UPDATE");
}
header("Location: ".$_SERVER["HTTP_REFERER"]);
?>
