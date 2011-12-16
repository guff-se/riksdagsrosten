<?php 
error_reporting(E_ALL);
ini_set('display_errors','On');

  // start a session //
  session_start(); 
  
  // include the database Class //
  require_once '../classes/Database.class.php';

	$db=new Database();
	$db->connectSQL();
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Riksdagsr&ouml;sten admin</title>
</head>
<body>
	<form action="post.php" method="post">
	<input type="submit" value="Spara &auml;ndringar">
	
	<table>
<?php
require_once '../includes/Pager/Pager.php';
/* We will bypass the database connection code ... */

$sql = "select count(*) as total from Utskottsforslag";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
/* Total number of rows in the logs table */
$totalItems = $row['total'];
 
/* Set some options for the Pager */
$pager_options = array(
'mode'       => 'Sliding',   // Sliding or Jumping mode. See below.
'perPage'    => 20,   // Total rows to show per page
'delta'      => 4,   // See below
'totalItems' => $totalItems,
);
$pager = Pager::factory($pager_options);
echo $pager->links;

list($from, $to) = $pager->getOffsetByPageId();
$from = $from - 1;
 
/* The number of rows to get per query */
$perPage = $pager_options['perPage'];

$sql = "SELECT Utskottsforslag.*, Voteringar.* FROM Utskottsforslag, Voteringar 
        WHERE Utskottsforslag.dok_id = Voteringar.dok_id AND Voteringar.punkt = 1
        ORDER BY Utskottsforslag.publicerad DESC LIMIT $from , $perPage";
$result = $db->executeSQLRows($sql);

foreach($result as $row) {
	print("<tr><td rowspan=2 align=center>
		Aktiv:<br>
		<input type=\"hidden\" name=\"ids[$row->dok_id]\">
		<input type=\"checkbox\" name=\"checks[$row->dok_id]\" ");
	if($row->status)
		print("checked=\"yes\"");
	print(">
	</td><td><b>
		$row->titel
	</b><i>
		$row->publicerad
	</i>
		");
	if(abs($row->roster_ja-$row->roster_nej)<10)
		print("<font style=\"color:red;\">");
	print("( ja:$row->roster_ja / nej:$row->roster_nej / avstår:$row->roster_avstar / frånvaro:$row->roster_franvarande)</font>
	</td></tr><tr><td>
		$row->bik
	</td></tr>
	<tr><td colspan=2><hr></td></tr>");

}
?>
</table>
<input type="submit" value="Spara &auml;ndringar">

	</form>
</body>
</html>