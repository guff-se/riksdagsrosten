<?php
$result = $db->executeSQLRows("SELECT Utskottsforslag.*, Voteringar.*, Organ.* FROM Utskottsforslag, Voteringar, Organ 
        WHERE Utskottsforslag.dok_id = Voteringar.dok_id 
        AND Utskottsforslag.status = 1
        AND Voteringar.punkt = 1
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
				<img class="screenshot" src="http://www.riksdagsrosten.se/static/images/example-1.png" />
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
		<div id="mentions">
			<div class="w960">
				<!--<h4>Vad säger folk om Riksdagrösten?</h4>-->
				<div class="social"><iframe src="http://www.facebook.com/plugins/like.php?app_id=196658153744046&amp;href=http://www.facebook.com/riksdagsrosten&amp;send=false&amp;layout=button_count&amp;width=1&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=lucida+grande&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe><a href="https://twitter.com/riksdagsrosten" class="twitter-follow-button" data-show-count="false" data-lang="en" data-show-screen-name="false">Follow @riksdagsrosten</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
				<div class="clearer">&nbsp;</div>
				<ul id="tweets">
					<!--<li> 
						<img src="https://twimg0-a.akamaihd.net/profile_images/1646714838/Leonard_Forsberg_reasonably_small.jpg" />
						<p><a class="author" href="https://twitter.com/leonardforsberg/status/131308000974614528">Leonard Forsberg:</a> <a href="#">@riksdagsrosten</a> det känns som jag sitter i Riksdagen. Toppen!</p>
						<div class="clearer">&nbsp;</div>
					</li>-->
				</ul>
				<div class="clearer">&nbsp;</div>
				<div id="site-info">
					<div class="left">Copyright &copy; 2011 <strong>Riksdagsrösten</strong> &mdash; Alla rättigheter förbehålls.</div>
					<div class="right">
						<ul>
							<li><a href="/om">Om webbplatsen</a></li>
							<!--<li><a href="#">Användarvillkor</a></li>-->
						</ul>
					 </div>
					<div class="clearer">&nbsp;</div>
				</div>
			</div>
		</div>
	</div>
	<!--<div id="wrapper">
		<img src="/static/images/logo-3.png" align="center">
		<div id="content">
			<div id="main" class="box-frame">
				<img src="/static/images/landning-se.png" align=left>	
				<h3>F&ouml;lj Riksdagen</h3>
				<p>
					Vi lyfter fram de senaste omr&ouml;stningarna fr&aring;n Riksdagen och g&ouml;r det enkelt f&ouml;r dig att h&auml;nga med.
				</p>
				<img src="/static/images/landning-hitta.png" align=left>
				<h3>Hitta din representant</h3>
				<p>
					Genom dina r&ouml;star kan vi matcha ihop dig med de Riksdagsledam&ouml;ter som r&ouml;star som du.
				</p>
				<img src="/static/images/landning-tyck.png" align=left>
				<h3>Tyck till</h3>
				<p>
					Vi sammanst&auml;ller dina &aring;sikter och kontaktar din Riksdagsledamot f&ouml;r att p&aring;verka beslut.
				</p>
			</div>
			<div id="login-box">
				<p>
					För att kunna dra nytta av alla funktioner på Riksdagsrösten behöver du logga in via Facebook
				</p>
				<a href="/login"><img src="/static/images/facebook-big.png"></a><br/>
				<a href="/start">...fortsätt oinloggad</a>
			</div>
			<div class="clearer">&nbsp;</div>
		</div>
		<div>
			DE SENASTE OMRÖSTNINGARNA
		</div>
	</div>-->
</body>