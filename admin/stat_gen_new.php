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

?>
<html>
<head><meta charset="utf-8" />
	</head>
	<body>

<?
// denna koden uppdaterar frånvaro i "Ledamöter", baserat på de individuella rösterna.
// eftersom vi inte använder "avstår" nånstans, så skippar vi det så länge
/*
$result = $db->executeSQLRows("select * from Ledamoter");
foreach($result as $ledamot) {
//	print("$ledamot->tilltalsnamn $ledamot->efternamn ($ledamot->intressent_id) ->");
	$result2 = $db->executeSQLRows("select * from Roster where intressent_id='$ledamot->intressent_id'");
//	print(mysql_error());
	$franvarande=0;
	if(count($result2)>0) {
		foreach($result2 as $rost) {
			if($rost->rost=="Frånvarande") {
					$franvarande++;
				}
		}
		$tot = count($result2);
		$procent = round($franvarande / $tot,2)*100;
		$db->executeSQL("update Ledamoter set aktiv_roster_tot='$tot', franvarande_antal='$franvarande', franvarande='$procent' where intressent_id='$ledamot->intressent_id'", "UPDATE");
//		print(mysql_error());
//		print ("tot:$tot, frånvarande:$franvarande, procent:$procent<br>\n");
	}
	else {
//		print ("Inga röster<br>\n");
	}
}
/**/


// UPDATE Parti
// Beräkna frånvaro per parti

/*
foreach($PARTI as $symbol => $name) {
//	print("nu kollar vi frånvaro och totalröster på $name<br>");
	$result = $db->executeSQLRows("select * from Ledamoter where parti='$symbol'");
	$franvaro=0;
	$tot=0;
	foreach($result as $ledamot) {
		$franvaro+=$ledamot->franvarande_antal;
		$tot+=$ledamot->aktiv_roster_tot;
	}
	$procent = round($franvaro / $tot * 100);
//	print("enl. beräkning: $franvaro franvarande av $tot totalt, dvs $procent %<br>");
	$db->executeSQL("update Parti set roster_tot='$tot', franvaro='$procent' where symbol='$symbol'", "UPDATE");
}

/**/



/// INSERT PartiRoster
/// Denna koden räknar de fyra typerna av röster för varje parti i varje fråga och sätter in i PartiRoster.
/// Detta skulle kunna tas direkt ifrån riksdagdatan i parsingen, men jag litar inte på den koden längre och har därför gjort om det.
//
// OBS! Denna koden använder INSERT, så man måste deleta all data i PartiRoster för att köra.
//


$db->executeSQL("DELETE from PartiRoster", "DELETE");

