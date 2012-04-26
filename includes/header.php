<?
include("header-scripts.php");
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
	
  <div id="wrapper">
  	<div id="header">
  		<div id="logo"><a href="/">Riksdagsrösten</a></div>
  		<div class="notice"><strong>OBS!</strong> Just nu sker utveckling på sajten, vi lanserar skarpt på <strong>fredag kl. 15:00</strong>. Lita inte på några siffror du ser nu, men testa gärna och kom med feedback. <strong>Följ oss Live:</strong> <a href="http://www.riksdagsrosten.se/live">http://www.riksdagsrosten.se/live</a></div>
  		<div id="nav-1">
  			<ul>
  				<li><a href="/votering/">Omröstningar</a></li>
  				<li><a href="/ledamot/">Ledamöter & partier</a></li>
  				<li><a href="/om/">Om</a></li>
  				<li><a href="/blogg/">Blogg</a></li>
                                
                                <?php if(isset($_SESSION['user_id'])) { ?>
                                    
                                   <li><a href="/profil/">Min sida</a></li>

                                <?php }else{ ?>
                                   <li class="facebook"><a href="/login/">Logga in</a></li>

                                <?php } ?>
  			</ul>
  			<div class="clearer">&nbsp;</div>
  		</div>
  		<div class="clearer">&nbsp;</div>
  	</div>