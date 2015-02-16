<?php
//Einbinden der Konfigurationsdatei
require_once "../common.php";
require_once "../session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();
System\Security::checkAdminAndRedirect(true);

$execute = true;

// Überprüfe Übergabe login
if(isset($_POST['login']) && !empty($_POST['login'])){
	$login = $_POST['login'];
}
else{ // Muss vorhanden sein
	$execute = false;
}

// Überprüfe Übergabe password
if(isset($_POST['password']) && !empty($_POST['password'])){
	$password = md5($_POST['password']);
}
else{ // Muss vorhanden sein
	$execute = false;
}

// Überprüfe Übergabe prename
if(isset($_POST['prename']) && !empty($_POST['prename'])){
	$prename = $_POST['prename'];
}
else{ // Muss vorhanden sein
	$execute = false;
}

// Überprüfe Übergabe surname
if(isset($_POST['surname']) && !empty($_POST['surname'])){
	$surname = $_POST['surname'];
}
else{ // Falls nicht vorhanden auf 0 setzen
	$execute = false;
}

// Überprüfe Übergabe actvie
if(isset($_POST['active'])){
	$active = 1;
}
else{ // Falls nicht vorhanden auf 0 setzen
	$active = 0;
}

// Überprüfe Übergabe admin
if(isset($_POST['admin'])){
	$admin = 1;
}
else{ // Falls nicht vorhanden auf 0 setzen
	$admin = 0;
}




// Sind alle Übergabewerte vorhanden wird das Skript weiter ausgeführt
if($execute){
	$sql = "INSERT INTO `users` (`login`, `password`, `prename`, `surname`, `active`, `admin`) 
			VALUES ('".$login."','".$password."','".$prename."','".$surname."','".$active."','".$admin."');";
	$GLOBALS['DB']->query($sql);
}
else{ // Fehlen benötigte Werte wird auf das Formular zurück geleitet
	echo "schlecht";
}


/*echo "<script type='text/javascript'>document.location.href='".PROJECT_HTTP_ROOT."/main.php?content=home';</script>";	
header("Location: ".PROJECT_HTTP_ROOT."/main.php?content=home");
*/
	 
?>