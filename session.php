<?php
//Anzeige der Seite nur mit gültiger Session
if(!System\Security::checkLoginStatus())
{
	header("Location: index.php");
	exit;
}
?>