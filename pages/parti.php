<?php
$partiet=$PARTISYMBOL[strtolower($_GET['id'])];
if(!isset($partiet))
	die("<script>Location.replace('/');</script>");
$result = $db->executeSQL("select franvaro, roster_tot, roster_piska, piska from Parti where symbol='$partiet'","SELECT");
$narvaro = 100-$result->franvaro;
$piska = round($result->piska,2)*100;
$roster_tot = $result->roster_tot;
$roster_ejpiska = $result->roster_tot-$result->roster_piska;

$ledamoter = $db->executeSQLRows("select * from Ledamoter where parti='$partiet' order by efternamn");
$antal = $db->executeSQL("select count(*) as total from Ledamoter where parti='$partiet'", "SELECT");
if(isset($USER)) {
	$match = $db->executeSQL("select * from PartiMatch where user_id = '$USER->id' && parti = '$partiet'","SELECT");
	if(isset($match->procent))
		$matchprocent=round($match->procent,2)*100;
}

$HEADER['title']=$PARTI[$partiet];
$HEADER['type']="government";
$HEADER['image']="http://www.riksdagsrosten.se/static/images/".slug($PARTI[$partiet]).".png";

include_once("includes/header.php");
?>
<div id="content">
	<div id="main" class="parti">
			<div id="parti-name"><h1 class="single-row"><span class="logo-parti <?=strtolower($partiet)?>"><?=$PARTI[$partiet]?></span></h1></div>
			<div class="fb-like" data-href="http://www.riksdagsrosten.se<?=$_SERVER['REQUEST_URI']?>" data-send="true" data-width="520" 
				data-show-faces="false"></div>
			<div class="clearer">&nbsp;</div>

			<div class="column-3">
				<div class="column">
					<h3>Antal ledamöter</h3>
					<span class="procent"><?php echo $antal->total; ?></span>
					<span class="description">Av alla 349 ledamöter i Sveriges riksdag tillhör <?php echo $antal->total; ?> av dem <?=$PARTI[$partiet]?>.</span>
				</div>
				<div class="column<?php if(!isset($USER)) { echo ' inactive'; } ?>">
					<h3>Likhet</h3>
					<?php if(isset($USER) && isset($matchprocent)) { ?>
						<span class="procent"><?=$matchprocent?>%</span>
						<span class="description">Vid <?=$matchprocent?>% av omröstningarna har du röstat som majoriteten av <?=$PARTI[$partiet]?>s ledamöter.</span>
					<?php }else{ ?>
						<span class="procent">?</span>
						<span class="description">Du måste <a href="/login/">logga in</a> och börja rösta för att se din likhet med <?=$PARTI[$partiet]?>.</span>
					<?php } ?>
				</div>
				<div class="column">
					<h3>Partilojalitet</h3>
					<span class="procent"><?=$piska?>%</span>
					<span class="description"><?=$roster_ejpiska?> av <?=$PARTI[$partiet]?>s <?=$roster_tot?> röster har frångått partilinjen sedan valet 2010.</span>
				</div>
				<div class="column">
					<h3>Närvaro</h3>
					<span class="procent"><?=$narvaro?>%</span>
					<span class="description">Genomsnittlig närvaro för <?=$PARTI[$partiet]?>s ledamöter sedan förra valet.</span>
				</div>
				<div class="clearer">&nbsp;</div>
			</div>
			<br/>
			<h3>Alla <?php echo $antal->total; ?> ledamöter som representerar <?=$PARTI[$partiet]?> i Sveriges riksdag</h3>
			<div class="box-frame grid-3 ledamotlista">
			<ul>
<?
foreach($ledamoter as $l) {
?>
				<li>
					<a href="/ledamot/<?=$l->id;?>_<?php echo slug($l->tilltalsnamn); ?>_<?php echo slug($l->efternamn); ?>/" class="<?=strtolower($partiet)?>">
						<img src="http://data.riksdagen.se/filarkiv/bilder/ledamot/0<?=$l->intressent_id?>_80.jpg" width="46" height="60" />
						<span class="name"><?=$l->tilltalsnamn?> <?=$l->efternamn?></span>
						<div class="clearer">&nbsp;</div>
					</a>
				</li>
<?}?>

				<div class="clearer">&nbsp;</div>
			</ul>
		</div>

</div>