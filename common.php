<?php
#Projektpfade (Web und lokale Pfade)
include(__DIR__.'/paths.php');

#Datenbanksettings und Systemeinstellungen
require_once PROJECT_DOCUMENT_ROOT.'/Settings.php';

#Alle Basis-Klassen einbinden
require_once PROJECT_DOCUMENT_ROOT."/inc/includeAllClasses.php";

#Datenbankobjekt erstellen (wenn nicht vorhanden)
if(!isset($GLOBALS['DB']))
{
	$DB = new System\Database\MySQL(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);	
}

#global verfügbares Session-Objekt.
new System\SessionHandler();

?>