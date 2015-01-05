<?php

//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

//HTML-Dok erstellen
$HTML = new System\HTML;

$HTML->printHead();
$HTML->printBody();

//echo "<a href='logout.php'>Logout</a> &nbsp; &nbsp;";
//echo "<a href='scripts/update.php'>Update</a>";
echo '<div id="wrap">
		<div id="banner">
			<div style="width: 100%; overflow: hidden; padding: 20px 0 20px 25px;">
				<div style="width: 600px; float: left;">
					<span class=\'heading1\' >Management</span>
				</div>
				<div style="margin-right: 40px; float: right;">
					<a href="logout.php">Logout <img src="images/style/exit_22.png"></a>
				</div>
			</div>
			<div id="menu">
				<div id="navigation">
					<ul>
					
					<li><a '.System\Navigation::getregister("home").'><span>Start</span></a></li>
					<li><a '.System\Navigation::getregister("new").'><span>Neu</span></a></li>
					<li><a '.System\Navigation::getregister("overview").'><span>&Uuml;bersicht</span></a></li>
					<li><a '.System\Navigation::getregister("user").'><span>Benutzer</span></a></li>
					
					</ul>
				</div>
			</div>
			</div>
		<div id="container">
  			<div id="content">';
				include_once(System\Navigation::getpage());
echo'		</div>
		</div>
	</div>';
	
$HTML->printFoot();

?>