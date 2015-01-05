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
	foreach($table['desc']['Field'] as $name)
	{
		echo "<th scope='col'>".$name."</th>";
	}
	echo	 "</tr>";
	
	foreach($table['cont'] as $row)
	{
		echo "<tr>";
		foreach($row as $col)
		{
			echo "<td>";
			echo $col;
			echo "</td>";
		}
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
