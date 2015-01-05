<?php
//Einbinden der Konfigurationsdatei
require_once "../common.php";
require_once "../session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

$sql1 = "SELECT * FROM ableseprotokoll";
$ableseprotokoll = $DB->query($sql1);

$sql2 = "SELECT * FROM anlieferung WHERE updated != 1";
$anlieferung = $DB->query($sql2);

foreach($ableseprotokoll as $alpk)
{
	$date = $alpk['date'];
	$tag = date('j', $date);
	$monat = date('n', $date);
	$jahr = date('Y', $date);
	
	$morgen = mktime(0,0,0,$monat,$tag,$jahr);
	$abend = mktime(23,59,59,$monat,$tag,$jahr);
	
	$vortag_morgen = mktime(0,0,0,$monat,$tag-1,$jahr);
	$vortag_abend = mktime(23,59,59,$monat,$tag-1,$jahr);
	
	//ORC-Anlage
	$orc = $DB->query("SELECT * FROM orc WHERE date >= ".$morgen." && date <= ".$abend." LIMIT 1");
	
	if(empty($orc))
	{
		//echo "Heute=".date('d.m.Y H:i', $date)." ";
		//echo $vortag_morgen;//date('d.m.Y H:i', $vortag_morgen)." ";
		//echo $vortag_abend;//date('d.m.Y H:i', $vortag_abend)." ";
		
		$orc_vortag = $DB->query("SELECT * FROM orc WHERE date >= ".$vortag_morgen." && date <= ".$vortag_abend." LIMIT 1");

		if(empty($orc_vortag))
		{
			$orcStandVortag = 0;
			$orcEnergiewert = 0;
		}
		else
		{
			$orcStandVortag = $orc_vortag[0]['ablesetag'];	
			if(isset($alpk['energiewert']))
			{
				$orcEnergiewert = $alpk['energiewert'];
			}
			else
			{
				$orcEnergiewert = 0;
			}
			
		}
		
		if(isset($alpk['z1']))
		{
			$orcStand = $alpk['z1'];
		}
		else
		{
			$orcStand = 0;		
		}
		//echo "WertVortag=".$orcStandVortag."WertAblesetag=".$orcStand."Energiewert=".$orcEnergiewert ;
		$DB->query("INSERT INTO orc (date, vortag, ablesetag, energiewert) VALUES (".$date.", ".$orcStandVortag.", ".$orcStand.", ".$orcEnergiewert.") ");		
	}
	else
	{
		$orc_vortag = $DB->query("SELECT * FROM orc WHERE date >= ".$vortag_morgen." && date <= ".$vortag_abend." LIMIT 1");
		if(empty($orc_vortag))
		{
			$tableOrcStandVortag = 0;
		}
		else
		{
			$tableOrcStandVortag = $orc_vortag[0]['ablesetag'];	
		}
		
		$tableOrcStand = $orc[0]['ablesetag'];
		$tableOrcEnergiewert = $orc[0]['energiewert'];
		
		//echo $tableOrcStand."=".$alpk['energiewert']."<br>";
		//echo $tableOrcEnergiewert."=".$alpk['z1']."<br>";
		
		if($alpk['energiewert'] == $tableOrcStand)
		{
			//echo "Passt";	
		}
	}
	
	//KESSELEINSCHÜBE
	$kessel = $DB->query("SELECT * FROM kesseleinschuebe WHERE date >= ".$morgen." && date <= ".$abend." LIMIT 1");
	if(empty($kessel))
	{
		//echo "Heute=".date('d.m.Y H:i', $date)." ";
		
		$kessel_vortag = $DB->query("SELECT * FROM kesseleinschuebe WHERE date >= ".$vortag_morgen." && date <= ".$vortag_abend." LIMIT 1");
		if(empty($kessel_vortag))
		{
			$einschuebeVortag = 0;
			$einschuebeProTag = 0;
			if(isset($alpk['kesseleinschuebe']))
			{
				$einschuebe = $alpk['kesseleinschuebe'];
			}
		}
		else
		{
			$einschuebeVortag = $kessel_vortag[0]['ablesetag'];	
			if(isset($alpk['kesseleinschuebe']))
			{
				$einschuebe = $alpk['kesseleinschuebe'];
				$einschuebeProTag = $einschuebe - $einschuebeVortag;
			}
			else
			{
				$einschuebe = 0;
				$einschuebeProTag = 0;
			}
			
		}
		//echo "WertVortag=".$einschuebeVortag."WertAblesetag=".$einschuebe."Einsch&uuml;be=".$einschuebeProTag ;
		$DB->query("INSERT INTO kesseleinschuebe (date, vortag, ablesetag, anzahl) VALUES (".$date.", ".$einschuebeVortag.", ".$einschuebe.", ".$einschuebeProTag.") ");
	}
	else
	{
		/*$kessel_vortag = $DB->query("SELECT * FROM kesseleinschuebe WHERE date >= ".$vortag_morgen." && date <= ".$vortag_abend." LIMIT 1");
		if(empty($kessel_vortag))
		{
			$tableEinschuebeVortag = 0;
		}
		else
		{
			$tableEinschuebeVortag = $kessel_vortag[0]['ablesetag'];	
		}
		
		
		$tableEinschuebe = $kessel[0]['ablesetag'];
		$tableEinschuebeProTag = $kessel[0]['anzahl'];
		$einschuebeProTag = $alpk['kesseleinschuebe'] - $tableEinschuebeVortag;
		
		//echo $tableEinschuebe."=".$alpk['kesseleinschuebe']."<br>";
		if($tableEinschuebe != $alpk['kesseleinschuebe'] && $tableEinschuebeProTag != $einschuebeProTag)
		{
			$DB->query("UPDATE kesseleinschuebe SET vortag= ".$tableEinschuebeVortag.", ablesetag=".$alpk['kesseleinschuebe'].", anzahl=".$einschuebeProTag." ");
		}*/
	}
	
	//WÄRMEERZEUGUNG
	$waerme = $DB->query("SELECT * FROM waermeerzeugung WHERE date >= ".$morgen." && date <= ".$abend." LIMIT 1");
	if(empty($waerme))
	{
		//echo "Heute=".date('d.m.Y H:i', $date)." ";
		
		
		
		$waerme_vortag = $DB->query("SELECT * FROM kesseleinschuebe WHERE date >= ".$vortag_morgen." && date <= ".$vortag_abend." LIMIT 1");
		if(empty($waerme_vortag))
		{
			$Z1Vortag = 0;
			$Z2Vortag = 0;
			$Z3Vortag = 0;
			$Z4Vortag = 0;
			$Z5Vortag = 0;
			$Z6Vortag = 0;
			$Z7Vortag = 0;
			
			if(isset($alpk['z1']) && isset($alpk['z2']) && isset($alpk['z3']) && isset($alpk['z4']) && isset($alpk['z5']) && isset($alpk['z6']) && isset($alpk['z7']))
			{
				$z1 = $alpk['z1'];
				$z2 = $alpk['z2'];
				$z3 = $alpk['z3'];
				$z4 = $alpk['z4'];
				$z5 = $alpk['z5'];
				$z6 = $alpk['z6'];
				$z7 = $alpk['z7'];
			}
			
			$waermeZ1 = 0;
			$waermeZ2 = 0;
			$waermeZ3 = 0;
			$waermeZ4 = 0;
			$waermeZ5 = 0;
			$waermeZ6 = 0;
			$waermeZ7 = 0;
		}
		else
		{
			if(isset($alpk['z1']) && isset($alpk['z2']) && isset($alpk['z3']) && isset($alpk['z4']) && isset($alpk['z5']) && isset($alpk['z6']) && isset($alpk['z7']))
			{
				$z1 = $alpk['z1'];
				$z2 = $alpk['z2'];
				$z3 = $alpk['z3'];
				$z4 = $alpk['z4'];
				$z5 = $alpk['z5'];
				$z6 = $alpk['z6'];
				$z7 = $alpk['z7'];
			}
			else
			{
				$z1 = 0;
				$z2 = 0;
				$z3 = 0;
				$z4 = 0;
				$z5 = 0;
				$z6 = 0;
				$z7 = 0;	
			}
			
			$Z1Vortag = $waerme_vortag[0]['z1_ablesetag'];
			$Z2Vortag = $waerme_vortag[0]['z2_ablesetag'];
			$Z3Vortag = $waerme_vortag[0]['z3_ablesetag'];
			$Z4Vortag = $waerme_vortag[0]['z4_ablesetag'];
			$Z5Vortag = $waerme_vortag[0]['z5_ablesetag'];
			$Z6Vortag = $waerme_vortag[0]['z6_ablesetag'];
			$Z7Vortag = $waerme_vortag[0]['z7_ablesetag'];
			
			$waermeZ1 = $z1 - $Z1Vortag;
			$waermeZ2 = $z2 - $Z2Vortag;
			$waermeZ3 = $z3 - $Z3Vortag;
			$waermeZ4 = $z4 - $Z4Vortag;
			$waermeZ5 = $z5 - $Z5Vortag;
			$waermeZ6 = $z6 - $Z6Vortag;
			$waermeZ7 = $z7 - $Z7Vortag;
			
		}
		$DB->query("INSERT INTO waermeerzeugung (date, z1_vortag, z1_ablesetag, z1_waerme, z2_vortag, z2_ablesetag, z2_waerme, z3_vortag, z3_ablesetag, z3_waerme, z4_vortag, z4_ablesetag, z4_waerme, z5_vortag, z5_ablesetag, z5_waerme, z6_vortag, z6_ablesetag, z6_waerme, z7_vortag, z7_ablesetag, z7_waerme) VALUES (".$date.", ".$Z1Vortag.", ".$z1.", ".$waermeZ1.", ".$Z2Vortag.", ".$z2.", ".$waermeZ2.", ".$Z3Vortag.", ".$z3.", ".$waermeZ3.", ".$Z4Vortag.", ".$z4.", ".$waermeZ4.", ".$Z5Vortag.", ".$z5.", ".$waermeZ5.", ".$Z6Vortag.", ".$z6.", ".$waermeZ6.", ".$Z7Vortag.", ".$z7.", ".$waermeZ7.") ");
	}
}

