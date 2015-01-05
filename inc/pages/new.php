<?php
//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

$year = date('Y');
$month = date('n');
$day = date('j');

echo '<div style="width: 100%; padding: 20px 0 20px 5px;">';

//Formular erstellen
echo "<form name='insert' method='post' action='".PROJECT_HTTP_ROOT."/scripts/newMessage.php'>";
//echo	"<input type='hidden' name='user' id='user' value=".$_SESSION['userid'].">";
echo	"<table class='form_table' border='0px'>";
echo 		"<tr>";
echo 			"<td colspan='2'>";
echo				"<span style='font-size:0.8em;'>Titel:</span></br>";
echo 				"<input size='50' type='text' name='title' id='title' value='Titel'>";
echo 			"</td>";
echo 		"</tr>";
echo 		"<tr>";
echo			"<td colspan='2'>";
echo				"<span style='font-size:0.8em;'>Beschreibung:</span></br>";
echo				"<textarea name='content' id='content' cols='50' rows='10'>Das ist der Inhalt!</textarea>";					
echo 			"</td>";
echo 		"</tr>";
echo 		"<tr>";
echo			"<td>";		
echo				"<span style='font-size:0.8em;'>Stunden:&nbsp;</span>";
echo				"<input size='3' type='text' name='time' id='time'  value='3'>";			
echo 			"</td>";
echo			"<td>";		
echo				"<span style='font-size:0.8em;'>Datum:&nbsp;</span>";
echo				"<input size='2' type='text' name='day' id='day' value='".$day."'>.";	
echo				"<input size='2' type='text' name='month' id='month' value='".$month."'>.";	
echo				"<input size='3' type='text' name='year' id='year' value='".$year."'>";		
echo 			"</td>";
echo 		"</tr>";
echo		"<tr>";
echo 			"<td colspan='2' align='right'>";
echo				"<input type='submit' name='submit' id='submit' value='Speichern'>";
echo 			"</td>";
echo 		"</tr>";
echo 	"</table>";
echo "</form>";

echo "</div>";

?>
