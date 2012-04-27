<?php
$result = $db->executeSQLRows("SELECT count(*) as total, parti FROM Ledamoter WHERE status LIKE 'Tj%' group by parti");
foreach($result as $r) {
	$parti[$r->parti]=$r->total;
}
$HEADER['title'] ="Partier";
$HEADER['type']="article";

include_once("includes/header.php");
?>


<div id="content">
	<div id="main" class="ledamoter-lista">
		<h1>Ledamöter & partier</h1>
		<div class="column-8">
		<div class="column">
			<a href="/parti/socialdemokraterna/">
				<h3 class="logo-parti s">Socialdemokraterna</h3>
				<span class="procent"><?=$parti['S']?>st</span>
			</a>
		</div>
		<div class="column">
			<a href="/parti/moderaterna/">
				<h3 class="logo-parti m">Moderaterna</h3>
				<span class="procent"><?=$parti['M']?>st</span>
			</a>
		</div>
		<div class="column">
			<a href="/parti/miljopartiet/">
				<h3 class="logo-parti mp">Miljöpartiet</h3>
				<span class="procent"><?=$parti['MP']?>st</span>
			</a>
		</div>
		<div class="column">
			<a href="/parti/kristdemokraterna/">
				<h3 class="logo-parti kd">Kristdemokraterna</h3>
				<span class="procent"><?=$parti['KD']?>st</span>
			</a>
		</div>
		<div class="column">
			<a href="/parti/sverigedemokraterna/">
				<h3 class="logo-parti sd">Sverigedemokraterna</h3>
				<span class="procent"><?=$parti['SD']?>st</span>
			</a>
		</div>
		<div class="column">
			<a href="/parti/folkpartiet/">
				<h3 class="logo-parti fp">Folkpartiet</h3>
				<span class="procent"><?=$parti['FP']?>st</span>
			</a>
		</div>
		<div class="column">
			<a href="/parti/centerpartiet/">
				<h3 class="logo-parti c">Centerpartiet</h3>
				<span class="procent"><?=$parti['C']?>st</span>
			</a>
		</div>
		<div class="column">
			<a href="/parti/vansterpartiet/">
				<h3 class="logo-parti v">Vänsterpartiet</h3>
				<span class="procent"><?=$parti['V']?>st</span>
			</a>
		</div>
		<div class="clearer">&nbsp;</div>
	</div>
	<!--	
		<div id="ledamoter">
			
			<ul>

                          <?php
                            print(count($result));
                            foreach ($result as $l) {
                                ?>

                                <li>
                                    <a href="?page=ledamot&id=<?= ("$l->id"); ?>"><?= ("$l->tilltalsnamn"); ?>  <?= ("$l->efternamn"); ?></a>

                                </li>
                            <?php } ?>
			</ul>
		</div>
	</div>
	<div id="sidebar">
		<iframe width="345" height="600" src="http://live.twingly.com/riksdagsrosten?css=http://riksdagsrosten.se/static/css/twingly-live.css" style="border:0;outline:0" frameborder="0" scrolling="no"></iframe>
	</div>
	<div class="clearer">&nbsp;</div>-->
</div>