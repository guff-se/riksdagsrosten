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
  <div class="alert alert-info alert-sand clearfix" style="padding:20px;">	<b>Riksdagsrösten ligger i dvala.</b><br>
	Det tekniska teamet bakom Riksdagsrösten har under en tid tappat styrfart och energi. Vi älskar idén, men har alla haft för många åtaganden och andra projekt, för att kunna prioritera detta. Just nu sker ingen utveckling och vi kan därför inte heller garantera att sajten är korrekt och buggfri.
<br>
	Vill du hjälpa till att dra igång igen? Hör av dig till <a href="mailto:guff@guff.se">guff@guff.se</a>
<br>
	/ Gustaf Josefsson
</div>

  	<div id="header">
  		<div id="logo"><a href="/">Riksdagsrösten</a></div>
  		<?php if(isset($_SESSION['user_id'])) { ?>
                              
                                   <div id="promotion" class="clearfix"><!--<a class="funded" href="http://fundedbyme.com/projects/2012/04/riksdagsrosten/" target="_blank"><img src="/static/images/finansiera.png" target="_blank" /></a>--><a style="opacity:0;visibility:hidden;width:0px;height:0px;" href="/login">Logga in med Facebook</a></div>
                                <?php }else{ ?>
                                   <div id="promotion" class="clearfix"><!--<a class="funded" href="http://fundedbyme.com/projects/2012/04/riksdagsrosten/" target="_blank"><img src="/static/images/finansiera.png" target="_blank" /></a>--><a class="fb" href="/login">Logga in med Facebook</a></div>
                                <?php } ?>
  		<div id="nav-1">
  			<ul>
  				<li><a href="/votering/">Omröstningar</a></li>
  				<li><a href="/ledamot/">Ledamöter & partier</a></li>
  				<li><a href="/blogg/">Blogg</a></li>
                                
                                <?php if(isset($_SESSION['user_id'])) { ?>
                                    
                                   <li><a href="/profil/">Min profil</a></li>

                                <?php }else{ ?>
                                   <li class="facebook"><a href="/login/">Logga in</a></li>

                                <?php } ?>
  			</ul>
  			<div class="clearer">&nbsp;</div>
  		</div>
  		<div class="clearer">&nbsp;</div>
  	</div>