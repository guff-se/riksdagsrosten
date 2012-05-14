<?php 
$PARTI["M"]="Moderaterna";
$PARTI["S"]="Socialdemokraterna";
$PARTI["V"]="Vänsterpartiet";
$PARTI["MP"]="Miljöpartiet";
$PARTI["KD"]="Kristdemokraterna";
$PARTI["C"]="Centerpartiet";
$PARTI["SD"]="Sverigedemokraterna";
$PARTI["FP"]="Folkpartiet";
error_reporting(E_ALL);
ini_set('display_errors','On');
  // start a session //
  session_start(); 
  
  // include the database Class //
  require_once '../classes/Database.class.php';

	$db=new Database();
	$db->connectSQL();
	
	if(isset($_SESSION["user_id"])) {
		$USER = $db->executeSQL("select * from Users where id='".$_SESSION["user_id"]."'","SELECT");
	}
$vid = mysql_real_escape_string($_GET["vid"]);
$rost = mysql_real_escape_string($_GET["rost"]);

if ($USER && $vid) {
	$result=$db->executeSQL("select id from UserRoster where user_id='$USER->id' and utskottsforslag_id='$vid'", "SELECT");

	if(isset($result)) {
			$sql = "delete from UserRoster WHERE id='$result->id'";
			$graph_url = "https://graph.facebook.com/{$result->id}?access_token=" .
						$_SESSION['access_token'];
			//print("graph_url: $graph_url\n");			
			$opts = array('http'=>array('method'=>"DELETE",));
			$context = stream_context_create($opts);
			$file = file_get_contents($graph_url, false, $context);
			$db->executeSQL($sql,"DELETE");
        }
		// Facebook Open Graph

	if($USER->publik) {
			$res2 = $db->executeSQL("select dok_id from Utskottsforslag where id='$vid'", "SELECT");
			$dok_id=$res2->dok_id;
			print($dok_id);
			$graph_url = "https://graph.facebook.com/me/riksdagsrosten:vote_on?method=post&access_token=" .
						$_SESSION['access_token'] . "&vote=" . $rost . "&bill=http://www.riksdagsrosten.se/votering/$dok_id";
	     	$fb_out = json_decode(file_get_contents($graph_url));
	       	$sql = "insert into UserRoster set id='$fb_out->id', rost='$rost', utskottsforslag_id='$vid', user_id='$USER->id'";              
	        $db->executeSQL($sql,"UPDATE");
	}
	else {
	      	$sql = "insert into UserRoster set rost='$rost', utskottsforslag_id='$vid', user_id='$USER->id'";              
	        $db->executeSQL($sql,"UPDATE");
	}
		// uppdatera voteringar //
		$push_sql = "
              UPDATE Utskottsforslag 
              SET folket_nej=(select count(*) AS antal FROM UserRoster WHERE rost = 'Nej' and utskottsforslag_id = '$vid'),
              folket_ja=(select count(*) AS antal FROM UserRoster WHERE rost = 'Ja' AND utskottsforslag_id = '$vid')
              WHERE id = '$vid' LIMIT 1";

        $db->executeSQL($push_sql,"UPDATE");

		// pusha update to User
		
		$result=$db->executeSQLRows("select UserRoster.rost, Utskottsforslag.votering_id from UserRoster, Utskottsforslag
								where UserRoster.user_id = '$USER->id' and UserRoster.utskottsforslag_id=Utskottsforslag.id and Utskottsforslag.punkt=1");
		$lika=array();
		$olika=array();
				foreach($PARTI as $p => $bs) {
					$partier_lika[$p]=0;
					$partier_olika[$p]=0;
				}
        		foreach($result as $urost) {
					$result2 = $db->executeSQLRows("select rost, intressent_id from Roster
											where votering_id='$urost->votering_id'");
					foreach($result2 as $lrost) {
						if($lrost->rost== "Ja" || $lrost->rost=="Nej")
							if($lrost->rost == $urost->rost) {
								if(isset($lika[$lrost->intressent_id]))
									$lika[$lrost->intressent_id]++;
								else
									$lika[$lrost->intressent_id]=0;
							}
							else {
								if(isset($olika[$lrost->intressent_id]))
									$olika[$lrost->intressent_id]++;
								else
									$olika[$lrost->intressent_id]=0;
								
							}
					}
					$result2 = $db->executeSQLRows("select roster_ja,roster_nej,parti from PartiRoster
												where votering_id='$urost->votering_id'");
					foreach($result2 as $p) {
						if($p->roster_ja < $p->roster_nej)
							if($urost->rost=="Nej")
								$partier_lika[$p->parti]++;
							else
								$partier_olika[$p->parti]++;
						if($p->roster_ja > $p->roster_nej)
							if($urost->rost=="Ja")
								$partier_lika[$p->parti]++;
							else
								$partier_olika[$p->parti]++;
					}
					
				}
				foreach($olika as $intressent_id => $count) {
					$result3=$db->executeSQL("select id from LedamotMatch where
											intressent_id='$intressent_id' and user_id='$USER->id'","SELECT");
					if(isset($result3)) {
						$db->executeSQL("update LedamotMatch set roster_olika=$count where
												intressent_id='$intressent_id' and user_id='$USER->id'","UPDATE");
					}
					else {
						$db->executeSQL("insert into LedamotMatch set roster_olika=$count,
												intressent_id='$intressent_id', user_id='$USER->id'","INSERT");
					}
					print(mysql_error());
				}
				foreach($lika as $intressent_id => $count) {
					$result3=$db->executeSQL("select id from LedamotMatch where
											intressent_id='$intressent_id' and user_id='$USER->id'","SELECT");
					if(isset($result3)) {
						$db->executeSQL("update LedamotMatch set roster_lika=$count where
												intressent_id='$intressent_id' and user_id='$USER->id'","UPDATE");
					}
					else {
						$db->executeSQL("insert into LedamotMatch set roster_lika=$count,
												intressent_id='$intressent_id', user_id='$USER->id'","INSERT");
					}
					print(mysql_error());
				}
				
				foreach($partier_olika as $parti => $count) {
					$result3=$db->executeSQL("select id from PartiMatch where
											parti='$parti' and user_id='$USER->id'","SELECT");
					if(isset($result3)) {
						$db->executeSQL("update PartiMatch set roster_olika=$count where
												parti='$parti' and user_id='$USER->id'","UPDATE");
					}
					else {
						$db->executeSQL("insert into PartiMatch set roster_olika=$count,
												parti='$parti', user_id='$USER->id'","INSERT");
					}
					print(mysql_error());
				}
				foreach($partier_lika as $parti => $count) {
					$result3=$db->executeSQL("select id from PartiMatch where
											parti='$parti' and user_id='$USER->id'","SELECT");
					if(isset($result3)) {
						$db->executeSQL("update PartiMatch set roster_lika=$count where
												parti='$parti' and user_id='$USER->id'","UPDATE");
					}
					else {
						$db->executeSQL("insert into PartiMatch set roster_lika=$count,
												parti='$parti', user_id='$USER->id'","INSERT");
					}
					print(mysql_error());
				}
				
				$db->executeSQL("update LedamotMatch set points=roster_lika-roster_olika,
									procent=roster_lika/(roster_lika+roster_olika) where
									user_id='$USER->id'","UPDATE");
				$db->executeSQL("update PartiMatch set points=roster_lika-roster_olika,
									procent=roster_lika/(roster_lika+roster_olika) where
									user_id='$USER->id'","UPDATE");
				// Fixa matchning med parti
			
			
		}else{
	die("HJÄÄLP!!! Vi blir hackade!!!");
}


header("Location: ".$_SERVER["HTTP_REFERER"]);	
?>