<?php
$result = $db->executeSQLRows("SELECT Utskottsforslag.*, Organ.* FROM Utskottsforslag, Organ 
        WHERE Utskottsforslag.status = 0
        AND Utskottsforslag.punkt = 1
        AND Organ.organ = Utskottsforslag.organ
        ORDER BY Utskottsforslag.publicerad DESC LIMIT 1");

	$kategorier = $db->executeSQLRows("SELECT * FROM Organ ORDER BY Beskrivning");

$HEADER['type']="website";

include_once("includes/header-scripts.php");
?>

<body>
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/sv_SE/all.js#xfbml=1&appId=196658153744046";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	<div id="first">
		<div class="w960">
			<h1 class="logo">Riksdagsrösten</h1>
			<div id="shortcuts">
				<ul>
					<li><a href="/votering/">Omröstningar</a></li>
					<li><a href="/ledamot/">Ledamöter & partier</a></li>
					<li><a href="/om/">Om</a></li>
					<li><a href="/blogg">Blogg</a></li>
					<li><a href="/login">Logga in</a></li>
				</ul>
			</div>
			<div class="clearer">&nbsp;</div>
		</div>
		<div id="features">
			<div class="w960">
				<div id="signup">
					<a href="/login">Logga in med Facebook</a>
					<iframe src="http://www.facebook.com/plugins/facepile.php?app_id=196658153744046" scrolling="no" frameborder="0" style="border:none;overflow:hidden; width:280px; height:80px;" allowTransparency="true"></iframe>
					Riksdagsrösten publicerar ingen data på Facebook utan att fråga dig först.
				</div>
				<img class="screenshot" src="/static/images/example-ledamot.png" />
				<div id="skip-signup">
					<p>För att kunna dra nytta av alla funktioner på Riksdagsrösten behöver du logga in via Facebook. <a href="/start">Fortsätt utan att logga in &rarr;</a></p>
				</div>
				<div class="clearer">&nbsp;</div>
				<!--<div class="item">
					<img src="http://data.riksdagen.se/filarkiv/bilder/ledamot/0895706637124_192.jpg" />
				</div>
				<div class="item">
					2
				</div>
				<div class="item">
					3
				</div>
				<div class="clearer">&nbsp;</div>-->
			</div>
		</div>
<!-- FOOTER -->
