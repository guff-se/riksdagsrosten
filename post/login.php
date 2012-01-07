<?php 

   $app_id = "196658153744046";
   $app_secret = "0349d42b3e5992fe738ee45c95248c7f";
   $my_url = "http://".$_SERVER["HTTP_HOST"]."/post/login.php";

   if(preg_match('/login.php/',$_SERVER['REQUEST_URI'])) {
       // include the database Class //
        require_once '../classes/Database.class.php';
        session_start();
   }else{
        // include the database Class //
        require_once 'classes/Database.class.php';
   }

    $db = new Database();
    $db->connectSQL();

    
  if(isset($_REQUEST["code"])) {
        $code = $_REQUEST["code"];
  }

	// ---------- outgoing (from site to facebook) ------------

   if(empty($code)) {
     $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
     $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state'] . "&scope=email,offline_access";

     header("Location: $dialog_url");
   }

	// ---------- incoming (return from facebook) ---------------

	if($_REQUEST['state'] == $_SESSION['state']) {
		$token_url = "https://graph.facebook.com/oauth/access_token?"
       		. "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       		. "&client_secret=" . $app_secret . "&code=" . $code;

		$response = file_get_contents($token_url);
		$params = null;
     
		parse_str($response, $params);

		$_SESSION['access_token']=$params['access_token'];
     
		$graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
		$user_profile = json_decode(file_get_contents($graph_url));
     
     	if (isset($user_profile->id)) {
        	$resultLogin = $db->executeSQL("SELECT * FROM Users WHERE facebook_id = " . $user_profile->id, "SELECT");
			if (isset($resultLogin->id)) {
            	$db->executeSQL("UPDATE Users SET senasteinloggning = now(), oauth='" . $params['access_token'] . "', email = '" . 
					$user_profile->email . "' WHERE id = " . $resultLogin->id, "UPDATE");
            	$_SESSION['user_id'] = $resultLogin->id;
?>
<html>
<head>
	<script type="text/javascript">
	  var _kmq = _kmq || [];
	  function _kms(u){
	    setTimeout(function(){
	      var s = document.createElement('script'); var f = document.getElementsByTagName('script')[0]; s.type = 'text/javascript'; s.async = true;
	      s.src = u; f.parentNode.insertBefore(s, f);
	    }, 1);
	  }
		_kms('//i.kissmetrics.com/i.js');_kms('//doug1izaerwt3.cloudfront.net/fbc518803b472ead5b21ddade8336d7e12f394a3.1.js');
		_kmq.push(['identify', '<?="$user_profile->first_name"?> <?="$user_profile->last_name"?> (<?="$user_profile->id"?>)']);
		_kmq.push(['record', 'logged in']);
		location.replace("http://<?=$_SERVER["HTTP_HOST"]?>");
	</script>
</head>
</html>

<?
			
        	} else {
            	$db->executeSQL("INSERT INTO Users (id,oauth,tilltalsnamn,efternamn,facebook_id,email,senasteinloggning)
                	VALUES ('','" . $params['access_token'] . "','" . $user_profile->first_name . "','" . $user_profile->last_name . "'," .
					$user_profile->id . ",'" . $user_profile->email . "',now())", "INSERT");

				$createdId = $db->executeSQL("SELECT * FROM Users WHERE facebook_id = " . $user_profile->id, "SELECT");

				$_SESSION['user_id'] = $createdId->id;
				?>
			<html>
			<head>
				<script type="text/javascript">
				  var _kmq = _kmq || [];
				  function _kms(u){
				    setTimeout(function(){
				      var s = document.createElement('script'); var f = document.getElementsByTagName('script')[0]; s.type = 'text/javascript'; s.async = true;
				      s.src = u; f.parentNode.insertBefore(s, f);
				    }, 1);
				  }
					_kms('//i.kissmetrics.com/i.js');_kms('//doug1izaerwt3.cloudfront.net/fbc518803b472ead5b21ddade8336d7e12f394a3.1.js');
					_kmq.push(['identify', '<?="$user_profile->first_name"?> <?="$user_profile->last_name"?> (<?="$user_profile->id"?>)']);
					_kmq.push(['record', 'signed up']);
					location.replace("http://<?=$_SERVER["HTTP_HOST"]?>");
				</script>
			</head>
			</html>
			<?
			}
    	}
	} else {
		echo("The state does not match. You may be a victim of CSRF.");
	}

 ?>


