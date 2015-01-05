<?php
//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

// Wenn eine Tabelle ausgewählt ist Eingabe Fromluar erzeugen
if(isset($_POST['sel_table']))
{
	$sel = $_POST['sel_table'];
	$create = 1;
	$TABLE = new System\Table;
	$name = $TABLE->tabledata($sel);
	$heading = "Neuer Eintrag in: ".$name['head'];
}
else // Keine tabelle ausgewählt
{
	$heading = "Tabelle auswählen";
	$create = 0;
}


echo "<h2>".$heading."</h2>";
if($create == 1)
{
	$Form = new System\Form;
	$Form->create($sel, $values = array());
}
else
{
	echo System\Option::select("tables");	
	echo "<br>";
	echo "<br>";
	echo "<h2>Sollwerte</h2>";
	
	$SOLL = new System\Soll;
	$SOLL->create();
}


?>
