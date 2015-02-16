<?php

//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();
$admin = System\Security::checkAdminAndRedirect();
//HTML-Dok erstellen
$HTML = new System\HTML;

$HTML->printHead();
$HTML->printBody();

//echo "<a href='logout.php'>Logout</a> &nbsp; &nbsp;";
//echo "<a href='scripts/update.php'>Update</a>";
echo '<div id="wrap">';
echo 	'<div id="banner">';
echo		'<div style="width: 100%; overflow: hidden; padding: 20px 0 20px 0px;">';
echo			'<div style="width: 600px; float: left;">';
echo				'<span class=\'heading1\' >Management</span>';
echo			'</div>';
echo			'<div style="margin-right: 40px; float: right;">';
echo				'<a href="logout.php">Logout <img src="images/style/exit_22.png"></a>';
echo			'</div>';
echo		'</div>';
echo		'<div id="menu">';
echo			'<div id="navigation">';
echo				'<ul>';
echo					'<li>';
echo						'<a '.System\Navigation::getregister("home").'>';
echo							'<span>';
echo								'Start <img src="images/menue/start.png" />';
echo							'</span>';
echo						'</a>';
echo					'</li>';
echo					'<li>';
echo						'<a '.System\Navigation::getregister("new").'>';
echo							'<span>';
echo								'Neu <img src="images/menue/new.png" />';
echo							'</span>';
echo						'</a>';
echo					'</li>';
echo					'<li>';
echo						'<a '.System\Navigation::getregister("overview").'>';
echo							'<span>';
echo								'&Uuml;bersicht <img src="images/menue/overview.png" />';
echo							'</span>';
echo						'</a>';
echo					'</li>';
echo					'<li>';
echo						'<a '.System\Navigation::getregister("user").'>';
echo							'<span>';
echo								'Benutzer <img src="images/menue/user.png" />';
echo							'</span>';
echo						'</a>';
echo					'</li>';
echo					'<li>';
echo						'<a '.System\Navigation::getregister("passEdit").'>';
echo							'<span>';
echo								'Passwort Ändern <img src="images/menue/key.png" />';
echo							'</span>';
echo						'</a>';
echo					'</li>';

if($admin){
	echo				'<li>';
	echo					'<a '.System\Navigation::getregister("userNew").'>';
	echo						'<span>';
	echo							'Neuer Benutzer <img src="images/menue/new_user.png" />';
	echo						'</span>';
	echo						'</a>';
	echo				'</li>';
}

echo				'</ul>';
echo			'</div>';
echo		'</div>';
echo	'</div>';
echo	'<div id="container">';
echo		'<div id="content">';
			  include_once(System\Navigation::getpage());
echo		'</div>';
echo	'</div>';
echo '</div>';
	
$HTML->printFoot();

?>