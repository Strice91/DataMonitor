<?php
//Namespace der Klasse
namespace System;

/**
* Die Table Klasse
*
*/
 
class Table
{
	/**
	 *	Liefert eine Tabelle mit Beschreibung und Inhalt
	 *
	 *	@param varchar Tabellenname
	 *	@param array Abzurufende Inhalte / Spalten ['col1', 'col2', ...]
	 *	@param int Limit der abzurufenden Zeilen
	 *	@param assoziatives array Spalten nach denen geordnet werden soll und die Reihenfolge
	 *			['col1' => 'DESC', 'col2' => 'ASC', ...]
	 */
	
	public function loadTable($table, $content=false, $limit=false, $order=false)
	{
		// Lese den Inhalt aus der Datenbank
		$tableCont = Table::readTable($table);
		// Hole Beschreibung der Tabelle
		$tableDesc = Table::getDescription($table);
		
		if(!empty($tableCont) && !empty($tableDesc))
		{	// Rückgabe als assoziatives Array
			$return['cont'] = $tableCont;	// Inhalt
			$return['desc'] = $tableDesc;	// Beschreibung
			return $return;
		}
		else
		{	// Im Fehlerfall wird false zurückgegeben
			return false;
		}
	}
	
	/**
	 *	Lädt eine Tabelle aus der Datenbank nach vorgegebenen Parametern
	 *
	 *	@param varchar Tabellenname
	 *	@param array Abzurufende Inhalte / Spalten ['col1', 'col2', ...]
	 *	@param int Limit der abzurufenden Zeilen
	 *	@param assoziatives array Spalten nach denen geordnet werden soll und die Reihenfolge
	 *			['col1' => 'DESC', 'col2' => 'ASC', ...]
	 */
	
	private function readTable($table, $content=false, $limit=false, $order=false)
	{
		// Auswahl der Spalen die zu holen sind
		if(empty($content) || $content == false)
		{	// Keine Spalte spezifiziert -> hole alle
			$select = '*';
		}
		else if($content == '*')
		{	// Alle Spalten ausgewählt -> füge kein `*` hinzu für korrekte Syntax
			$select = '*';
		}
		else
		{	// Füge die ausgewählten Spalten zu einen SQL String zusammen
			// "`col1`,`col2`,...,`colN`"
			$select = '`';
			$select .= implode('`,`',$content);
			$select .= '`';
		}
		// SELECT Statement mit den ausgewählten Spalten
		$sql = "SELECT ".$select." ";
		$sql .= "FROM `".$table."` ";
		
		// Sortierung der Abfrage
		if($order)
		{	// Ist ein ORDER Parameter gesetzt wird ORDER BY angefügt
			$sql .= "ORDER BY ";
			foreach($order as $col => $ordDir)
			{	// Aufschlüüseln des assoziativen Arrays
				// ['col1' => 'DESC', 'col2' => 'ASC'] -> "`col1` DESC, `col2` ASC" 
				$sql .= "`".$col."` ".$ordDir.", ";
			}
			$sql = rtrim($sql, ", ");	// Entfernen des letzten Kommas
			$sql .= " ";
		}
		
		// Limit der abzurufenden Zeilen
		if($limit)
		{	// Ist der LIMIT Paraemter gesetzt wird ein LIMIT angefügt
			$sql .= "LIMIT ".$limit." ";
		}
		
		//echo "|".$sql."|";
		// Ausführen des zusammengebauten Query Strings
		$result = $GLOBALS['DB']->query($sql); 
		return $result;
	}
	
	/**
	 *	Holt die Beschreibung einer Tabelle
	 *
	 *	@param varchar Tabellenname
	 *	@return array Array mit den Inhalten 'Field', 'Type', 'Null', 'Key', 'Default', 'Extra'
	 *				  Möchte man die Namen der Spalten haben ließt man den Index 'Field'.
	 *				  In jedem Schlüssel findet nummerische Arrays mit der Spezifikation der 
	 *				  gewünschten Spalte [0] => name1, [1] => name2, ...
	 */
	
	private function getDescription($table)
	{
		// Beschreibung der Tabelle aus der Datenbank holen
		$desc = $GLOBALS['DB']->describe($table);
		// Bearbeitung bei gefundener Tabelle
		if(!empty($desc)){
			// Anlegen der Beschreibungs Kathegorien
			$return['Field'] = array();
			$return['Type'] = array();
			$return['Null'] = array();
			$return['Key'] = array();
			$return['Default'] = array();
			$return['Extra'] = array();
			
			// Auffüllen der Kathegorien mit den Spezifikationen
			// ['Field'] => ([0] => name1, [1] => name2, ...)
			// ['Type'] => ([0] => text, [1] => int, ...)
			foreach($desc as $col){
				array_push($return['Field'],$col['Field']);
				array_push($return['Type'],$col['Type']);
				array_push($return['Null'],$col['Null']);
				array_push($return['Key'],$col['Key']);
				array_push($return['Default'],$col['Default']);
				array_push($return['Extra'],$col['Extra']);
			}
			return $return;
		}
		else
		{	// Die Tabelle wurde nicht gefunden
			return false;
		}
	}
}
?>