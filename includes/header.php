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
<?/*
  <div class="alert alert-info alert-sand clearfix"><strong>Hjälp oss att ge sajten liv!</strong> Tycker du om Riksdagsrösten? Kan du tänka dig att avvara en tjuga, eller kanske en tusenlapp? <a class="btn btn-success pull-right" id="stod-btn" href="http://fundedbyme.com/projects/2012/04/riksdagsrosten/" target="_blank">Stöd Riksdagsrösten →</a></div>
*/?>
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