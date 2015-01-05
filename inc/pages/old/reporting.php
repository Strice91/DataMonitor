<?php

//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

// Zeitruam auswählen
if(isset($_POST['sel_jahr']))
{
	$jahr = mysql_real_escape_string($_POST['sel_jahr']);
}
else
{
	$jahr = date('Y',time());	
}
$jahr1 = mktime(0,0,0,1,1,$jahr);
$jahr2 = mktime(23,59,59,12,31,$jahr);

echo '<link href="'.PROJECT_HTTP_ROOT.'/inc/css/table.css" rel="stylesheet" type="text/css" />';

$heading = "Reporting - ".$jahr."";

$namen[1] = "W&auml;rmeerzeugung ORC";
$namen[2] = "W&auml;rmeerzeugung Spitzenlastkessel";
$namen[3] = "W&auml;rmeerzeugung Biomasse";
$namen[4] = "W&auml;rmeerzeugung Gesamt";
$namen[5] = "Netzabgabe Biomasseheizkraftwerk";
$namen[6] = "Netzabgabe Heizzentrale 2";
$namen[7] = "W&auml;rmeabgabe Gesamt";
$namen[8] = "Stromerzeugung ORC-Anlage";
$namen[9] = "Stromerzeugung PV-Anlage BMHKW";
$namen[10] = "Stromerzeugung Gesamt";
$namen[11] = "Einsatz Hackschnitzel";
$namen[12] = "Erdgasbezug Spitzenlastkessel";
$namen[13] = "Strombezug BMHKW";
$namen[14] = "Erdgasbezug Heizzentrale 2";
$namen[15] = "Strombezug Heizzentrale 2";
$namen[16] = "Energiebezug Gesamt";

//Sollwerte


// Werte aus ORC
$orc = $GLOBALS['DB']->query("SELECT * FROM orc WHERE date > ".$jahr1." && date < ".$jahr2."");
$orcValues = array();
foreach($orc as $o)
{
	$mon = date('n', $o['date']);
	if(isset($orcValues[$mon]))
	{
		$orcValues[$mon] = $orcValues[$mon] + $o['energiewert'];
	}
	else
	{
		$orcValues[$mon] = 0;
	}
}



// 2 Dimansionales Werte Array für Tabelle
$Values[1] = $orcValues;



echo "<table width='1000' class='main_table'>";
echo "<tr class='ueberschrift'>
	 	 <td colspan='6'>
		 	<div>
			  ".$heading."
		 	 </div>
		  <div>";
		  echo "".System\Option::select('jahr')."";
echo	" </div>
		  <div>
			  ".System\Option::select('graph', "reporting", $jahr)."
		  </div>
	  	</td>
 	 </tr>";
				
echo "
  <tr>
    <th>Energiebilanz </th>
    <th>Einheit</th>
    <th>Januar</th>
    <th>Februar</th>
    <th>M&auml;rz</th>
    <th>April</th>
    <th>Mai</th>
    <th>Juni</th>
    <th>Juli</th>
    <th>August</th>
    <th>September</th>
    <th>Oktober</th>
    <th>November</th>
    <th>Dezember</th>
    <th>Gesamt IST</th>
    <th>Wirtschaftsplan</th>
  </tr>";
  
for($i=1;$i<=16;$i++)
{
  echo" <tr>
	  <td>".$namen[$i]."</td>
	  <td>kWh</td>";
	  for($j=1;$j<=13;$j++)
	  {
		  //WErt in Zelle eintragen
		  if(isset($Values[$i][$j]))
		  {
			  $wert = $Values[$i][$j];
		  }
		  else
		  {
			  $wert = 0;	
		  }
		  
		  //Wert auf Monatssumme addieren
		  if(isset($Values[16][$j]))
		  {
		  	$Values[16][$j] = $Values[16][$j] + $wert;
		  }
		  else
		  {
			$Values[16][$j] = $wert;  
		  }
		  
		  echo "<td>".$wert."</td>";
		 
	  }
	  
  echo" </tr>";
}

echo "</table>";






?>