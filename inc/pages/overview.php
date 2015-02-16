<?php
//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

//Inhalt
$Table = new System\Table;
$table = $Table->loadTable('messages');


echo '<div style="width: 100%; padding: 20px 0 20px 5px;">';
if($table)
{	
	echo '<link href="'.PROJECT_HTTP_ROOT.'/inc/css/table.css" rel="stylesheet" type="text/css" />';
	echo "<table width='500px' class='main_table'>";
	
	echo 	"<tr>";	
	echo		"<th scope='col'>Name</th>";
	echo		"<th scope='col'>Titel</th>";
	echo		"<th scope='col'>Inhalt</th>";
	echo		"<th scope='col'>Stunden</th>";
	echo		"<th scope='col'>Datum</th>";
	echo	 "</tr>";
	
	foreach($table['cont'] as $row)
	{
		$user = new System\User($row['user']);
		$username = "".$user->prename." ".$user->surname."";
		$normalDate = date('d.m.y',$row['date']);
		
		echo "<tr>";
		echo 	"<td>".$username."</td>";
		echo 	"<td>".$row['title']."</td>";
		echo 	"<td>".$row['content']."</td>";
		echo 	"<td>".$row['time']."</td>";
		echo 	"<td>".$normalDate."</td>";
		echo "</tr>";
	}
	
	echo "</table>";
	
}
else
{
	echo 'Tabelle kann nicht gefunden werden!';
}
echo "</div>";

?>
