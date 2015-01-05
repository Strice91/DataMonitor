<?php
//Namespace der Klasse
namespace System;

/**
* Die Soll Klasse
*
*/
 
class Soll
{
	/**
	*	Erstellt eine Sollwerte-Tablle aus vorgegbenen Mustern
	*
	*	@param varchar Typ der Tablle für spezifitationen
	*
	*/
	
	public function create()
	{	
	
		$db_table = $GLOBALS['DB']->query("SELECT * FROM soll ORDER BY tabelle ASC");
		
		//Tabelle erstellen
		echo '<link href="'.PROJECT_HTTP_ROOT.'/inc/css/table.css" rel="stylesheet" type="text/css" />';
		echo "<table class='main_table'>";
		
		echo "<tr>";
		echo 	"<th scope='col'>Tabelle</th>";
		echo 	"<th scope='col'>Jahr</th>";
		echo 	"<th scope='col'>Sollwert</th>";
		echo 	"<th scope='col'></th>";
		echo "</tr>";
		
		//Tabellen einträge erstellen
		foreach($db_table as $values)
		{
			echo "<tr>";
				$i=0;
				foreach($values as $wert)
				{
					if($i > 1)
					{
						echo "<td>".$wert."</td>";	
					}
					$i++;
				}
				echo "<td>".Option::edit($values['id'], "soll")." ".Option::delete($values['id'], "soll")."</td>";
			echo "</tr>";
		}
		
	}
	
	
}