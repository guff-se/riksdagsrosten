<?
header('HTTP/1.0 404 Not Found');
$HEADER['title'] = "404";
include_once("includes/header.php");
$comment="REQUEST_URI=" . $_SERVER["REQUEST_URI"]."<br>";
if(isset($_SERVER["HTTP_REFERER"]))
	$comment=$comment."\nHTTP_REFERER=".$_SERVER["HTTP_REFERER"]."<br>";

$db->executeSQL("insert into Log set code='404', comment='$comment'","INSERT");

?>
<div style="height:404px;text-align:center;">
	<br/><br/>
		<h1>404</h1>
        <h2>Ooops... Vi är visst rörigare än riksdagen själva.<br/>Den här sidan finns inte.</h2>
</div>