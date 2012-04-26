<?
define('YOUR_APP_ID', '196658153744046');
define('YOUR_APP_SECRET', '0349d42b3e5992fe738ee45c95248c7f');

function get_facebook_cookie($app_id, $app_secret) {
  $args = array();
  parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
  ksort($args);
  $payload = '';
  foreach ($args as $key => $value) {
    if ($key != 'sig') {
      $payload .= $key . '=' . $value;
    }
  }
  if (md5($payload . $app_secret) != $args['sig']) {
    return null;
  }
  return $args;
}

$cookie = get_facebook_cookie(YOUR_APP_ID, YOUR_APP_SECRET);

$user = json_decode(file_get_contents(
    'https://graph.facebook.com/me?access_token=' .
    $cookie['access_token']));

function PartiVoteringPost($parti, $)
	if(isset($result)) {
			$sql = "delete from UserRoster WHERE id='$result->id'";
/*			$graph_url = "https://graph.facebook.com/{$result->id}?access_token=" .
						$_SESSION['access_token'];
						
			$opts = array('http'=>array('method'=>"DELETE",));
			$context = stream_context_create($opts);
			$file = file_get_contents($graph_url, false, $context);*/
			$db->executeSQL($sql,"DELETE");
        }
		// Facebook Open Graph

/*		$graph_url = "https://graph.facebook.com/me/riksdagsrosten:vote_on?method=post&access_token=" .
					$_SESSION['access_token'] . "&vote=" . $rost . "&bill=" . $_SERVER['HTTP_REFERER'];
     	$fb_out = json_decode(file_get_contents($graph_url));*/

		// uppdatera UserRoster
//       	$sql = "insert into UserRoster set id='$fb_out->id', rost='$rost', utskottsforslag_id='$vid', user_id='$user'";              
       	$sql = "insert into UserRoster set rost='$rost', utskottsforslag_id='$vid', user_id='$user'";              
        $db->executeSQL($sql,"UPDATE");


?>