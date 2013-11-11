<?
require_once 'classes/Database.class.php';

$db=new Database();
$db->connectSQL();

if($_GET["type"]=="kommande") {
	$result = $db->executeSQLRows("SELECT * FROM Utskottsforslag 
	    WHERE Utskottsforslag.status = '5'
	    AND Utskottsforslag.punkt = 1
	    AND Utskottsforslag.visible = 1
	    ORDER BY Utskottsforslag.beslut_datum LIMIT 5");
	$title = "Kommande omröstningar";
	foreach($result as $r) {
		if($r->roster_ja > $r->roster_nej)
			$r->rost = "ja";
		else
			$r->rost = "nej";	
	}

} else if($_GET["type"]=="senaste") {
	$result = $db->executeSQLRows("SELECT * FROM Utskottsforslag 
	    WHERE Utskottsforslag.status = '0'
	    AND Utskottsforslag.punkt = 1
	    AND Utskottsforslag.visible = 1
	    ORDER BY Utskottsforslag.beslut_datum DESC LIMIT 5");
	$title = "Senaste omröstningar";
	foreach($result as $r) {
		if($r->roster_ja > $r->roster_nej)
			$r->rost = "ja";
		else
			$r->rost = "nej";	
	}
} else if($_GET["type"]=="ledamot") {
	$result = $db->executeSQLRows("SELECT * FROM Ledamoter WHERE id = ".mysql_real_escape_string($_GET['id']));
	$l = $result[0];
	$result = $db->executeSQLRows("	SELECT * FROM Utskottsforslag 
		    WHERE Utskottsforslag.status = '0'
		    AND Utskottsforslag.punkt = 1
		    AND Utskottsforslag.visible = 1
		    ORDER BY Utskottsforslag.beslut_datum DESC LIMIT 5");
	$title = "$l->tilltalsnamn $l->efternamn"."s omröstningar";

	$roster = array();
	$antal_roster=0;
	foreach($result as $r) {
		$rost = $db->executeSQL("SELECT Roster.rost FROM Roster
			 						WHERE Roster.votering_id = '$r->votering_id'
									AND Roster.intressent_id='$l->intressent_id'","SELECT");
		if(isset($rost->rost)) {
			$r->rost=$rost->rost;
			$antal_roster++;
			array_push($roster,$r);
		}
	}
	$roster = array_slice($roster,0,5);
} else if($_GET["type"]=="personlig") {
	$user_id=mysql_real_escape_string($_GET['id']);
	$user = $db->executeSQL("SELECT publik, tilltalsnamn, efternamn FROM Users WHERE id = $user_id", "SELECT");
	if($user->publik != 1)
		die("inte publik profil");
		$title = "$user->tilltalsnamn $user->efternamn"."s röster";
	$result = $db->executeSQLRows("SELECT Utskottsforslag.dok_id, Utskottsforslag.titel, Utskottsforslag.rubrik, UserRoster.* FROM Utskottsforslag, UserRoster WHERE Utskottsforslag.id = UserRoster.utskottsforslag_id AND UserRoster.user_id = '$user_id' ORDER BY UserRoster.id DESC LIMIT 5");	
}
else
	die("no type");



?>

<style type="text/css">
/* WIDGET */
.riksdagsrosten-widget {
	font-family: Arial;
	line-height: 1.3em;
	background-color: #EAE4D9;
	padding: 7px 8px 5px 7px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	font-size: 12px;
}
.riksdagsrosten-widget-container {
	background-color: #fff;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	-moz-box-shadow: 1px 1px 3px #888;
	-webkit-box-shadow: 1px 1px 3px#888;
	box-shadow: 1px 1px 3px #888;
	padding: 5px;
}
.riksdagsrosten-widget-logo a {
	background-image: url(http://www.riksdagsrosten.se/static/images/riksdagsrosten-widget.png);
	text-indent: -9999px;
	width: 135px;
	height: 35px;
	display: block;
	margin: 6px auto;
}
.riksdagsrosten-widget-subtitle {
	font-size: 100%;
	font-weight: bold;
	line-height: 1.2em;
	margin-bottom: 2px;
	margin-top: 4px;
	cursor: default;
}
.riksdagsrosten-widget a { 
	text-decoration: none;
	color: #888;
}
.riksdagsrosten-widget-answer {
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	padding: 3px 5px;
	font-size: 12px;
}
.riksdagsrosten-widget ul {
	list-style: none;
	padding:0px;
}
.riksdagsrosten-widget li {
	margin-top: 4px;
	border-top: 1px solid #e1e1e1;
	padding-top: 6px;
}
.riksdagsrosten-widget a .riksdagsrosten-widget-answer-nej {
	text-shadow: rgba(0, 0, 0, 0.5) -1px 0, rgba(0, 0, 0, 0.3) 0 -1px, rgba(255, 255, 255, 0.5) 0 1px;
	color: #480000;
	background: -moz-linear-gradient(-90deg,#FF5C5C,#B93C3C);
	background: -webkit-gradient(linear, left top, left bottom, from(#FF5C5C), to(#B93C3C));
}
.riksdagsrosten-widget a .riksdagsrosten-widget-answer-ja {
	text-shadow: rgba(0, 0, 0, 0.5) -1px 0, rgba(0, 0, 0, 0.3) 0 -1px, rgba(255, 255, 255, 0.5) 0 1px;
	color: #030;
	background: -moz-linear-gradient(-90deg,#61C861,#2E872E);
	background: -webkit-gradient(linear, left top, left bottom, from(#61C861), to(#2E872E));
}
.riksdagsrosten-widget a .riksdagsrosten-widget-title {
	line-height: 20px;
	color: #111;
}
.riksdagsrosten-widget a:hover .riksdagsrosten-widget-title {
	text-decoration: underline;
}
.riksdagsrosten-widget .riksdagsrosten-widget-date {
	color: #888;
	line-height: 20px;
	cursor: help;
}
.riksdagsrosten-widget-footer {
	text-align: center;
	color: #666;
	margin-top: 3px;
	cursor: default;
}
.riksdagsrosten-widget-footer a {
	color: #666;
	text-decoration: underline;
}
</style>


<div style="width:250px;margin:15px;">
<div class="riksdagsrosten-widget">
<div class="riksdagsrosten-widget-container">
	<div class="riksdagsrosten-widget-header">
		<span class="riksdagsrosten-widget-logo"><a href="http://www.riksdagsrosten.se" target="_blank">Riksdagsrösten</a></span> 
	</div>
	<h6 class="riksdagsrosten-widget-subtitle"><?=$title?></h6>
	<ul>
<? foreach($result as $r) {
	?>
		<li><a href="#"><span class="riksdagsrosten-widget-answer riksdagsrosten-widget-answer-<?=$r->rost?>"><?=$r->rost?></span> <span class="riksdagsrosten-widget-title"><?=$r->titel?></span></a> <?/*- <abbr class="riksdagsrosten-widget-date" title="den 13 december 2011 kl. 01:15" data-utime="1323735317">2 dagar sedan</abbr></li>*/?>
<? } ?>
	</ul>
</div>
		<div class="riksdagsrosten-widget-footer"><a href="http://www.riksdagsrosten.se" target="_blank">widget</a> från <a href="http://www.riksdagsrosten.se" target="_blank">Riksdagsrösten</div>
</div>
</div>