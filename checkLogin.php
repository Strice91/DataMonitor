<?php
require_once "common.php";
require_once "inc/classes/class.Login.php";

//Login-Objekt erstellen
$LOGIN = new Scripts\Login();

//Logindaten überprüfen
$loginOK = $LOGIN->checkLoginData();

//HTML-Dok erstellen
$HTML = new System\HTML();

$HTML->printHead();
$HTML->printBody();


//Wenn logindaten OK
if($loginOK == true)
{
	echo "<span style='color:green;'>\n";
	echo "Sie sind als";
	echo " <b>".$_POST['login']."</b>";
	echo " eingelogt worden!</span>\n";
	echo "<script type='text/javascript'>document.location.href='main.php?content=home';</script>";
}
else
{
	echo "<span style='color:red;'>\n";
	echo "Fehler in Benutzername oder Kennwort. <br />";
	echo "<a href='index.php'>Zum Login-Fromlar</a>";	
}


$HTML->printFoot();
?>