<?php
//Einbinden der Konfigurationsdatei
require_once "../common.php";
require_once "../session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

$execute = true;

// Überprüfe Übergabe password
if(isset($_POST['password']) && !empty($_POST['password'])){
	$password1 = $_POST['password'];
}
else{ // Muss vorhanden sein
	$execute = false;
}

// Überprüfe Übergabe password
if(isset($_POST['password2']) && !empty($_POST['password2'])){
	$password2 = $_POST['password2'];
}
else{ // Muss vorhanden sein
	$execute = false;
}

if($password1 == $password2){
	$password = md5($password1);	
}
else{ // Müssen übereinstimmen
	$execute = false;	
}

// Sind alle Übergabewerte vorhanden wird das Skript weiter ausgeführt
if($execute){
	$userid = $_SESSION['userid'];
	$sql = "UPDATE `users` SET `password` = '".$password."' WHERE `ID` = '".$userid."'";
	$GLOBALS['DB']->query($sql);
}
else{ // Fehlen benötigte Werte wird auf das Formular zurück geleitet
	echo "schlecht";
}


/*echo "<script type='text/javascript'>document.location.href='".PROJECT_HTTP_ROOT."/main.php?content=home';</script>";	
header("Location: ".PROJECT_HTTP_ROOT."/main.php?content=home");
*/
	 
?>