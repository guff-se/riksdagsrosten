<?php

if(!isset($USER)) {
    header("Location: /");
    die(" Prova logga in först");
    
    
}

$HEADER['title']="Min profil";
$HEADER['type']="";

include_once("includes/header.php");

$graph_url = "https://graph.facebook.com/me/friends&access_token=" .
			$_SESSION['access_token'];
$fb_out = json_decode(file_get_contents($graph_url));

print(serialize($fb_out));

?>


