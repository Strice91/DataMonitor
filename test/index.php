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
?>



<div id="banner">

<div id="ueberschrift"><h1>Biomasse Heizkraftwerk</h1> 

<div id="top">

<div id="navigation">

<ul>

<li><a href="" title=""><span>Home</span></a></li>

<li><a href="" title="" class="current"><span>About Us</span></a></li>

<li><a href="" title="" class=""><span>Services</span></a></li>

<li><a href="" title=""><span>Our Work</span></a></li>

<li><a href="" title=""><span>Contact Us</span></a></li>

</ul>

</div></div>
</div>
<div id="container">
  <div id="content">
    
  <h2>Welcome to <span style="font-weight:bold; color:#4592BE;">Chesspiece 2</span> Template</h2>

<p>inhalt1 </p>

<p>Inhalt2</p> 

</div>

</div></div>
	
<?php
$HTML->printFoot();
?>