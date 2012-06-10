<?php

if(!isset($USER)) {
    header("Location: /");
    die(" Prova logga in först");
    
    
}

$result = $db->executeSQL("SELECT count(*) as roster FROM UserRoster WHERE user_id = '$USER->id'", "SELECT");
$USER->roster=$result->roster;

$roster = $db->executeSQLRows("SELECT Utskottsforslag.dok_id, Utskottsforslag.titel, Utskottsforslag.rubrik, UserRoster.* FROM Utskottsforslag, UserRoster WHERE Utskottsforslag.id = UserRoster.utskottsforslag_id AND UserRoster.user_id = '$USER->id' ORDER BY UserRoster.timestamp DESC");

$HEADER['title']="Mina röster";
$HEADER['type']="";

include_once("includes/header.php");
?>


<div id="content">
	<div id="main" class="profil">
		<h1>Min profil</h1>
		<div id="profile-overview" class="box-frame">
			<div class="inner">
			<img src="https://graph.facebook.com/<?=$USER->facebook_id; ?>/picture" width="50" height="50" class="left" /><h3 class="left" style="font-weight:600;"><?=$USER->tilltalsnamn; ?> <?=$USER->efternamn; ?></h3> <span>(<b><?=$USER->roster; ?></b> röster)</span> <span class="logout"><a href="/profil/redigera" class="btn btn-large">Inställningar</a> <a href="/logout" class="btn btn-large btn-inverse">Logga ut</a></span>
				<div class="clearer">&nbsp;</div>
            </div>        
		</div>
		<br />

		<div style="width:810px;">
			<h3>Dina  röster</h3>
		<div class="singular-vote-list">
				<table class="table">
					<tr><th>Titel</th><th>Tid</th><th>Ditt val</th>
                                    <?php if(isset($roster)) { ?>
                                     <?php foreach($roster as $rost) { ?>
                                    <tr><td>
                                        <a href="/votering/<?=$rost->dok_id; ?>/">
                                        <span class="title"><?php echo substr($rost->titel,0,109);?></span>
									</td><td>
										<time><?=$rost->timestamp?></time>
									</td><td>
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
                                    </td></tr> 
                                     <? } ?>
                                    <? }else{ echo "Inga röster lagda än"; } ?>
                                
				</table>
			</div>
		</div>
		<div class="clearer">&nbsp;</div>
	</div>
</div>
