<?php
//Einbinden der Konfigurationsdatei
require_once "../common.php";
require_once "../session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

$execute = true;

// Überprüfe Übergabe title
if(isset($_POST['title']) && !empty($_POST['title'])){
	$title = $_POST['title'];
}
else{ // Muss vorhanden sein
	$execute = false;
}

// Überprüfe Übergabe content
if(isset($_POST['content']) && !empty($_POST['content'])){
	$content = $_POST['content'];
}
else{ // Muss vorhanden sein
	$execute = false;
}

// Überprüfe Übergabe time
if(isset($_POST['time']) && !empty($_POST['time'])){
	$time = $_POST['time'];
}
else{ // Falls nicht vorhanden auf 0 setzen
	$time = 0;	
}

// Überprüfe Übergabe datum = (day, month, year)
if(isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])
	&& !empty($_POST['day']) && !empty($_POST['month']) && !empty($_POST['year']))
{
	$date = mktime(0,0,0,intval($_POST['month']),intval($_POST['day']),intval($_POST['year']));
}
else{ // Muss vorhanden sein
	$execute = false;
}


// Sind alle Übergabewerte vorhanden wird das Skript weiter ausgeführt
if($execute){
	// Anlegen einer neuen Nachricht mit den übergebenen Werten
	$msg = new System\Message($_SESSION['userid'],$title,$content,$time,$date);
	// Speichern der Nachricht in der Datenbank
	//$msg->save();
	// Sende der Nachricht als Email
	//$msg->send();
}
else{ // Fehlen benötigte Werte wird auf das Formular zurück geleitet
	echo "schlecht";
}


/*echo "<script type='text/javascript'>document.location.href='".PROJECT_HTTP_ROOT."/main.php?content=home';</script>";	
header("Location: ".PROJECT_HTTP_ROOT."/main.php?content=home");
*/
	 
?>