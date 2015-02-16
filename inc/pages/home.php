<?php
//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

echo '<link href="'.PROJECT_HTTP_ROOT.'/inc/css/tutorial.css" rel="stylesheet" type="text/css" />';

//Inhalt
echo "<div class='main_tut'>";
echo 	"<div class='tut'> 
			<span>Um einen Neuen Eintrag anzulegen klicken Sie auf den Neu-Button in der Navigationsleiste.</span>
		</div>";
echo 	"<div class='tut_img'>
			<img src='images/tutorial/new_button.png' style='border:solid 1px black;' />
		</div>";
echo 	"<div class='tut'>
			<span>
				Im Bereich &Prime;Neu&Prime; können sie einen neuen Stundeneintrag machen. Klicken Sie auf Speichern
				wird Eintrag in der Datenbank gespeichert und als Mail verschickt.
			</span>
		</div>";
echo 	"<div class='tut'>
			<img src='images/tutorial/new.png' style='border:solid 1px black;' />
		</div>";		
echo "</div>";

?>
