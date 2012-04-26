<?php
    $result = $db->executeSQLRows("SELECT Utskottsforslag.*, Organ.* FROM Utskottsforslag, Organ 
        WHERE Utskottsforslag.status = 0
        AND Utskottsforslag.punkt = 1
        AND Organ.organ = Utskottsforslag.organ
        AND Utskottsforslag.votering_id != ''
        ORDER BY Utskottsforslag.publicerad DESC LIMIT 5");

    
        $kommandeOmrostningar = $db->executeSQLRows("SELECT Utskottsforslag.*, Organ.* FROM Utskottsforslag, Organ 
        WHERE Utskottsforslag.status = 5
        AND Utskottsforslag.punkt = 1
        AND Organ.organ = Utskottsforslag.organ
        GROUP BY dok_id
        ORDER BY Utskottsforslag.beslut_datum ASC LIMIT 5");
            
	$kategorier = $db->executeSQLRows("SELECT * FROM Organ ORDER BY Beskrivning");

$HEADER['type']="website";

include_once("includes/header.php");
?>


<div id="content">
	<div id="main" class="start">		
		<div id="list" style="width: 960px;">
			<div id="slider">
			<ul id="slider-content" style="-webkit-border-radius: 15px;
-webkit-border-bottom-left-radius: 0;
-moz-border-radius: 15px;
-moz-border-radius-bottomleft: 0;
border-radius: 15px;
border-bottom-left-radius: 0;">

<?foreach(array_slice($result,0,5) as $v){
        
        if($v->roster_ja != 0 && $v->roster_nej != 0) {
	$ja_procent=round($v->roster_ja/($v->roster_ja+$v->roster_nej),2)*100;
	$nej_procent=round($v->roster_nej/($v->roster_ja+$v->roster_nej),2)*100;
        
         if($v->folket_nej == "") {
           $v->folket_nej = 0; 
         }
        }
        if($v->folket_ja != 0 && $v->folket_nej != 0) {
         $folket_ja_procent=round($v->folket_ja/($v->folket_ja+$v->folket_nej),2)*100;
	 $folket_nej_procent=round($v->folket_nej/($v->folket_ja+$v->folket_nej),2)*100;
        }
	
	
	?>
				<li>
					<a href="/votering/<?=$v->dok_id?>/">
						<h6><?=$v->titel?></h6>
						<div class="left">
							<p class="description"><? echo substr($v->bik,0,350);?>&hellip;</p>
							<div class="meta">Voteringsdag: <b><?=str_replace(" 00:00:00","",$v->beslut_datum); ?></b><br/>Kategori: <b><?=$v->beskrivning ?></b></div>
						</div>
						<div class="right">
							<div>Folket</div>
							<div class="votes">
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
<?}?>
			</ul>
			<div>
				<div id="slider-nav-container">
					<div id="slider-nav">
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div id="slider-more"><a href="#">Visa alla omröstningar &rarr;</a></div>
				<div class="clearer">&nbsp;</div>
			</div>
			</div>
			<div class="clearer">&nbsp;</div>
				<div style="float:left;width:65%;">
					<h3>Senaste omröstningarna</h3>
			<div class="singular-vote-list">
						<ul style="padding:0px;">
				
                                                    <?php foreach ($result as $v) { ?>
                                                        <li>
                                                            <a href="/votering/<?= $v->dok_id ?>/">
                                                                <span class="title"><?= $v->titel ?></span>
                                                                
                                                                <div class="clearer">&nbsp;</div>
                                                            </a>
                                                        </li>
                                                    <?php } ?>

						</ul>
						<a class="show-more-button" href="/votering">Visa fler omröstningar</a>
					</div>
				</div>
				<div style="float:right;width:33%;">
					<h3>Kommande omröstningar</h3>
			<div class="singular-vote-list">
						<ul style="padding:0px;">
                                                    <?php foreach ($kommandeOmrostningar as $k) { ?>
                                                        <li>
                                                            <a href="/votering/<?= $k->dok_id ?>/">
                                                                <span class="title"><?= $k->titel ?></span>
                                                                <div class="clearer">&nbsp;</div>
                                                                <span class="title">Voteringsdag: <?= str_replace(" 00:00:00","",$k->beslut_datum); ?></span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>


						</ul>
					</div>
				<div class="clearer">&nbsp;</div>
			</div>
			
			<!--<a class="show-more-button" href="/votering">Visa fler omröstningar</a>-->
		</div>
	</div>
	<div id="sidebar">
		<!--<h3>Donera till Riksdagsrösten</h3>
		<i>Vi behöver din hjälp för att utvecklas.</i>
		<div style="margin-top:15px;margin-left:25px">
		<iframe style="overflow:hidden;" src="http://www.fundedbyme.com/projects/2011/10/riksdagsrosten/widget/" width="240" height="600" frameborder="0" align="middle">Your browser does not support iframe</iframe>
		<div style="float:right;margin-top:0px;margin-right:35px">
			<a class="FlattrButton" style="display:none;" href="http://riksdagsrosten.se/"></a>
			<noscript><a href="http://flattr.com/thing/427951/Riksdagsrosten" target="_blank">
			<img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></noscript>
		</div>
		</div>-->

		<!--<iframe width="345" height="600" src="http://live.twingly.com/riksdagsrosten?css=http://riksdagsrosten.se/static/css/twingly-live.css" style="border:0;outline:0" frameborder="0" scrolling="no"></iframe>-->
	</div>
	<div style="width: 320px;float: left;">
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
		<div class="box-stroke">
			<?php include_once("includes/blog-feed.php"); ?>
		</div>
		<br/>
		<div class="box-stroke" style="padding:10px;">	
				<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Friksdagsrosten&amp;width=292&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;border_color=%23FFF&amp;stream=false&amp;header=false&amp;appId=124823437606237" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:258px;" allowTransparency="true"></iframe>
		</div>
		</div>
	
	<div class="clearer">&nbsp;</div>
	<!--<br/>
	<div class="column-3 parti">
				<div class="column">
					<h3></h3>
					<span class="procent"></span>
					<span class="description"></span>
				</div>
				<div class="column">
					<h3></h3>
					<span class="procent"></span>
					<span class="description"></span>
				</div>
				<div class="column">
					<h3></h3>
					<span class="procent"></span>
					<span class="description"></span>
				</div>
				<div class="column">
					<h3></h3>
					<span class="procent"></span>
					<span class="description"></span>
				</div>
				<div class="clearer">&nbsp;</div>
			</div>-->
</div>