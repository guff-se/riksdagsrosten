<?php

if(preg_match('/_/',$_GET['id'])) {
    $param = explode("_",$_GET['id']);
    $_GET['id'] = $param[0];    
}

$result = $db->executeSQLRows("SELECT * FROM Ledamoter WHERE id = ".mysql_real_escape_string($_GET['id']));
$l = $result[0];

$result = $db->executeSQLRows("SELECT Utskottsforslag.*, Organ.* FROM Utskottsforslag, Organ 
        WHERE Utskottsforslag.status = 0
        AND Utskottsforslag.punkt = 1
		AND Utskottsforslag.visible = 1
        AND Organ.organ = Utskottsforslag.organ
        ORDER BY Utskottsforslag.beslut_datum DESC");


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

if(isset($USER)) {
$match = $db->executeSQL("SELECT * from LedamotMatch
								where intressent_id='$l->intressent_id' AND
								user_id='$USER->id'","SELECT");
if(is_object($match))
	$match->procent=round($match->procent,2)*100;
}

$HEADER['title']="$l->tilltalsnamn $l->efternamn";
$HEADER['type']="politician";
$HEADER['image']="http://data.riksdagen.se/filarkiv/bilder/ledamot/0$l->intressent_id"."_192.jpg";

include_once("includes/header.php");

?>
<div id="content">
	<div id="main" class="profile">
			<div id="profile-name"><h1 class="single-row"><?=$l->tilltalsnamn?> <?=$l->efternamn?> <span class="logo-parti <?=strtolower($l->parti)?>">(<?=$l->parti?>)</span></h1></div>
			<div class="fb-like" data-href="http://www.riksdagsrosten.se<?=$_SERVER['REQUEST_URI']?>" data-send="true" data-width="520" 
				data-show-faces="false"></div>
			<div class="clearer">&nbsp;</div>

			<div class="column-3">
				<div class="column">
					<img class="column-image" src="http://data.riksdagen.se/filarkiv/bilder/ledamot/0<?=$l->intressent_id?>_192.jpg" width="207" height="276" />
					<!--<img class="column-image" src="/static/images/ledamoter/280/0<?=$l->intressent_id?>_280.jpg" width="207" height="276" />-->
				</div>
				<div class="column<?php if(!isset($USER)) { echo ' inactive'; } ?>">
					<h3>Likhet</h3>
					<?php if(isset($USER)) { ?>
						<? if(is_object($match)) {?>
							<span class="procent"><?=$match->procent;?>%</span>
							<span class="description">Baserat på de <?php print($match->roster_lika + $match->roster_olika);?> omröstningar ni båda röstat i har ni svarat likadant på <?=$match->procent;?>% av dem.<!--<?php // echo $match->points; ?>% av de X omröstningarna har du och <?=$l->tilltalsnamn?> <?=$l->efternamn?> röstat likadant.--></span>
						<?php } else{ ?>
							<span class="procent">?</span>
							<span class="description">Du har inte röstat i någon omröstning som <?=$l->tilltalsnamn?> <?=$l->efternamn?> har röstat i.</span>
					<?php } 
					} else{ ?>
						<span class="procent">?</span>
						<span class="description">Du måste <a href="/login/">logga in</a> för att se hur pass lika du och <?=$l->tilltalsnamn?> <?=$l->efternamn?> har röstat.</span>
					<?php } ?>
					
				</div>
				<div class="column">
					<h3>Partilojalitet</h3>
					<span class="procent"><?=$l->piska?>%</span>
					<span class="description"><?=$l->tilltalsnamn?> har frångått partilinjen vid <?=$l->roster_tot-$l->roster_piska?> av <?=$l->roster_tot?> omröstningar sedan förra valet.
					</span>
				</div>
				<div class="column">
					<h3>Närvaro</h3>
					<span class="procent"><?php print(100-$l->franvarande);?>%</span>
					<span class="description"><?php echo "$l->tilltalsnamn $l->efternamn"; ?> har varit frånvarande vid <?php print($l->franvarande_antal); ?> av <?php print($l->aktiv_roster_tot); ?> omröstningar sedan förra valet.</span>
				</div>
				<div class="clearer">&nbsp;</div>
			</div>
			<br/>
			<div>
				<div style="float:left;">
					<h3>Samtliga röster:</h3>

					<div class="all-vote-list">
							<table class="table">
								<tr><th>Titel</th><th>Datum</th><th>Röst</th>
			                                    <?php if(isset($roster)) { ?>
			                                     <?php foreach($roster as $rost) { ?>
			                                    <tr><td>
			                                        <a href="/votering/<?=$rost->dok_id; ?>/">
			                                        <span class="title"><?php echo substr($rost->titel,0,109);?></span>
												</td><td>
													<time><?=substr($rost->beslut_datum,0,10);?></time>
												</td><td>
			                                        <?php
														if($rost->rost == 'Ja') {
														    $answerClass = 'yes';
														}
														else if($rost->rost == 'Nej') {
														    $answerClass = 'no';
														}
														else if($rost->rost == 'Frånvarande') {
															$answerClass = 'franvarande';
														}

													?>
			                                        <span class="answer <?php echo $answerClass; ?>"><?=$rost->rost; ?></span>
			                                        </a>
			                                    </td></tr> 
			                                     <? } ?>
			                                    <? }else{ echo "Inga röster lagda än"; } ?>

							</table>
						</div>

			</div>
				</div>
				<div class="clearer">&nbsp;</div>
			</div>
</div>