<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */


ini_set('display_errors','On');
session_start();

  // include the database Class //
  require_once '../classes/Database.class.php';
	$db=new Database();
	$db->connectSQL();

if(isset($_SESSION["user_id"])) {
	$USER = $db->executeSQL("select * from Users where id='".$_SESSION["user_id"]."'","SELECT");
}

?><!DOCTYPE html>
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
	  _kms('//i.kissmetrics.com/i.js');_kms('//doug1izaerwt3.cloudfront.net/0b7fd2808f0f08864bafbeba2fd3b5d03d47aefb.1.js');
	</script>
	<?if(isset($USER)) {?>
		<script type="text/javascript">
		  _kmq.push(['identify', '<?="$USER->tilltalsnamn"?> <?="$USER->efternamn"?> (<?="$USER->facebook_id"?>)']);
		</script>
	<?}?>

<meta property="og:url" content="http://www.riksdagsrosten.se<?=$_SERVER['REQUEST_URI']?>"/>

  <meta property="fb:app_id"      content="196658153744046" /> 
<?php
/* if(isset($HEADER['description'])) {
	$ogDescription = strip_tags($HEADER['description']);
	print("<meta property=\"og:description\" content=\"".$ogDescription."\" />");
	}*/
?>
	<meta property="og:site_name" content="Riksdagsrösten"/>
	<meta property="og:title" content="<?php
		/*
		 * Print the <title> tag based on what is being viewed.
		 */
		global $page, $paged;

		wp_title( '|', true, 'right' );

		// Add the blog name.
		bloginfo( 'name' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

		?>"/>
	<meta property="og:type" content="article">
    <meta property="og:image" content="<?=array_shift(wp_get_attachment_image_src( get_post_thumbnail_id(), 'large'));?>">


<?if($page=="votering" && isset($HEADER["folket_ja"]) && isset($HEADER["folket_nej"])) {?>
	<meta property="riksdagsrosten:yay" content="<?=$HEADER["folket_ja"]?>">
	<meta property="riksdagsrosten:nay" content="<?=$HEADER["folket_nej"]?>">
<?
	}
?>	

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
	<link href="/static/css/main.css?v=3" media="screen" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
	<script type="text/javascript" src="/static/js/jquery.cycle.all.js"></script>
	<script type="text/javascript">
		$(function() {
		    $('#slider-content').cycle({
				fx:         'scrollLeft',
		        timeout:     3000,
		        pager:      '#slider-nav',
		        pagerEvent: 'mouseover',
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
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />

<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="/blogg/wp-content/themes/twentyeleven/style.css?v=2" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/sv_SE/all.js#xfbml=1&appId=196658153744046";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
<div id="page" class="hfeed">
  <div id="wrapper">
  	<div id="header">
  		<div id="logo">
<?if(isset($USER)) {?>
	<a href="/">
<?} else {?>		
	<a href="/landning">
<?}?>
	Riksdagsrösten</a></div>
		<div class="notice">
			<strong>OBS!</strong> Just nu sker utveckling på sajten så lita inte på någon data du ser för tillfället. Ibland utgör exempeldata vissa delar av informationen.
		</div>
  		<div id="nav-1">
  			<ul>
<?if(isset($USER) || isset($_SESSION['OINLOGGAD'])) {?>
  				<li><a href="/votering/">Omröstningar</a></li>
  				<li><a href="/ledamot/">Ledamöter & partier</a></li>
  				<li><a href="/om/">Om</a></li>
  				<li><a href="/blogg/">Blogg</a></li>
				<li><a href="/profil/">Min profil</a></li>
<?} else {?>
	<li><a href="/landning">Omröstningar</a></li>
	<li><a href="/landning">Ledamöter & partier</a></li>
	<li><a href="/landning">Om</a></li>
	<li><a href="/blogg/">Blogg</a></li>
	<li class="facebook"><a href="/login/">Logga in</a></li>
	
<?}?>

			</ul>
  			<div class="clearer">&nbsp;</div>
  		</div>
  		<div class="clearer">&nbsp;</div>
  	</div>
