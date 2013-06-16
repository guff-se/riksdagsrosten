<?php 
error_reporting(E_ALL);
ini_set('display_errors','On');

  // start a session //
  session_start(); 
  
  // include the database Class //
  require_once '../classes/Database.class.php';

	$db=new Database();
	$db->connectSQL();
		
	$parameters="";


	if(0)//!isset($_GET['showAll']))
		$parameters="AND Utskottsforslag.visible = 1";
?>

<?
include("header.php");
?>
		<div class="row page-header">
			<div class="span12 pagination pagination-centered">
				<h2>Voteringar</h2>
				<ul>
		<?php
		require_once '../includes/Pager/Pager.php';
		/* We will bypass the database connection code ... */

		$sql = "select count(*) as total from Utskottsforslag where Utskottsforslag.punkt = 1 order by beslut_datum". $parameters;
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
		'separator' => '',
		'spacesBeforeSeparator' => 0,
		'spacesAfterSeparator' => 0
		);
		$pager = Pager::factory($pager_options);
		echo $pager->links;

		list($from, $to) = $pager->getOffsetByPageId();
		$from = $from - 1;

		/* The number of rows to get per query */
		$perPage = $pager_options['perPage'];

		?>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="span12">
			<table class="table">
				<tbody>
<?
$result = $db->executeSQLRows("SELECT Utskottsforslag.*, Organ.* FROM Utskottsforslag, Organ
        WHERE Utskottsforslag.punkt = 1
        AND Utskottsforslag.organ = Organ.organ ". $parameters ."
        order BY Utskottsforslag.beslut_datum DESC LIMIT $from, $perPage");

		foreach($result as $row) {
?>
				<tr>
					<td width="130px">
						<form action="post.php" method="post">
							<input type="hidden" name="dok_id" value="<?=$row->dok_id?>">
	<?					if($row->visible) { ?>
						<button name="visible" class="btn" type="submit" value="0">G&ouml;m</button>
	<?					} else { ?>
						<button name="visible" class="btn btn-success" type="submit" value="1">Visa</button>
	<? 					} ?>
						<a class="btn btn-primary" href="/admin/visa.php?dok_id=<?=$row->dok_id?>">Redigera</a>
					</form>
					</td><td>
						<strong><?=$row->titel?></strong>
						<a class="label" href="/votering/<?=$row->dok_id?>"><?=$row->dok_id?></a>
						<?if($row->status==5) {?>
							<span class="label label-info">kommande</span>
						<?} else {?>
							<span class="label label-success">tidigare</span>
						<?}?>
						<br />
						<i>
							<?=$row->beslut_datum?>
						</i>
	<?					if($row->status==99) { ?>
							(acklamation)
	<?					} else {
								if(abs($row->roster_ja-$row->roster_nej)<10) { ?>
							<font style="color:red;">
	<?							} ?>
							( ja:<?=$row->roster_ja?> / nej:<?=$row->roster_nej?> /
								avstår:<?=$row->roster_avstar?> / frånvaro:<?=$row->roster_franvarande?>)</font>
						<? } ?>
	
					</td>
				</tr>
<?		} ?>
			</tbody>
		</table>
			</div>
		</div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>




