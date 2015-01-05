<?php
//Einbinden der Konfigurationsdatei
require_once "common.php";
require_once "session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

$heading = "Eintrag bearbeiten";
$table = $_GET['table'];
$id = $_GET['id'];
$db_table_data = $GLOBALS['DB']->query_first(mysql_real_escape_string("SELECT * FROM ".$table." WHERE id=".$id.""), 1);

if(!empty($db_table_data))
{
	//MySQL angabe in Array umwandeln
	foreach($db_table_data as $v)
	{
		foreach($v as $w)
		{
			$values[] = $w;
		}
	}
	
	$create = true;
}
else
{
	$create = false;
}
			
echo "<h2>".$heading."</h2>";

if($create)
{
	$Form = new System\Form;
	$Form->create($table, $values);
}
else
{
	echo "Eintrag nicht gefunden!";	
}


?>
