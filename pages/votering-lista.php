<?php
if(!isset($id))
	if(isset($_POST["id"]))
		$id=$_POST["id"];
	else
		$id="tidigare";
if($id=="populara")
	$sort = "ORDER BY Utskottsforslag.folket_ja+Utskottsforslag.folket_nej DESC LIMIT 50";
else if($id=="kommande")
	$sort = "AND Utskottsforslag.status = '5'
    ORDER BY Utskottsforslag.beslut_datum LIMIT 50";
else if($page=="kategori" && isset($_GET["id"])) {
	$kid = mysql_real_escape_string($_GET["id"]);
	$sort = "AND Utskottsforslag.organ='$kid' ORDER BY Utskottsforslag.beslut_datum DESC LIMIT 50";
	$result = $db->executeSQL("select Beskrivning from Organ where organ='$kid'", "SELECT");
	$kategori_namn= $result->Beskrivning;
}
else if($id=="tidigare") {
	$sort = "AND Utskottsforslag.status = '0'
    ORDER BY Utskottsforslag.beslut_datum DESC LIMIT 50";
}
else
	die("vilka voteringar?");

/*if(isset($USER)) {
	$result = $db->executeSQLRows("SELECT Organ.*, Utskottsforslag.*, UserRoster.* FROM Utskottsforslag, Organ, UserRoster
        WHERE Utskottsforslag.punkt = 1
        AND Utskottsforslag.visible = 1
        AND Utskottsforslag.organ = Organ.organ
		AND Utskottsforslag.id = UserRoster.utskottsforslag_id
		AND UserRoster.user_id = $USER->id
        $sort");
} else {
*/
	$result = $db->executeSQLRows("SELECT Organ.*, Utskottsforslag.*  FROM Utskottsforslag, Organ
        WHERE Utskottsforslag.punkt = 1
        AND Utskottsforslag.visible = 1
        AND Utskottsforslag.organ = Organ.organ
        $sort");
//}


$kategorier = $db->executeSQLRows("SELECT * FROM Organ WHERE aktiv=1 ORDER BY Beskrivning");
/*
if(isset($USER) && $result) {
//logged in stuff //
$dokids=$result[0]->id;
for($i=1; $i<sizeof($result);$i++) {
	$dokids.=",".$result[$i]->id;
}
 $din_rost_result = $db->executeSQLRows("SELECT * FROM UserRoster WHERE utskottsforslag_id in ($dokids) and user_id=$USER->id");
var_dump($din_rost_result);
}
*/

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
			<div id="filter">
			<ul>
				<?if($id=="populara") {?>
					<li class="active"><a href="#">Populära</a></li>
				<?} else {?>
					<li><a href="/votering/populara">Populära</a></li>
				<?}
				if($id=="tidigare") {?>
					<li class="active"><a href="#">Tidigare</a></li>
				<?} else {?>
					<li><a href="/votering/tidigare">Tidigare</a></li>
				<?}
				if($id=="kommande") {?>
					<li class="active"><a href="#">Kommande</a></li>
				<?} else {?>
					<li><a href="/votering/kommande">Kommande</a></li>
				<? } ?>
			</ul>
			<div class="clearer">&nbsp;</div>
			</div>
			<div id="filter-sub" class="box-frame">
			<form action="/kategori" method="get">
				<input type="hidden" name="tab" value="<?$id?>">
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
    
    if($v->roster_ja != 0 && $v->roster_nej != 0) {
	$ja_procent=round($v->roster_ja/($v->roster_ja+$v->roster_nej),2)*100;
	$nej_procent=round($v->roster_nej/($v->roster_ja+$v->roster_nej),2)*100;
     }
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
                                                            <div class="meta">Voteringsdag: <b><?=str_replace(" 00:00:00","",$v->beslut_datum); ?></b><br/>Kategori: <b><?=$v->beskrivning; ?></b></div>
						</div>
						<div class="right">
							<div class="didvote">Röstat?</div>
							<div>Folket</div>
							<div class="votes<?php if($folket_ja_procent + $folket_nej_procent == 0) {echo' none';} ?>">
								<div class="yes" style="width:<?=$folket_ja_procent; ?>%;"></div>
								<div class="no" style="width:<? print(100-$folket_ja_procent); ?>%;"></div>
								<div class="clearer">&nbsp;</div>
								<div class="yes-count"><?=$v->folket_ja; ?>&nbsp;st</div>
								<div class="no-count"><?=$v->folket_nej; ?>&nbsp;st</div>
								<div class="clearer">&nbsp;</div>
							</div>
							<div>Riksdagen</div>
							<div class="votes">
								<div class="yes" style="width:<?=$ja_procent?>%;"></div>
								<div class="no" style="width:<? print(100-$ja_procent)?>%;"></div>
								<div class="clearer">&nbsp;</div>
								<div class="yes-count"><?=$v->roster_ja?>&nbsp;st</div>
								<div class="no-count"><?=$v->roster_nej?>&nbsp;st</div>
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
			<!--<div class="pagination_front">
				<ul>
				    <li>«</li>
				    <li class="active">
				      <a href="#">1</a>
				    </li>
				    <li><a href="#">2</a></li>
				    <li><a href="#">3</a></li>
				    <li><a href="#">4</a></li>
				    <li><a href="#">»</a></li>
				  </ul>
				</div>-->
		</div>
	</div>
	<div id="sidebar">
		<!--<iframe width="345" height="600" src="http://live.twingly.com/riksdagsrosten?css=http://riksdagsrosten.se/static/css/twingly-live.css" style="border:0;outline:0" frameborder="0" scrolling="no"></iframe>
		<div>
			<a class="FlattrButton" style="display:none;" href="http://riksdagsrosten.se/"></a>
			<noscript><a href="http://flattr.com/thing/427951/Riksdagsrosten" target="_blank">
			<img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></noscript>
		</div>-->
		<br />
		<br />
		<div class="box-stroke">	
				<h4 style="background-image: url(/static/images/landning-se.png);">F&ouml;lj Sveriges riksdag</h3>
				<p style="border-bottom: 2px solid #EAE4D9;padding-bottom:15px;">
					Vi lyfter fram de senaste omr&ouml;stningarna fr&aring;n Riksdagen och g&ouml;r det enkelt f&ouml;r dig att h&auml;nga med.
				</p>
				<h4 style="background-image: url(/static/images/landning-hitta.png);">Hitta din representant</h3>
				<p style="border-bottom: 2px solid #EAE4D9;padding-bottom:15px;">
					Genom dina r&ouml;star kan vi matcha ihop dig med de Riksdagsledam&ouml;ter som r&ouml;star som du.
				</p>
				<h4 style="background-image: url(/static/images/landning-tyck.png);">Tyck till / Påverka</h3>
				<p style="margin-bottom:5px;">
					Vi sammanst&auml;ller dina &aring;sikter och kontaktar din Riksdagsledamot f&ouml;r att p&aring;verka beslut.
				</p>
		</div>
		<br/>
		<div class="box-stroke" style="padding:10px;">	
				<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Friksdagsrosten&amp;width=292&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;border_color=%23FFF&amp;stream=false&amp;header=false&amp;appId=124823437606237" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:258px;" allowTransparency="true"></iframe>
		</div>
	</div>
	<div class="clearer">&nbsp;</div>
</div>