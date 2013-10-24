<?php 
error_reporting(E_ALL);
ini_set('display_errors','On');

  // start a session //
  session_start(); 
  
  // include the database Class //
  require_once 'classes/Database.class.php';

	$db=new Database();
	$db->connectSQL();
	
	
include("header.php");

?>
<table class="table table-bordered">

<?
/*
$users=$db->executeSQLRows("SELECT registreringsdatum, id from Users");
foreach($users as $u) {
	if($u->registreringsdatum != "0000-00-00 00:00:00") {
		//print($u->registreringsdatum."<br>");
		$sql=("INSERT into LogCohortRegister set week=".date("oW",strtotime($u->registreringsdatum)).", user_id='$u->id'");
		print($sql."<br>");
		$db->executeSQL($sql,"INSERT");
	}
}
/**/
/*
$users=$db->executeSQLRows("SELECT timestamp, user_id from UserRoster");
foreach($users as $u) {
	//print($u->timestamp."<br>");
	$sql=("INSERT into LogCohortVote set week=".date("oW",strtotime($u->timestamp)).", user_id='$u->user_id'");
	print($sql."<br>");
	$db->executeSQL($sql,"INSERT");
}
/**/

$weeks = $db->executeSQLRows("SELECT DISTINCT week from LogCohortLogin");

print("<thead><tr><td colspan=2>Registration</td><td colspan=".count($weeks).">Logged in<br><em>Voted</em></td></tr>
	<tr><td>Week</td><td>Total</td>");
for($i = 1;$i<=count($weeks);$i++) {
	print("<th>$i</th>");
}
print("</tr></thead><tbody>");

$skuffa=0;

foreach($weeks as $rWeek) {
	$reg=$db->executeSQL("SELECT count(LogCohortRegister.week) as count from LogCohortRegister WHERE LogCohortRegister.week='$rWeek->week'","SELECT");
	print("<tr><th class=\"span1\">$rWeek->week</th><th class=\"span1\">$reg->count</th>");
	foreach($weeks as $lWeek) {
		$login=$db->executeSQL("SELECT count(LogCohortLogin.week) as count from LogCohortRegister, LogCohortLogin WHERE
				LogCohortLogin.week='$lWeek->week' AND LogCohortRegister.week='$rWeek->week' AND LogCohortRegister.user_id = LogCohortLogin.user_id","SELECT");
		$vote=$db->executeSQL("SELECT count(LogCohortVote.week) as count from LogCohortRegister, LogCohortVote WHERE
				LogCohortVote.week='$lWeek->week' AND LogCohortRegister.week='$rWeek->week' AND LogCohortRegister.user_id = LogCohortVote.user_id","SELECT");

		print(mysql_error());
		if($reg->count > 0) {
			$lPercent = 100* round($login->count / $reg->count,2);
			$vPercent = 100* round($vote->count / $reg->count,2);
		}
		if($lPercent > 0 || $vPercent > 0) {
			print("<td>");
			print($lPercent."%<br><em>".$vPercent."%</em>");
			print("</td>");
		}
	}
	if($skuffa)
		print("<td colspan=$skuffa></td>");
	$skuffa++;
	print("</tr>");
}

?>
</tbody></table>


    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	<script src="admin.js" type="text/javascript"></script>
  </body>
</html>




