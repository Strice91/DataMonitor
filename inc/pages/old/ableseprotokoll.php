<?php
//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

// Zeitruam auswählen
if(isset($_POST['sel_start']) && isset($_POST['sel_end']))
{
	$start = mysql_real_escape_string($_POST['sel_start']);
	$end = mysql_real_escape_string($_POST['sel_end']);
}
else
{
	$start = 0;
	$end = 0;	
}

//Inhalt
$Table = new System\Table;
$Table->create('ableseprotokoll', $start, $end);

?>