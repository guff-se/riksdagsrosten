<?php
if(isset($_GET['id']))
	$dokID=$_GET['id'];
        
        
else
	die("hack!");

if(isset($USER)) {
//logged in stuff //
 $din_rost_result = $db->executeSQLRows("SELECT * FROM UserRoster WHERE utskottsforslag_id in (select id from Utskottsforslag where dok_id = '".$dokID."' AND punkt = 1) AND user_id = '$USER->id'");
 $din_rost=$din_rost_result[0];
}

$result = $db->executeSQLRows("SELECT * FROM Utskottsforslag WHERE Utskottsforslag.dok_id = '$dokID' AND status = 0 AND punkt = 1");

$resultKommande = $db->executeSQLRows("SELECT * FROM Utskottsforslag WHERE Utskottsforslag.dok_id = '$dokID' AND status = 5 AND punkt = 1");

$k=$resultKommande[0];
$v=$result[0];

if(!isset($v->status)) {
    $v = $k;
}



if($v->status == 0) {
 $ja_procent=round($v->roster_ja/($v->roster_ja+$v->roster_nej),2)*100;
 $ja_fill_procent=$v->roster_ja/($v->roster_ja+$v->roster_nej)*100;
 $nej_fill_procent=$v->roster_nej/($v->roster_ja+$v->roster_nej)*100;
 $nej_procent=round($v->roster_nej/($v->roster_ja+$v->roster_nej),2)*100;
 
 
 $result2 = $db->executeSQLRows("SELECT Utskottsforslag.*, PartiRoster.* FROM Utskottsforslag, PartiRoster 
        WHERE Utskottsforslag.dok_id = PartiRoster.dok_id AND Utskottsforslag.dok_id = '$dokID' AND PartiRoster.punkt=1");

  foreach($result2 as $pr){
	$temptotal=$pr->roster_ja+$pr->roster_nej;
	$parti[$pr->parti]['ja']=$pr->roster_ja;
	$parti[$pr->parti]['nej']=$pr->roster_nej;
	if($temptotal > 0) { // man vill ju inte dela med noll
		$parti[$pr->parti]['ja_procent']=round($pr->roster_ja/$temptotal,2)*100;
		$parti[$pr->parti]['nej_procent']=round($pr->roster_nej/$temptotal,2)*100;
	}
	$parti[$pr->parti]['avstar']=$pr->roster_avstar;
	$parti[$pr->parti]['franvarande']=$pr->roster_franvarande;
   }
 
 
}

$folket_tot=$v->folket_ja+$v->folket_nej;
if($folket_tot) {
	$folket_ja_procent=round($v->folket_ja/($folket_tot),2)*100;
	$folket_nej_procent=round($v->folket_nej/($folket_tot),2)*100;
}

$folket_total = $v->folket_ja + $v->folket_nej;


$HEADER['title'] ="$v->titel";
$HEADER['description'] ="$v->bik";
$HEADER['type']="riksdagsrosten:bill";
$HEADER["folket_ja"]=$v->folket_ja;
$HEADER["folket_nej"]=$v->folket_nej;

include_once("includes/header.php");
?>
<?if(isset($USER))
	if($USER->type == 1) {?>
<div id="admin">
	<a href="/admin/visa.php?dok_id=<?= $v->dok_id ?>">Redigera votering</a>
</div>
<? } ?>
<div id="content">
	<div id="main" class="votering box-stroke">
		<h1><?php echo $v->titel; ?></h1>
		<p><b>Beslutsdatum:</b> <?php echo $v->beslut_datum; ?> &nbsp;
			<a href="http://data.riksdagen.se/dokument/<?=$dokID?>">Läs förslaget i sin helhet</a>
		</p>
            <p>
              <?= nl2br($v->bik);?>
            </p>
	</div>
	<div id="sidebar">
		<div id="data-box">
		
			<div id="leave-vote" class="clearfix">
                        <?php if(isset($USER)) { ?>                        
                        <?php 
                            $ja_status = '';
                            $nej_status = '';
                           if(isset($din_rost->rost)) {
                             if(strtolower($din_rost->rost) == "nej") { $nej_status = "voted"; $ja_status = "not-voted"; $selected_vote = 'voted-nej';}  
                             if(strtolower($din_rost->rost) == "ja")  { $ja_status = "voted"; $nej_status = "not-voted"; $selected_vote = 'voted-ja'; }
                           }
                        ?>
                        
                        
                        
			<ul id="votebuttons" class="<?=$selected_vote; ?>">
				<li class="<?=$ja_status; ?>"><a <?php print("onclick=\"clicky.goal( '1025', '1' );\""); ?> class="voteYes log_vote button yes" href="/post/rosta.php?vid=<?=$v->id?>&rost=Ja">JA</a></li>
				<li class="<?=$nej_status; ?>"><a <?php print("onclick=\"clicky.goal( '1025', '1' );\""); ?> class="voteNo log_vote button no" href="/post/rosta.php?vid=<?=$v->id?>&rost=Nej">NEJ</a></li>
			</ul>​
                         
                        
                        
                        
                        
			<!--<a <?print("onclick=\"clicky.goal( '1025', '1' );\"");?>
				class="log_vote button yes <?=$nej_active; ?>" href="/post/rosta.php?vid=<?=$v->id?>&rost=Ja">Ja</a>
			<a <?print("onclick=\"clicky.goal( '1025', '1' );\"");?>
				class="log_vote button no  <?=$ja_active; ?> " href="/post/rosta.php?vid=<?=$v->id?>&rost=Nej">Nej</a>-->
			
			<!--<div class="clearer">&nbsp;</div>-->
			<!--<a class="button next" href="#" title="Nästa fråga">&#8227;</a>-->
                        <?php } ?>
		</div>
		
		
			<div class="group">
				<h5>Folkets åsikt just nu</h5>

<? if($folket_tot) {?>
				<div class="votes">
					<div class="yes" style="width:<?=$folket_ja_procent; ?>%;"></div>
					<div class="no" style="width:<? print(100-$folket_ja_procent); ?>%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count"><?=$v->folket_ja; ?>&nbsp;st</div>
					<div class="no-count"><?=$v->folket_nej; ?>&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
<? } else { ?>
Ingen har röstat i denna fråga.	
<? } ?>
				<div class="count">Antal röster: <b><?=$folket_total; ?> st</b></div>
			</div>
                    
<? if ($v->votering_id != "") { ?>
			<div class="group">
				<h5>Ledamöter i riksdagen</h5>
				<div class="votes">
					<div class="yes" style="width:<?=$ja_fill_procent?>%;"></div>
					<div class="no" style="width:<?=$nej_fill_procent?>%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count"><?=$v->roster_ja?>&nbsp;st</div>
					<div class="no-count"><?=$v->roster_nej?>&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="count">Avstod: <b><?=$v->roster_avstar; ?> st</b>, Frånvarande: <b><?=$v->roster_franvarande; ?> st</b></div>
			</div>
<? } ?>
		</div>
	</div>
	<div class="clearer">&nbsp;</div>
        
<? if ($v->votering_id != "") { ?>
	<div class="column-8">
		<div class="column">
			<h3 class="logo-parti s">Socialdemokraterna</h3>
			<div class="votes<?php if($parti["S"]["ja_procent"] + $parti["S"]["nej_procent"] == 0) {echo' none';} ?>">
					<div class="yes" style="width:<?=$parti["S"]["ja_procent"]; ?>%;"></div>
					<div class="no" style="width:<?=$parti["S"]["nej_procent"]; ?>%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count"><?=$parti["S"]["ja"]; ?>&nbsp;st</div>
					<div class="no-count"><?=$parti["S"]["nej"]; ?>&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
			<span>Avstod: <b><?=$parti["S"]["avstar"]; ?> st</b></span>
			<span>Frånvarande: <b><?=$parti["S"]["franvarande"]; ?> st</b></span>
		</div>
		<div class="column">
			<h3 class="logo-parti m">Moderaterna</h3>
			<div class="votes<?php if($parti["M"]["ja_procent"] + $parti["M"]["nej_procent"] == 0) {echo' none';} ?>">
					<div class="yes" style="width:<?=$parti["M"]["ja_procent"]; ?>%;"></div>
					<div class="no" style="width:<?=$parti["M"]["nej_procent"]; ?>%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count"><?=$parti["M"]["ja"]; ?>&nbsp;st</div>
					<div class="no-count"><?=$parti["M"]["nej"]; ?>&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
			<span>Avstod: <b><?=$parti["M"]["avstar"]; ?> st</b></span>
			<span>Frånvarande: <b><?=$parti["M"]["franvarande"]; ?> st</b></span>
		</div>
		<div class="column">
			<h3 class="logo-parti mp">Miljöpartiet</h3>
			<div class="votes<?php if($parti["MP"]["ja_procent"] + $parti["MP"]["nej_procent"] == 0) {echo' none';} ?>">
					<div class="yes" style="width:<?=$parti["MP"]["ja_procent"]; ?>%;"></div>
					<div class="no" style="width:<?=$parti["MP"]["nej_procent"]; ?>%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count"><?=$parti["MP"]["ja"]; ?>&nbsp;st</div>
					<div class="no-count"><?=$parti["MP"]["nej"]; ?>&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
			<span>Avstod: <b><?=$parti["MP"]["avstar"]; ?> st</b></span>
			<span>Frånvarande: <b><?=$parti["MP"]["franvarande"]; ?> st</b></span>
		</div>
		<div class="column">
			<h3 class="logo-parti kd">Kristdemokraterna</h3>
			<div class="votes<?php if($parti["KD"]["ja_procent"] + $parti["KD"]["nej_procent"] == 0) {echo' none';} ?>">
					<div class="yes" style="width:<?=$parti["KD"]["ja_procent"]; ?>%;"></div>
					<div class="no" style="width:<?=$parti["KD"]["nej_procent"]; ?>%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count"><?=$parti["KD"]["ja"]; ?>&nbsp;st</div>
					<div class="no-count"><?=$parti["KD"]["nej"]; ?>&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
			<span>Avstod: <b><?=$parti["KD"]["avstar"]; ?> st</b></span>
			<span>Frånvarande: <b><?=$parti["KD"]["franvarande"]; ?> st</b></span>
		</div>
		<div class="column">
			<h3 class="logo-parti sd">Sverigedemokraterna</h3>
			<div class="votes<?php if($parti["SD"]["ja_procent"] + $parti["SD"]["nej_procent"] == 0) {echo' none';} ?>">
					<div class="yes" style="width:<?=$parti["SD"]["ja_procent"]; ?>%;"></div>
					<div class="no" style="width:<?=$parti["SD"]["nej_procent"]; ?>%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count"><?=$parti["SD"]["ja"]; ?>&nbsp;st</div>
					<div class="no-count"><?=$parti["SD"]["nej"]; ?>&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
			<span>Avstod: <b><?=$parti["SD"]["avstar"]; ?> st</b></span>
			<span>Frånvarande: <b><?=$parti["SD"]["franvarande"]; ?> st</b></span>
		</div>
		<div class="column">
			<h3 class="logo-parti fp">Folkpartiet</h3>
			<div class="votes<?php if($parti["FP"]["ja_procent"] + $parti["FP"]["nej_procent"] == 0) {echo' none';} ?>">
					<div class="yes" style="width:<?=$parti["FP"]["ja_procent"]; ?>%;"></div>
					<div class="no" style="width:<?=$parti["FP"]["nej_procent"]; ?>%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count"><?=$parti["FP"]["ja"]; ?>&nbsp;st</div>
					<div class="no-count"><?=$parti["FP"]["nej"]; ?>&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
			<span>Avstod: <b><?=$parti["FP"]["avstar"]; ?> st</b></span>
			<span>Frånvarande: <b><?=$parti["FP"]["franvarande"]; ?> st</b></span>
		</div>
		<div class="column">
			<h3 class="logo-parti c">Centerpartiet</h3>
			<div class="votes<?php if($parti["C"]["ja_procent"] + $parti["C"]["nej_procent"] == 0) {echo' none';} ?>">
					<div class="yes" style="width:<?=$parti["C"]["ja_procent"]; ?>%;"></div>
					<div class="no" style="width:<?=$parti["C"]["nej_procent"]; ?>%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count"><?=$parti["C"]["ja"]; ?>&nbsp;st</div>
					<div class="no-count"><?=$parti["C"]["nej"]; ?>&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
			<span>Avstod: <b><?=$parti["C"]["avstar"]; ?> st</b></span>
			<span>Frånvarande: <b><?=$parti["C"]["franvarande"]; ?> st</b></span>
		</div>
		<!--<div class="column">
			<h3 class="logo-parti c">Centerpartiet</h3>
			<div class="votes">
					<div class="yes" style="width:94%;"></div>
					<div class="no" style="width:6%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count">15&nbsp;st</div>
					<div class="no-count">1&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
			<span>Avstod: <b>0 st</b></span>
			<span>Frånvarande: <b>7 st</b></span>
		</div>-->
		<div class="column">
			<h3 class="logo-parti v">Vänsterpartiet</h3>
			<div class="votes<?php if($parti["V"]["ja_procent"] + $parti["V"]["nej_procent"] == 0) {echo' none';} ?>">
					<div class="yes" style="width:<?=$parti["V"]["ja_procent"]; ?>%;"></div>
					<div class="no" style="width:<?=$parti["V"]["nej_procent"]; ?>%;"></div>
					<div class="clearer">&nbsp;</div>
					<div class="yes-count"><?=$parti["V"]["ja"]; ?>&nbsp;st</div>
					<div class="no-count"><?=$parti["V"]["nej"]; ?>&nbsp;st</div>
					<div class="clearer">&nbsp;</div>
				</div>
			<span>Avstod: <b><?=$parti["V"]["avstar"]; ?> st</b></span>
			<span>Frånvarande: <b><?=$parti["V"]["franvarande"]; ?> st</b></span>
		</div>
		<div class="clearer">&nbsp;</div>
	</div>
        <? } ?>
<div>
    
<?if ($v->votering_id != "") { ?>
<?if(isset($_GET["more"])) {?>
	<a class="show-more-button" href="/votering/<?=$v->dok_id?>">Göm samtliga röster</a>
<div id="all" class="box-frame grid-3">
<?
$rost_typer=array("Ja","Nej","Avstår","Frånvarande");
foreach($rost_typer as $rt) {
	$result=$db->executeSQLRows("select * from  Roster, Ledamoter where Ledamoter.intressent_id=Roster.intressent_id
								AND Roster.votering_id='$v->votering_id' AND rost='$rt' order by parti, efternamn");
?>
	<h3><?=$rt?> (<? print(count($result)); ?>)</h3>
	<ul>
	<?
if(isset($result))
	foreach($result as $l) {?>
		<li class="<?php
		if($l->rost == 'Frånvarande') {
			echo 'franvarande';
		} else {
		echo strtolower($l->rost); } ?>">
			<a href="/ledamot/<?=$l->id;?>_<?php echo slug($l->tilltalsnamn); ?>_<?php echo slug($l->efternamn); ?>/">
					<img src="http://data.riksdagen.se/filarkiv/bilder/ledamot/0<?=$l->intressent_id?>_80.jpg" width="46" height="61" />
					<span class="name logo-parti <?=strtolower($l->parti)?>"><?=$l->tilltalsnamn?> <?=$l->efternamn?></span>
					<span class="vote"><?=$l->rost?></span>
					<div class="clearer">&nbsp;</div>
			</a>
		</li>
	<?}?>
	</ul>
	<div class="clearer">&nbsp;</div>
<?
} // slut på röst_typer
?>
        
</div>
<?} else {?>
	<div><a class="show-more-button" href="/votering/<?=$v->dok_id?>/1#all">Visa samtliga röster</a></div>
<?}?>
        
<?}?>

         <div id="disqus_thread"></div>
<script type="text/javascript">
    var disqus_shortname = 'riksdagsrosten';

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
            
</div>