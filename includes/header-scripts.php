<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# riksdagsrosten: http://ogp.me/ns/fb/riksdagsrosten#">
	<script type="text/javascript">
	  var _kmq = _kmq || [];
	  function _kms(u){
	    setTimeout(function(){
	      var s = document.createElement('script'); var f = document.getElementsByTagName('script')[0]; s.type = 'text/javascript'; s.async = true;
	      s.src = u; f.parentNode.insertBefore(s, f);
	    }, 1);
	  }
	  _kms('//i.kissmetrics.com/i.js');_kms('//doug1izaerwt3.cloudfront.net/fbc518803b472ead5b21ddade8336d7e12f394a3.1.js');
	<?if(isset($USER)) {?>
		_kmq.push(['identify', '<?="$USER->tilltalsnamn"?> <?="$USER->efternamn"?> (<?="$USER->facebook_id"?>)']);
	<?}?>
	</script>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>Riksdagsrösten<?
    if(isset($HEADER['title']))
		print(" | ".$HEADER['title']);
	else
		$HEADER['title'] = "Riksdagsrösten";
    ?></title>

<meta property="og:url" content="http://www.riksdagsrosten.se<?=$_SERVER['REQUEST_URI']?>"/>

  <meta property="fb:app_id"      content="196658153744046" /> 
<?php if(isset($HEADER['description'])) {
	$ogDescription = strip_tags($HEADER['description']);
	print("<meta property=\"og:description\" content=\"".$ogDescription."\" />");
	}
?>
	<meta property="og:site_name" content="Riksdagsrösten"/>
	<meta property="og:title" content="<?=$HEADER['title']?>"/>
	<?if(isset($HEADER['type']))
		print("<meta property=\"og:type\" content=\"" . $HEADER['type'] . "\"/>");
	else
		print("<meta property=\"og:type\" content=\"website\"/>");
	?>

	<?if(isset($HEADER['image']))
		print("<meta property=\"og:image\" content=\"" . $HEADER['image'] . "\"/>");
	else
		print("<meta property=\"og:image\" content=\"http://www.riksdagsrosten.se/icon_200.png\"/>");
	?>
	
<?if($page=="votering" && isset($HEADER["folket_ja"]) && isset($HEADER["folket_nej"])) {?>
	<meta property="riksdagsrosten:yay" content="<?=$HEADER["folket_ja"]?>">
	<meta property="riksdagsrosten:nay" content="<?=$HEADER["folket_nej"]?>">
<?
	}
?>	
	
	<link rel="pingback" href="http://www.riksdagsrosten.se<?=$_SERVER['REQUEST_URI']?>" />
	
	<?
	if(!isset($USER->admin)) {
		if(isset($USER)) {?>
	<script type="text/javascript">
	  var clicky_custom = {};
	  clicky_custom.session = {
	    userid: '<?=$USER->id?>',
	    username: '<?=$USER->tilltalsnamn?> <?=$USER->efternamn?>',
	    email: '<?=$USER->email?>'
	  };
	</script>
	<?
	}
	?>
	  <script src="//static.getclicky.com/js" type="text/javascript"></script>
	        <script type="text/javascript">try{ clicky.init(66497714); }catch(e){}</script>
	        <noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/66497714ns.gif" /></p></noscript>
	<?
	}
	?>
	
    <script type="text/javascript" src="http://static.twingly.com/jsapi/1.1.1/twingly.js"></script>
	<script type="text/javascript">
	    var _kundo = _kundo || {};
	    _kundo["org"] = "riksdagsrosten";
	    _kundo["lang"] = "sv";
	    _kundo["btn-type"] = "1";
	
	    (function() {
	        function async_load(){
	            var s = document.createElement('script');
	            s.type = 'text/javascript';
	            s.async = true;
	            s.src = ('https:' == document.location.protocol ? 'https://static-ssl' : 'http://static') +
	            '.kundo.se/embed.js';
	            var x = document.getElementsByTagName('script')[0];
	            x.parentNode.insertBefore(s, x);
	        }
	        if (window.attachEvent)
	            window.attachEvent('onload', async_load);
	        else
	            window.addEventListener('load', async_load, false);
	    })();
	</script>
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" rel="stylesheet" type="text/css" />
	<link href="/static/css/reset.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="/static/css/main.css?v=2" media="screen" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
	<script type="text/javascript" src="/static/js/jquery.cycle.all.js"></script>
	<script type="text/javascript">
		$(function() {
		    $('#slider-content').cycle({
				fx:         'scrollLeft',
		        timeout:     3000,
		        pager:      '#slider-nav',
		        fastOnEvent: true
		    });
		});
	</script>
	<script type="text/javascript">
	/* <![CDATA[ */
	    (function() {
	        var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
	        s.type = 'text/javascript';
	        s.async = true;
	        s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
	        t.parentNode.insertBefore(s, t);
	    })();
	/* ]]> */
	</script>
  </head>