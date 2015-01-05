<?php
//Einbinden der Konfigurationsdatei
require_once 'common.php';
require_once 'inc/classes/class.Login.php';

//Backslashes entfernen
System\Security::globalStripSlashes();

//HTML-Dok erstellen
$HTML = new System\HTML;

$HTML->printHead();
$HTML->printBody();

//Inhalt
$LOGIN = new Scripts\Login();
$LOGIN->printLoginForm("checkLogin.php");

$HTML->printFoot();
?>