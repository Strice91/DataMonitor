<?php
//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

$year = date('Y');
$month = date('n');
$day = date('j');

echo '<link href="'.PROJECT_HTTP_ROOT.'/inc/css/form.css" rel="stylesheet" type="text/css" />';

echo '<div style="width: 100%; padding: 20px 0 20px 5px;">';
//Formular erstellen
echo "<form name='insert' method='post' action='".PROJECT_HTTP_ROOT."/scripts/newMessage.php'>";
//echo	"<input type='hidden' name='user' id='user' value=".$_SESSION['userid'].">";
echo	"<table class='form_table' border='0px'>";
echo 		"<tr>";
echo 			"<td colspan='2'>";
echo				"<div class='form_input'>";
echo					"<img src='images/style/title.png' /> &nbsp;";
echo					"<span style='font-size:0.8em;'>Titel: </span></br>";
echo 					"<input size='40' type='text' name='title' value='Titel'>";
echo				"</div>";
echo				"<div class='form_input'>";
echo					"<img src='images/style/user.png' /> &nbsp;";
echo					"<span style='font-size:0.8em;'>Mitarbeiter: </span></br>";
echo 					"<input size='40' type='text' name='worker' value='Mitarbeiter'>";
echo				"</div>";
echo 			"</td>";
echo			"<td>";
echo				"<img src='images/style/new_48.png' /> &nbsp;";
echo 			"</td>";
echo 		"</tr>";
echo 		"<tr>";
echo			"<td colspan='3'>";
echo				"<div class='form_input'>";
echo				"<img src='images/style/content.png' /> &nbsp;";
echo				"<span style='font-size:0.8em;'> Beschreibung: </span></br>";
echo				"<textarea name='content' cols='50' rows='10'>Das ist der Inhalt!</textarea>";		
echo				"</div>";	
echo 			"</td>";
echo 		"</tr>";
echo 		"<tr>";
echo			"<td>";		
echo				"<img src='images/style/time.png' /> &nbsp;";
echo				"<span style='font-size:0.8em;'>Stunden:&nbsp;</span>";
echo				"<input size='3' type='text' name='time' value='3'>";			
echo 			"</td>";
echo			"<td>";		
echo				"<img src='images/style/date.png' /> &nbsp;";
echo				"<span style='font-size:0.8em;'>Datum:&nbsp;</span>";
echo				"<input size='2' type='text' name='day' value='".$day."'>.";	
echo				"<input size='2' type='text' name='month' value='".$month."'>.";	
echo				"<input size='3' type='text' name='year' value='".$year."'>";		
echo 			"</td>";
echo			"<td>";
echo				"<input type='submit' name='submit' value='Speichern'>";
echo 			"</td>";
echo 		"</tr>";
echo 	"</table>";
echo "</form>";

echo "</div>";

?>

<div style="padding-bottom:"