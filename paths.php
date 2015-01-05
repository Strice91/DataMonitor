<?php
//Definieren des Systempfades
define('PROJECT_DOCUMENT_ROOT',__DIR__);

//Projektname
$project = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace("\\", "/",__DIR__));

//Protocoll der Verbindung (HTTP oder HTTPS)
(!isset($_SERVER['HTTPS']) OR $_SERVER['HTTPS'] == 'off') ? $protocol = 'http://' : $protocol = 'https://';

//PROJCET Pfad (WEB)
define('PROJECT_HTTP_ROOT', $protocol.$_SERVER['HTTP_HOST'].$project);
?>