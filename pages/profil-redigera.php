<?php

if(!isset($USER)) {
    header("Location: /");
    die(" Prova logga in först");
    
    
}

$result = $db->executeSQL("SELECT count(*) as roster FROM UserRoster WHERE user_id = '$USER->id'", "SELECT");
$USER->roster=$result->roster;

$roster = $db->executeSQLRows("SELECT Utskottsforslag.dok_id, Utskottsforslag.titel, Utskottsforslag.rubrik, UserRoster.* FROM Utskottsforslag, UserRoster WHERE Utskottsforslag.id = UserRoster.utskottsforslag_id AND UserRoster.user_id = '$USER->id' ORDER BY UserRoster.id DESC LIMIT 8");


$ledamoter = $db->executeSQLRows("select * from LedamotMatch, Ledamoter where LedamotMatch.intressent_id = Ledamoter.intressent_id and LedamotMatch.user_id = '$USER->id' order by points desc limit 5");

$result2 = $db->executeSQLRows("select * from PartiMatch where user_id = '$USER->id'");
foreach($PARTI as $pit => $name) {
	$partier[$pit]["procent"]="?";
}
if(isset($result2))
	foreach($result2 as $pit) {
		$partier[$pit->parti]["procent"]=round($pit->procent,2)*100;
	}

$HEADER['title']="Redigera profil";
$HEADER['type']="";

include_once("includes/header.php");
?>


<div id="content">
	<div id="main" class="profil">
		<h1>Redigera profil</h1>
		<!--<div id="profile-overview" class="box-frame">
			<div class="inner">
			<img src="https://graph.facebook.com/<?=$USER->facebook_id; ?>/picture" width="50" height="50" class="left" /><h3 class="left"><?=$USER->tilltalsnamn; ?> <?=$USER->efternamn; ?></h3> <span>(<b><?=$USER->roster; ?></b> röster)</span><span class="logout"><a href="/logout/" class="btn btn-inverse">Logga ut</a></span>
				<div class="clearer">&nbsp;</div>
            </div>        
		</div>
		<br />-->
		<form action="/post/edit.php" method="post">

			<div class="box-frame">
				<div class="inner">
					Ange det parti du röstade på i senaste riksdagsvalet (2010):
					<select name="parti_2010">
						<option name="">---</option>
					<?
					foreach($PARTI as $p => $p_namn) {
						if($p==$USER->parti2010)
							$selected="selected";
						else
							$selected="";
					?>
						<option value="<?=$p?>" <?=$selected?>><?=$p_namn?></option>
					<? }?>
						<option name="O">Övriga</option>
					</select>
					(läs vår <a href="/anvandarvillkor">integritetspolicy</a>)
					<br/>
	            </div>
	            <br />
	            <div class="inner">
					Typ av Profil: 
					<select name="publik">
						<option value="1" <?if($USER->publik) print("selected")?>>Öppen profil</option>
						<option value="0" <?if(!$USER->publik) print("selected")?>>Stängd profil</option>
					</select>
					<br/>
	            </div>
	            <br/>
			</div>
			<br />
			<div style="text-align:center;">
            	<a href="/profil" class="btn btn-large">Avbryt</a>
            	<input type="submit" class="btn btn-large btn-success" value="Spara">
			</div>
		</form>
	</div>
</div>
