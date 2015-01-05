<?php
//Namespace der Klasse
namespace System\Database;

/**
* Verbindung zur Datenbank und Methoden
*
*/

class MySQL
{
	//Datenbakverbindungsobjekt
	public $MySQLiObj = null;
	
	//Letzte SQL-Abfrage
	public $lastSQLQuery = null;
	
	//Status der letzten SQL-Abfrage
	public $lastSQLStatus = null;
	
	
	/** 
     * Verbindet zur Datenbank aufbauen,
     * gibt ggf. eine Fehlermeldung zurück. 
     *  
     */
	 
	 public function __construct($server, $user, $password, $db, $port = '3306')
	 {
		 //MySQLi-Objekt erstellen
		 $this->MySQLiObj = new \mysqli($server.":".$port, $user, $password, $db);
		 
		 //Prüfen ob ein Fehler aufgetreten ist
		 if(mysqli_connect_errno())
		 {
				 echo "Keine Verbindung zum MySQL-Server möglich.";
				 trigger_error("MySQL-Connction-Error", E_USER_ERROR);
				 die();
		 }
		 
		 //Characterset der Verbindung setzten
		 $this->query("SET NAMES utf8");
	 }
	 
	 
	/** 
     * Beendet die Verbindung zur Datenbank bei Beenden eines 
     * Skriptes. 
     *  
     */
	 
	public function __destruct()
	{
		$this->MySQLiObj->close();	
	}
	
	/** 
     * Führt eine SQL-Anfrage durch. 
     *  
     * Der optionale Parameter bestimmt, ob das Ergebnis als 
     * Array-Struktur zurückgegeben wird oder als normales MySQL-Resultset 
     *  
     * @param text Die SQL-Anfrage 
     * @param boolean Parameter, ob ein Resultset oder ein Array zurückgegeben werden soll 
     *  
     * @return Array Gibt eine Ergebnismenge zurück 
     */
	 
	 public function query($sqlQuery, $resultset = false)
	 {
		 //Letzte SQL Abfrage aufzeichnen
		 $this->lastSQLQuery = $sqlQuery;
		 
		 $result = $this->MySQLiObj->query($sqlQuery);

		 //Ergebis als Array oder plain zurückgeben
		 if($resultset == true)
		 {
			//Status setzen
			if($result == false)
			{
				$this->lastSQLStatus = false;	
			}
			else
			{
				$this->lastSQLStatus = true;	
			}
			
			return $result;
		 }
		 
		 $return = $this->makeArrayResult($result);
		 
		 return $return;
	 }
	 
	 /** 
      * Führt eine SQL-Anfrage durch. 
      *  
      * Der optionale Parameter bestimmt, ob das Ergebnis als 
      * Array-Struktur zurückgegeben wird oder als normales MySQL-Resultset 
      *  
      * @param text Die SQL-Anfrage 
      * @param boolean Parameter, ob ein Resultset oder ein Array zurückgegeben werden soll 
      *  
      * @return Array Gibt den ersten Treffer aus
      */
	 
	 public function query_first($sqlQuery, $limit=0, $offset=0, $resultset = false)
	 {
		 // Prüfe wie viele Zeilen ausgegeben werden sollen
		 if($limit!=0) $sqlQuery.=" LIMIT $offset, $limit";
		 
		 //Letzte SQL Abfrage aufzeichnen
		 $this->lastSQLQuery = $sqlQuery;
		 
		 $result = $this->MySQLiObj->query($sqlQuery);
		 
		 //Ergebis als Array oder plain zurückgeben
		 if($resultset == true)
		 {
			//Status setzen
			if($result == false)
			{
				$this->lastSQLStatus = false;	
			}
			else
			{
				$this->lastSQLStatus = true;	
			}
			
			return $result;
		 }
		 
		 $return = $this->makeArrayResult($result);
		 
		 return $return;
	 }
	 
	/** 
     * Fehlermeldung der letzten Abfrage 
     *  
     * @return varchar Die letzte Fehlermeldung wird zurückgegeben 
     */
	 
	public function lastSQLError() 
    { 
	
        return $this->MySQLiObj->error; 
		
    }
	
	/** 
     * Maskiert einen Parameter für die Benutzung in einer SQL-Anfrage 
     *  
     * @param varchar Attributwert 
     *  
     * @return Gibt den Übergebenen Wert maskiert zurück 
     */
	 
	public function escapeString($value) 
    { 
        return $this->MySQLiObj->real_escape_string($value); 
    }
	
	 /** 
      * Array-Struktur der Anfrage 
      *  
      * Lässt ein Ergebnis aussehen, wie das von DBX 
      *  
      * @param MySQLiObject Das Ergebnisobjekt einer MySQLi-Anfrage  
      *  
      * @param boolean/Array Gibt entweder true, false oder eine Ergebnismenge zurück 
      */
	 
	 private function makeArrayResult($ResultObj)
	 {
		if($ResultObj === false)
		{	//Es trat ein Fehler auf
			$this->lastSQLStatus = false;
			return false;	
		}
		else if($ResultObj === true)
		{	//TRUE setzen
			$this->lastSQLStatus = true;
			return true;
		}
		else if(($ResultObj->num_rows) == 0)
		{	//Anfrage liefert leere Rückgabe
			$this->lastSQLStatus = true;
			$array = array();
			return $array;	
		}
		else
		{
			$array = array();
			while($line = $ResultObj->fetch_array(MYSQL_ASSOC))	
			{	//Alle Bezeichner in $line kleinschreiben
				array_push($array, $line);
			}
			
			//Status der Abfrage setzen
			$this->lastSQLStatus = true;
			
			//Array zurückgeben
			return $array;
		}
	 }
	 
	 /** 
      * Ruft die Beschreibung einer Tabelle ab
      *  
      * @param varcahr $table Name der Tabelle 
	  *
	  * @return varchar Beschreibung der Tabelle
      */
	 public function describe($table)
	 {
		 $sql = "DESCRIBE ".$this->escapeString($table)."";
		 $description = $this->query_first($sql);
		 return $description;
	 }
}

?>