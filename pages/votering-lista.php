<?php
if($page=="pop")
	$sort = "ORDER BY Voteringar.folket_ja+Voteringar.folket_nej DESC LIMIT 50";
else if($page=="kategori" && isset($_GET["id"])) {
	$kid = $_GET["id"];
	$sort = "AND Utskottsforslag.organ='$kid' ORDER BY Utskottsforslag.publicerad DESC LIMIT 50";
	$result = $db->executeSQL("select Beskrivning from Organ where organ='$kid'", "SELECT");
	$kategori_namn= $result->Beskrivning;
}
else
	$sort = "ORDER BY Utskottsforslag.publicerad DESC LIMIT 50";

$result = $db->executeSQLRows("SELECT Utskottsforslag.*, Voteringar.*, Organ.* FROM Utskottsforslag, Voteringar, Organ
        WHERE Utskottsforslag.dok_id = Voteringar.dok_id
        AND Utskottsforslag.status = 1
        AND Voteringar.punkt = 1
        AND Utskottsforslag.organ = Organ.organ
        $sort");

$kategorier = $db->executeSQLRows("SELECT * FROM Organ ORDER BY Beskrivning");


$HEADER['title']="Omröstningar";
$HEADER['type']="article";

include_once("includes/header.php");
?>


<?php
$HEADER['title'] ="Kategorier";
$HEADER['type']="article";

include_once("includes/header.php");
?>


<div id="content">
	<div id="main">
		<div id="list">
			<h3>Omröstningar
			<?if(isset($kategori_namn)) {?>
			på förslag från <?=$kategori_namn?>
			<?
				}
			?>
			</h3>
			<div class="box-frame">
			<form action="/kategori" method="get">
				<select style="width:100%;font-size:16px;" name="id" onchange='location.replace("/kategori/"+this.value)'>
				<option value="">Alla kategorier</option>
				<?foreach ($kategorier as $r) {
					if(isset($kid) && $r->organ==$kid)
						$selected="selected='$kid'";
					else
						$selected="";
				?>
					<option value="<?=$r->organ?>" <?=$selected?>><?=$r->beskrivning?></option>
				<?
				}
				?>
				</select>
			</form>
			</div>
			<br/>
			<ul>

<?php
if(isset($result)) {
foreach($result as $v) {
	$ja_procent=round($v->roster_ja/($v->roster_ja+$v->roster_nej),2)*100;
	$nej_procent=round($v->roster_nej/($v->roster_ja+$v->roster_nej),2)*100;
        
        if($v->folket_nej == "") {
           $v->folket_nej = 0; 
        }
    if($v->folket_ja || $v->folket_nej){
    $folket_ja_procent=round($v->folket_ja/($v->folket_ja+$v->folket_nej),2)*100;
	$folket_nej_procent=round($v->folket_nej/($v->folket_ja+$v->folket_nej),2)*100;
    }
	?>
				<li>
					<a href="/votering/<?=$v->dok_id?>/">
						<h6><?=$v->titel?></h6>
						<div class="left">
							<p class="description"><? echo strip_tags(substr($v->bik,0,250));?>&hellip;</p>
							<div class="meta">Voteringsdag: <b><?=$v->publicerad ?></b><br/>Kategori: <b><?=$v->beskrivning; ?></b></div>
						</div>
						<div class="right">
							<div>Folket</div>
							<div class="votes">
								<div class="yes" style="width:<?=$folket_ja_procent; ?>%;"><?=$v->folket_ja; ?>st</div>
								<div class="no" style="width:<? print(100-$folket_ja_procent); ?>%;"><?=$v->folket_nej; ?>st</div>
								<div class="clearer">&nbsp;</div>
							</div>
							<div>Riksdagen</div>
							<div class="votes">
								<div class="yes" style="width:<?=$ja_procent?>%;"><?=$v->roster_ja?>st</div>
								<div class="no" style="width:<? print(100-$ja_procent)?>%;"><?=$v->roster_nej?>st</div>
								<div class="clearer">&nbsp;</div>
							</div>
						</div>
						<div class="clearer">&nbsp;</div>
					</a>
				</li>
<?php } }?>			  


			</ul>
			<?php if(!isset($kategori_namn)) { ?>
			<a class="show-more-button" href="/votering">Visa fler omröstningar</a>
			<?php } ?>
		</div>
	</div>
	<div id="sidebar">
		<iframe width="345" height="600" src="http://live.twingly.com/riksdagsrosten?css=http://riksdagsrosten.se/static/css/twingly-live.css" style="border:0;outline:0" frameborder="0" scrolling="no"></iframe>
		<div>
			<a class="FlattrButton" style="display:none;" href="http://riksdagsrosten.se/"></a>
			<noscript><a href="http://flattr.com/thing/427951/Riksdagsrosten" target="_blank">
			<img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></noscript>
		</div>
	</div>
	<div class="clearer">&nbsp;</div>
</div>