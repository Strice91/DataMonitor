<?php 

//Fehlerreporting 
error_reporting(E_ALL); 

//DEBUG-MODUS
define('DEBUG',false); 

//DATENBANKVERBINDUNGS-DATEN 
define('DB_SERVER',"localhost"); 
define('DB_PORT',"3306"); 
define('DB_NAME',"management"); 

//Datenbankbenutzer 
define('DB_USER',"root"); 
//Benutzerpasswort 
define('DB_PASSWORD',"root"); 

//HTML-TITEL 
define('HTML_TITLE',"Management"); 

//Zeitzone
date_default_timezone_set('Europe/Berlin'); 

?>