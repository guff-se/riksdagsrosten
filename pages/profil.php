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

$HEADER['title']="Min profil";
$HEADER['type']="";

include_once("includes/header.php");
?>


<div id="content">
	<div id="main" class="profil">
		<h1>Min profil</h1>
		<div id="profile-overview" class="box-frame">
			<div class="inner">
			<img src="https://graph.facebook.com/<?=$USER->facebook_id; ?>/picture" width="50" height="50" class="left" /><h3 class="left"><?=$USER->tilltalsnamn; ?> <?=$USER->efternamn; ?></h3> <span>(<b><?=$USER->roster; ?></b> röster)</span> <span class="logout"><a href="/profil/redigera" class="btn btn-large btn-inverse">Redigera</a></span>
				<div class="clearer">&nbsp;</div>
            </div>        
		</div>
		<br />
		<div>
			<div style="float:left;">
		<h3>Ledamöter som röstar som du</h3>
		<div class="box-frame toplist ranked" style="width:400px;">
<?if(isset($ledamoter)) {?>
			<ul>
<?
$i=1;
foreach($ledamoter as $l) {?>
				<li>
					<span class="rank"><?=$i?>.</span>
					<a href="/ledamot/<?=$l->id;?>_<?php echo slug($l->tilltalsnamn); ?>_<?php echo slug($l->efternamn); ?>/">
						<img src="http://data.riksdagen.se/filarkiv/bilder/ledamot/0<?=$l->intressent_id?>_80.jpg" width="46" />
						<span class="name logo-parti <?php echo strtolower($l->parti); ?>"><?=$l->tilltalsnamn?> <?=$l->efternamn?></span>
						<span class="result"><?=$l->roster_lika?>/<?=$l->roster_olika?><br>
							<?=round($l->procent,2)*100?>%</span>
						<div class="clearer">&nbsp;</div>
					</a>
				</li>
<?
$i++;
}
?>
			</ul>
<? } else { ?>
	Du måste rösta för att bli matchad med ledamöter.
<?}?>
		</div>
		</div>
		<div style="float:right;width:510px;">
			<h3>Dina senaste röster</h3>
		<div class="singular-vote-list">
				<ul>
                                    <?php if(isset($roster)) { ?>
                                     <?php foreach($roster as $rost) { ?>
                                    <li>
                                        <a href="/votering/<?=$rost->dok_id; ?>/">
                                        <span class="title"><?php echo substr($rost->titel,0,59);?></span>
                                        <?php
											if($rost->rost == 'Ja') {
											    $answerClass = 'yes';
											}
											else if($rost->rost == 'Nej') {
											    $answerClass = 'no';
											}
											
										?>
                                        <span class="answer <?php echo $answerClass; ?>"><?=$rost->rost; ?></span>
                                        </a>
                                    </li>    
                                     <? } ?>
                                    <? }else{ echo "Inga röster lagda än"; } ?>
                                
				</ul>
			</div>
		</div>
		<div class="clearer">&nbsp;</div>
		</div>
		<br/>
		<h3>Hur har du röstat i förhållande till majoriteten i partierna?</h3>
		<div class="column-8">
		<div class="column">
			<h3 class="logo-parti s">Socialdemokraterna</h3>
			<span class="procent"><?=$partier["S"]["procent"]?>%</span>
		</div>
		<div class="column">
			<h3 class="logo-parti m">Moderaterna</h3>
			<span class="procent"><?=$partier["M"]["procent"]?>%</span>
		</div>
		<div class="column">
			<h3 class="logo-parti mp">Miljöpartiet</h3>
			<span class="procent"><?=$partier["MP"]["procent"]?>%</span>
		</div>
		<div class="column">
			<h3 class="logo-parti kd">Kristdemokraterna</h3>
			<span class="procent"><?=$partier["KD"]["procent"]?>%</span>
		</div>
		<div class="column">
			<h3 class="logo-parti sd">Sverigedemokraterna</h3>
			<span class="procent"><?=$partier["SD"]["procent"]?>%</span>
		</div>
		<div class="column">
			<h3 class="logo-parti fp">Folkpartiet</h3>
			<span class="procent"><?=$partier["FP"]["procent"]?>%</span>
		</div>
		<div class="column">
			<h3 class="logo-parti c">Centerpartiet</h3>
			<span class="procent"><?=$partier["C"]["procent"]?>%</span>
		</div>
		<div class="column">
			<h3 class="logo-parti v">Vänsterpartiet</h3>
			<span class="procent"><?=$partier["V"]["procent"]?>%</span>
		</div>
		<div class="clearer">&nbsp;</div>
	</div>
	</div>
</div>
