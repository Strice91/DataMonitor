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
	$end = 0;	
}

//Inhalt
$Table = new System\Table;
$Table->create('lieferplan', $jahr);

?>