<?php
//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

echo '<div style="width: 100%; padding: 20px 0 20px 5px;">';
//Formular erstellen
echo "<form name='insert' method='post' action='".PROJECT_HTTP_ROOT."/scripts/editPass.php'>";
echo	"<table class='form_table' border='0px'>";
echo 		"<tr>";
echo 			"<td>";
echo				"<span style='font-size:0.8em;'>Passwort: </span>";
echo 			"</td>";
echo 			"<td>";
echo 				"<input size='20' type='password' name='password' value='' maxlength='32'>";
echo 			"</td>";
echo 		"</tr>";
echo 		"<tr>";
echo 			"<td>";
echo				"<span style='font-size:0.8em;'>Passwort wiederholen: </span>";
echo 			"</td>";
echo 			"<td>";
echo 				"<input size='20' type='password' name='password2' value='' maxlength='32'>";
echo 			"</td>";
echo 		"</tr>";
echo 		"<tr>";
echo 			"<td>";
echo 			"</td>";
echo 			"<td>";
echo 				"<input size='20' type='submit' name='submit' value='Ändern'>";
echo 			"</td>";
echo 		"</tr>";
echo 	"</table>";
echo "</form>";

echo "</div>";

?>