foreach($anlieferung as $anl)
{
	
	$date = $anl['date'];
	$tag = date('j', $date);
	$monat = date('n', $date);
	$jahr = date('Y', $date);
	
	$morgen = mktime(0,0,0,$monat,$tag,$jahr);
	$abend = mktime(23,59,59,$monat,$tag,$jahr);
	
	$vortag_morgen = mktime(0,0,0,$monat,$tag-1,$jahr);
	$vortag_abend = mktime(23,59,59,$monat,$tag-1,$jahr);
	
	//HACKSCHNITZEL UMSCHLAG
	
	$hack = $DB->query("SELECT * FROM hackschnitzel WHERE date >= ".$morgen." && date <= ".$abend." LIMIT 1");
	
	if(empty($hack))
	{
		$wassergehalt = $anl['wassergehalt'];
		$leer = $anl['leer'];
		$beladen = $anl['beladen'];
		$lieferant = $anl['lieferant'];

		echo "WGH=".$wassergehalt." 100-WGH= ".(100-$wassergehalt)." LEER=".$leer." BELADEN=".$beladen." <br>";
		$holzFrisch = $beladen - $leer;
		$holzAtro = $holzFrisch * (100 - $wassergehalt) / 100;
		$menge = $holzAtro / 165;
		$heizwert = $holzFrisch * (((1-$wassergehalt / 100) * 5.1) - ($wassergehalt / 100 * 0.68));
		$co2 = $menge * (295/1000);
		
		$holzFrisch = round($holzFrisch, 0);
		$holzAtro = round($holzAtro, 0);
		$menge = round($menge, 1);
		$heizwert = round($heizwert, 0);
		$co2 = round($co2, 1);
		
		echo "FRISCH=".$holzFrisch." ATRO=".$holzAtro." MENGE=".$menge." <br>";
		$l1 = 0;
		$l2 = 0;
		$l3 = 0;
		$sonstige = 0;
		
		if($lieferant == "Alzinger")
		{
			$l1 = $menge;
		}
		elseif($lieferant == "WBV KEH")
		{
			$l2 = $menge;
		}
		elseif($lieferant == "Euringer")
		{
			$l3 = $menge;
		}
		elseif($lieferant == "Sonstige")
		{
			$sonstige = $menge;
		}
		
		$sql1 = "INSERT INTO hackschnitzel (date, wassergehalt, holzgewicht_frisch, menge, holzgewicht_atro, heizwert, co2, lieferant1, lieferant2, lieferant3, sonstige) VALUES (".$date.", ".$wassergehalt.", ".$holzFrisch.", ".$menge.", ".$holzAtro.", ".$heizwert.", ".$co2.", ".$l1.", ".$l2.", ".$l3.", ".$sonstige.")";
		$DB->query($sql1);
		
		$result = $DB->MySQLiObj->error;
		
		if($result == "")
		{
			$sql2 = "UPDATE anlieferung SET updated=1 WHERE id=".$anl['id']."";
			$DB->query($sql2);
		}
	}
	else
	{
		$i = $hack[0]['lieferungen'];
		$i++;
		echo " I=".$i."<br> ";
		/*if($i==1)
		{
			$heute = $tag;	
			$i++;
		}
		else
		{
			$i++;	
		}
		
		if($heute != $tag)
		{
			$i=1;
		}	*/
		
		$wassergehalt = $anl['wassergehalt'];
		$leer = $anl['leer'];
		$beladen = $anl['beladen'];
		$lieferant = $anl['lieferant'];
		
		echo "WGH=".$wassergehalt." 100-WGH= ".(100-$wassergehalt)." LEER=".$leer." BELADEN=".$beladen." <br>";
		$holzFrisch = $beladen - $leer;
		$holzAtro = $holzFrisch * (100 - $wassergehalt) / 100;
		$menge = $holzAtro / 165;
		$heizwert = $holzFrisch * (((1-$wassergehalt / 100) * 5.1) - ($wassergehalt / 100 * 0.68));
		$co2 = $menge * (295/1000);
		
		$holzFrisch = round($holzFrisch, 0);
		$holzAtro = round($holzAtro, 0);
		$menge = round($menge, 1);
		$heizwert = round($heizwert, 0);
		$co2 = round($co2, 1);
		
		echo "FRISCH=".$holzFrisch." ATRO=".$holzAtro." MENGE=".$menge." <br>";
		$l1 = $hack[0]['lieferant1'];
		$l2 = $hack[0]['lieferant2'] ;
		$l3 = $hack[0]['lieferant3'];
		$sonstige =  $hack[0]['sonstige'];
		
		if($lieferant == "Alzinger")
		{
			$l1 = $l1 + $menge;
		}
		elseif($lieferant == "WBV KEH")
		{
			$l2 = $l2 + $menge;
		}
		elseif($lieferant == "Euringer")
		{
			$l3 = $l3 + $menge;
		}
		elseif($lieferant == "Sonstige")
		{
			$sonstige = $sonstige + $menge;
		}
		
		$id = $hack[0]['id'];
		$holzFrisch = $hack[0]['holzgewicht_frisch'] + $holzFrisch;
		$holzAtro = $hack[0]['holzgewicht_atro'] + $holzAtro;
		$menge =  $hack[0]['menge'] + $menge;
		$heizwert =  $hack[0]['heizwert'] + $heizwert;
		$co2 = $hack[0]['co2'] + $co2;
		$wassergehalt =  ($hack[0]['wassergehalt'] * ($i-1) + $wassergehalt) / $i;
		
		$wassergehalt = round($wassergehalt, 1);
		
		$sql1 = "UPDATE hackschnitzel SET wassergehalt=".$wassergehalt.", holzgewicht_frisch=".$holzFrisch.", menge=".$menge.", holzgewicht_atro=".$holzAtro.", heizwert=".$heizwert.", co2=".$co2.", lieferant1=".$l1.", lieferant2=".$l2.", lieferant3=".$l3.", sonstige=".$sonstige.", lieferungen=".$i." WHERE id=".$id."";
		$DB->query($sql1);
		
		$result = $DB->MySQLiObj->error;
		
		if($result == "")
		{
			$sql2 = "UPDATE anlieferung SET updated=1 WHERE id=".$anl['id']."";
			$DB->query($sql2);
		}
		

	}
	
	echo $sql1."<br> ";
	echo $sql2;
	
}

//header("Location: ../main.php");
?>