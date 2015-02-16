<?php
//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();
System\Security::checkAdminAndRedirect(true);

echo '<div style="width: 100%; padding: 20px 0 20px 5px;">';
//Formular erstellen
echo "<form name='insert' method='post' action='".PROJECT_HTTP_ROOT."/scripts/newUser.php'>";
echo	"<table class='form_table' border='0px'>";
echo 		"<tr>";
echo 			"<td>";
echo				"<span style='font-size:0.8em;'>Login: </span>";
echo 			"</td>";
echo 			"<td>";
echo 				"<input size='20' type='text' name='login' value='' maxlength='32'>";
echo 			"</td>";
echo 		"</tr>";
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
echo				"<span style='font-size:0.8em;'>Vorname: </span>";
echo 			"</td>";
echo 			"<td>";
echo 				"<input size='20' type='text' name='prename' value='' maxlength='30'>";
echo 			"</td>";
echo 		"</tr>";
echo 		"<tr>";
echo 			"<td>";
echo				"<span style='font-size:0.8em;'>Nachname: </span>";
echo 			"</td>";
echo 			"<td>";
echo 				"<input size='20' type='text' name='surname' value='' maxlength='30'>";
echo 			"</td>";
echo 		"</tr>";
echo 		"<tr>";
echo 			"<td>";
echo				"<span style='font-size:0.8em;'>Aktiv: </span>";
echo 			"</td>";
echo 			"<td>";
echo 				"<input size='20' type='checkbox' name='active' value='test'>";
echo 			"</td>";
echo 		"</tr>";
echo 		"<tr>";
echo 			"<td>";
echo				"<span style='font-size:0.8em;'>Admin: </span>";
echo 			"</td>";
echo 			"<td>";
echo 				"<input size='20' type='checkbox' name='admin' >";
echo 			"</td>";
echo 		"</tr>";
echo 		"<tr>";
echo 			"<td>";
echo 			"</td>";
echo 			"<td>";
echo 				"<input size='20' type='submit' name='submit' value='Speichern'>";
echo 			"</td>";
echo 		"</tr>";
echo 	"</table>";
echo "</form>";

echo "</div>";

?>
