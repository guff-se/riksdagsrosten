<html>
<head><meta charset="utf-8" />
	</head>
	<body>
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


// Voteringar behvöer fältet publicerat. Detta hämtar fältet från Utskottsforslag och lägger in i Voteringar.
/*
$result = $db->executeSQLRows("select Utskottsforslag.publicerad, Utskottsforslag.dok_id, Voteringar.dok_id, Voteringar.id from Utskottsforslag, Voteringar where Utskottsforslag.dok_id = Voteringar.dok_id");
print(mysql_error());
foreach($result as $votering) {
	$db->executeSQL("update Voteringar set publicerad='$votering->publicerad' where id='$votering->id'","update");
	print(mysql_error());
}
/**/



/*
// Roster behöver dok_id. Detta hämtas från Voteringar och lägges in i Roster.
$result = $db->executeSQLRows("select Roster.id, Voteringar.dok_id from Roster, Voteringar where Roster.votering_id = Voteringar.votering_id");
print(mysql_error());
foreach($result as $votering) {

	$db->executeSQL("update Roster set dok_id='$votering->dok_id' where id='$votering->id'","update");
	print(mysql_error());
}
/**/




// Denna koden räknar röster för varje votering och krävs troligtvis inte längre efter att johan laddat in dessa direkt från riksdagen.
/*
$result = $db->executeSQLRows("select * from Voteringar");
foreach($result as $votering) {
	$result2 = $db->executeSQLRows("select * from Roster where votering_id='$votering->votering_id'");
	$ja=0;
	$nej=0;
	$avstar=0;
	$franvarande=0;
	foreach($result2 as $rost) {
		if($rost->rost=="Ja")
				$ja++;
		if($rost->rost=="Nej")
				$nej++;
		if($rost->rost=="Avstår")
				$avstar++;
		if($rost->rost=="Frånvarande")
				$franvarande++;
	}
	$db->executeSQL("update Voteringar set roster_ja='$ja', roster_nej='$nej',
							roster_avstar='$avstar', roster_franvarande='$franvarande'
							where votering_id='$votering->votering_id'", "UPDATE");
	print(mysql_error());
//	print ("ja:$ja, nej:$nej, avstår:$avstar, frånvarande:$franvarande<br>");
}
/**/





// Denna koden sumerar hur varje parti röstat i varje votering och INSERTar detta i tabellen PartiRoster
// Detta innebär att tabellen PartiRoster måste tömmas om man vill köra om denna koden.
/*
$result = $db->executeSQLRows("select * from Voteringar order by id desc");
foreach($result as $votering) {
	$result2 = $db->executeSQLRows("select Roster.rost, Ledamoter.parti
				from Roster, Ledamoter where Roster.intressent_id=Ledamoter.intressent_id and votering_id='$votering->votering_id'");
	print(mysql_error());
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
	print("<br>$votering->votering_id -> antal roster:".count($result2)."<br>");
	
	foreach($result2 as $rost) {
		if($rost->parti) {
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
		$db->executeSQL("insert into PartiRoster set votering_id='$votering->votering_id',
								roster_ja='$ja[$symbol]', roster_nej='$nej[$symbol]',
								roster_avstar='$avstar[$symbol]', roster_franvarande='$franvarande[$symbol]',
								dok_id='$votering->dok_id', parti='$symbol', punkt='$votering->punkt'", "UPDATE");
		print(mysql_error());
	}
	print("\narray_sum->");
	print(array_sum($ja)+array_sum($nej)+array_sum($avstar)+array_sum($franvarande)."<br>");

}
/**/





// Här beräknas partipiskan för varje ledamot och uppdaterar tabellen Ledamoter.
// Detta är beroende av att förra scriptbiten (skapandet PartirRoster) har körts.
/*
$result = $db->executeSQLRows("select id, parti, intressent_id from Ledamoter");
foreach($result as $l) {
	$result2 = $db->executeSQLRows("select PartiRoster.roster_ja, PartiRoster.roster_nej, PartiRoster.roster_avstar, Roster.rost
									from PartiRoster, Roster
									where PartiRoster.votering_id = Roster.votering_id and PartiRoster.parti = '$l->parti'
									and Roster.intressent_id='$l->intressent_id'");
	$same=0;
	$total=0;
	$avstar=0;
	foreach($result2 as $v) {
		if(($v->roster_ja > $v->roster_nej) && ($v->roster_ja > $v->roster_avstar)) // partiet röstade ja
			if($v->rost=="Ja" || $v->rost=="Avstår")
				$same++;
		if(($v->roster_nej > $v->roster_ja) && ($v->roster_nej > $v->roster_avstar)) // partiet röstade nej
			if($v->rost=="Nej" || $v->rost=="Avstår")
				$same++;
		if(($v->roster_avstar > $v->roster_ja) && ($v->roster_avstar > $v->roster_nej)) // partiet avstod
			if($v->rost=="Avstår") {
				$same++;
				$avstar++;
			}
		if($v->rost!="Frånvarande")
			$total++;
//		print("ja:$v->roster_ja, nej:$v->roster_nej, röst: $v->rost<br>");
	}
	$piska = round($same / $total,2)*100;
	print("update Ledamoter set piska='$piska', roster_piska='$same', roster_tot='$total' where id='$l->id'<br>");
	$db->executeSQL("update Ledamoter set piska='$piska', roster_piska='$same', roster_tot='$total' where id='$l->id'","UPDATE");
}
/**/





// Denna summerar partipiskerösterna och uppdaterar tablen Parti.
// Detta användersig av den individuella partipiskan och är beroende av att föregående script (ovan) körts.
/*
foreach($PARTI as $p => $namn) {
	$result = $db->executeSQL("select sum(roster_tot) as tot, sum(roster_piska) as piska from Ledamoter where parti='$p'","SELECT");
	$piska = $result->piska/$result->tot;
	print("update Parti set roster_tot='$result->tot', roster_piska='$result->piska', piska='$piska' where symbol='$p'<br>");
//	$db->executeSQL("update Parti set roster_tot='$result->tot', roster_piska='$result->piska', piska='$piska' where symbol='$p'","UPDATE");
}
/**/




// Denna summerar frånvaro per Parti och är beroende av att PartiRoster är skapad enl. ovan.
/*
$result = $db->executeSQLRows("select sum(roster_ja+roster_nej+roster_avstar) as total ,sum(roster_franvarande) as franvarande,parti from PartiRoster group by parti;");
foreach($result as $p) {
	$franvp=round($p->franvarande/$p->total,2)*100;
	$db->executeSQLRows("update Parti set franvaro='$franvp' where symbol='$p->parti'","UPDATE");
}
/**/



// denna koden uppdaterar frånvaro och åvstår i "Ledamöter", baserat på de individuella rösterna.
/*
$result = $db->executeSQLRows("select * from Ledamoter");
foreach($result as $ledamot) {
	print("$ledamot->tilltalsnamn $ledamot->efternamn ($ledamot->intressent_id) ->");
	$result2 = $db->executeSQLRows("select * from Roster where intressent_id='$ledamot->intressent_id'");
	print(mysql_error());
	$franvarande=0;
	foreach($result2 as $rost) {
		if($rost->rost=="Frånvarande")
				$franvarande++;
	}
	$tot = count($result2);
	$procent = round($franvarande / $tot,2)*100;
	$db->executeSQL("update Ledamoter set franvarande='$procent' where intressent_id='$ledamot->intressent_id'", "UPDATE");
	print(mysql_error());
	print ("tot:$tot, frånvarande:$franvarande, procent:$procent<br>");
}
/**/
print("fin...");
?>
</body>
</html>