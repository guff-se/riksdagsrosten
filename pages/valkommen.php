<?
$HEADER['title'] = "Välkommen";
$HEADER['type']="article";

include_once("includes/header.php");
?>
<br/>
<div id="welcome">
<!--<div class="alert alert-success"><strong>Yay!</strong> Användarkonto skapat med lyckat resultat.</div>-->
<!--<h1>Välkommen!</h1>
<p>Vad roligt att du vill vara med och revolutionera svensk politik!</p>-->
<h3>Vill du ha en öppen eller stängd profil?</h3>
<ul class="clearfix">
<li class="twoway first-child">
<ul>
		<li class="fb-no"><strong>Facebook</strong> <span>Inget kommer att publiceras på Facebook utan att du tillfrågas först.</span>
		</li>
		<li class="friends-no"><strong>Vänner</strong> <span>Din profil är stängd och ingen kan se hur du har röstat.</span></li>
	</ul>
<a class="btn btn-large" href="/">Stängd profil</a>
</li>
<li class="twoway">
	<ul>
		<li class="fb"><strong>Facebook</strong> <span>Din aktivitet kommer att synas på din Facebook-profil.</span>
		</li>
		<li class="friends"><strong>Vänner</strong> <span>Möjlighet att skapa widgets och låta dina vänner ta del av dina röster.</span></li>
	</ul>
<a class="btn btn-large btn-primary"  href="/post/edit.php?publik=1">Öppen profil</a>
</li>
</ul>
<div class="clearer">&nbsp;</div>
</div>