$vot_id_res = $db->executeSQLRows("select distinct(votering_id), dok_id from Roster");
foreach($vot_id_res as $votering) {
//	print("votering: $votering->votering_id<br>");
	$result = $db->executeSQLRows("select Roster.rost, Ledamoter.parti from Roster, Ledamoter
		where Roster.intressent_id=Ledamoter.intressent_id and votering_id='$votering->votering_id'");
	unset($ja);
	unset($nej);
	unset($avstar);
	unset($franvarande);
	foreach($PARTI as $symbol => $name) { // Gör en för varje rösttyp med Parti som key.
		$ja[$symbol]=0;
		$nej[$symbol]=0;
		$avstar[$symbol]=0;
		$franvarande[$symbol]=0;
	}

	foreach($result as $rost) {
		if($rost->parti && $rost->parti!="-") {
			if($rost->rost=="Ja")
				$ja[$rost->parti]++;
			if($rost->rost=="Nej")
				$nej[$rost->parti]++;
			if($rost->rost=="Avstår")
				$avstar[$rost->parti]++;
			if($rost->rost=="Frånvarande")
				$franvarande[$rost->parti]++;
		}
	}

	foreach($PARTI as $symbol => $name) {
//		print("$name -><br>ja: $ja[$symbol], nej: $nej[$symbol], avstar: $avstar[$symbol], frånvarande: $franvarande[$symbol](enl. beräkning)<br>");

		// beräkna partilinjen
		if(($ja[$symbol] > $nej[$symbol]) && ($ja[$symbol] > $avstar[$symbol])) // partiet röstade ja
			$piska="Ja";
		else if(($nej[$symbol] > $ja[$symbol]) && ($nej[$symbol] > $avstar[$symbol])) // partiet röstade nej
			$piska="Nej";
		else if(($avstar[$symbol] > $ja[$symbol]) && ($avstar[$symbol] > $nej[$symbol])) // partiet avstod
			$piska="Avstår";
		else
			$piska="-";

		$punktresult=$db->executeSQL("select punkt from Utskottsforslag where votering_id='$votering->votering_id'","SELECT");
		if($punktresult) {
			$query="insert into PartiRoster set parti='$symbol', votering_id='$votering->votering_id',
				dok_id='$votering->dok_id', punkt='$punktresult->punkt', piska='$piska',
				roster_ja='$ja[$symbol]', roster_nej='$nej[$symbol]', roster_avstar='$avstar[$symbol]', roster_franvarande='$franvarande[$symbol]'";
		}
		else {
			$query="insert into PartiRoster set parti='$symbol', votering_id='$votering->votering_id', dok_id='$votering->dok_id', piska='$piska',
				roster_ja='$ja[$symbol]', roster_nej='$nej[$symbol]', roster_avstar='$avstar[$symbol]', roster_franvarande='$franvarande[$symbol]'";
		}
//			print("$query<br>");
			$db->executeSQL($query, "UPDATE");
			
//// Detta är för att kolla istället för att sätta in.

/*		$result2 = $db->executeSQLRows("select * from PartiRoster where votering_id='$votering->votering_id' AND parti='$symbol'");
		if(!count($result2)) {}
			print("<b>denna votering saknas i PartiRoster!</b><br>")
		}
		else {
			$partirost = $result2[0];
			print("ja: $partirost->roster_ja, nej: $partirost->roster_nej, avstår: $partirost->roster_avstar, frånvarande: $partirost->roster_franvarande (enl. databas)<br>");
			if($ja[$symbol] != $partirost->roster_ja || $nej[$symbol] != $partirost->roster_nej ||
							$avstar[$symbol] != $partirost->roster_avstar || $franvarande[$symbol] != $partirost->roster_franvarande)
				print("<b>här är nåt fel!</b><br>");
		}
*/

	}
}
/**/



// UPDATE Ledamoter
// Här beräknas partipiskan för varje ledamot och uppdaterar tabellen Ledamoter.
// Detta är beroende av att förra scriptbiten (skapandet PartirRoster) har körts.
/*
$result = $db->executeSQLRows("select id, parti, intressent_id from Ledamoter");
foreach($result as $l) {
	$result2 = $db->executeSQLRows("select PartiRoster.piska, Roster.rost, Roster.votering_id from PartiRoster, Roster
									where PartiRoster.votering_id = Roster.votering_id and PartiRoster.parti = '$l->parti'
									and Roster.intressent_id='$l->intressent_id'");
	$same=0;
	$total=0;
	$avstar=0;
	if(count($result2)) {
	foreach($result2 as $v) {
//		print("votering $v->votering_id: piska: $v->piska, röst: $v->rost ->");
		if($v->rost!="Frånvarande") {
			$total++;
			if($v->piska == $v->rost) {
//				print("samma!<br>");
				$same++;
			}
			
			else {
				if($v->rost=="Avstår") {
//					print("<b>Avstod!</b><br>");
					$avstar++;
				}
			}
		}
			
	}
	if($total)
		$piska = round($same / $total,2)*100;
	else
		$piska = 100;
	$sql="update Ledamoter set piska='$piska', roster_piska='$same', roster_tot='$total' where id='$l->id'";
//	print("$sql<br>\n");
	$db->executeSQL($sql,"UPDATE");
	}
}

/**/

// UPDATE Parti
// Denna summerar partipiskerösterna och uppdaterar tablen Parti.
// Detta användersig av den individuella partipiskan och är beroende av att föregående script (ovan) körts.
/*
foreach($PARTI as $p => $namn) {
	$result = $db->executeSQL("select sum(roster_tot) as tot, sum(roster_piska) as piska from Ledamoter where parti='$p' and aktiv=1","SELECT");
	$piska = $result->piska/$result->tot;
	$sql= "update Parti set roster_tot='$result->tot', roster_piska='$result->piska', piska='$piska' where symbol='$p'";
//	print("$sql<br>\n");
	$db->executeSQL($sql,"UPDATE");
}
/**/







print("fin...");
?>
</body>
</html>