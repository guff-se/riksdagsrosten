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
$roster = array_slice($roster,0,5);

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
				<div style="float:left;width:58%;">
					<h3>Senaste rösterna</h3>
					<div class="singular-vote-list">
						<ul>
<?foreach($roster as $r) {?>
							<li>
								<a href="/votering/<?=$r->dok_id?>/">
									<span class="title"><?php echo substr($r->titel,0,69);
														if(strlen($r->titel)>69)
															echo "...";
														?></span>
									<?php
									if($r->rost == 'Ja') {
										$answerClass = 'yes';
									}
									else if($r->rost == 'Nej') {
										$answerClass = 'no';
									}
									else if($r->rost == 'Frånvarande') {
										$answerClass = 'franvarande';
									}
									
									?>
									<span class="answer<?php if(isset($answerClass)) { echo ' '; echo $answerClass; $answerClass = false; } ?>"><?=$r->rost?></span>
									<div class="clearer">&nbsp;</div>
								</a>
							</li>
<?}?>				
						</ul>
					</div>
			</div>
				</div>
				<div style="float:right;width:40%;">
					<h3 style="background-image:url('http://static.twingly.com/content/images/twingly-logo-mini.png');background-position: right center;background-repeat:no-repeat;">Bloggat om <?=$l->tilltalsnamn?> <?=$l->efternamn?></h3>
					<div class="box-frame">
						<div class="twingly_widget">
	  						<a href="http://www.twingly.com">Twingly Blog Search</a>
	  						<span class="query">"<?=$l->tilltalsnamn?> <?=$l->efternamn?>", <?=$PARTI[$l->parti]?></span>
	  						<span class="title">Bloggat om <?=$l->tilltalsnamn?> <?=$l->efternamn?></span>
						</div>
					</div>
				</div>
				<div class="clearer">&nbsp;</div>
<?if($l->twitter) {?>
				<div class="box-stroke" style="margin-top:15px;color:#666;padding-top:10px;">
					<img style="position:relative;top:5px;margin-right:7px;" src="/static/images/twitter-icon.png" width="30"/><a href="http://twitter.com/<?=$l->twitter?>" target="_blank" style="color: #444;font-weight:bold;text-decoration:none;"><?=$l->tilltalsnamn?> <?=$l->efternamn?></a><b style="color:#444;font-weight:bold;">:</b> <span id="last-tweet" style="line-height:25px;"></span>
				</div>
<script type="text/javascript">
$(document).ready(function(){
var username='<?=$l->twitter?>';
var format='json';
var url='http://api.twitter.com/1/statuses/user_timeline/'+username+'.'+format+'?callback=?';
	$.getJSON(url,function(tweet){
		$("#last-tweet").html(tweet[0].text);
	});
});
</script>
<?}?>
			</div>
</div>