<?php 
//Namespace der Klasse 
namespace System; 

/** 
 * SessionHandler 
 *  
 */ 
class SessionHandler 
{
	private $DB = null;
	
	/** 
     * Konstruktor 
     */
	
	public function __construct()
	{
		$this->DB = $GLOBALS['DB'];
		
		// Den SessionHandler auf die Methoden 
        // dieser Klasse setzen
		
		session_set_save_handler(array ($this, '_open'),  
                                 array ($this, '_close'), 
                                 array ($this, '_read'), 
                                 array ($this, '_write'),  
                                 array ($this, '_destroy'),  
                                 array ($this, '_gc'));
		
		// Session starten  
        session_start(); 
		
        //session_write_close(); 
        register_shutdown_function('session_write_close');
	}
	
	/** 
     * Öffnen der Session 
     *  
     * @return boolean Gibt immer true zurück 
     */
	 
	public function _open($path, $name) 
	{             
        return true; 
    }
	
	 /** 
     * Session schließen  
     *  
     * @return boolean Gibt immer true zurück 
     */ 
    public function _close() 
	{ 
         
        //Ruft den Garbage-Collector auf. 
        $this->_gc(0); 
        return true; 
		
    }
	
	 /** 
     * Session-Daten aus der Datenbank auslesen 
     *  
     * @return varchar Gibt entweder die Sitzungswerte oder einen leeren String zurück 
     */
	
	public function _read($sesID)
	{
		//Session Daten holen
		$sessionStatement = "SELECT * FROM sessions WHERE id = '$sesID'";
		$result	= $this->DB->query($sessionStatement);
		
		//Datenbankfehler
		if($result === false)
		{
			return '';	
		}
		
		//Datensatz gefunden
		if(count($result) > 0)
		{
			return $result[0]["value"];	
		}
		
		//Kein Datensatz gefunden
		else
		{
			return '';	
		}
	}
	
	 /** 
     * Neue Daten in die Datenbank schreiben 
     *  
     * @param varchar eindeutige Sessionid 
     * @param Array Alle Daten der Session 
     *  
     * @return boolean Gibt den Status des Schreibens zurück 
     */
	 
	 public function _write($sesID, $data)
	 {
		//Nur schreiben, wenn Daten übergeben werden 
        if($data == null) 
        { 
            return true; 
        } 
		
		//Statement, um eine bestehende Session upzudaten
		$sessionStatement = "UPDATE sessions SET lastUpdated='".time()."', value='$data' WHERE id='$sesID'";
		$result = $this->DB->query($sessionStatement);
		
		//Ergebniss prüfen
		if($result === false)
		{
			//Fehler in der Datenbank
			return false;	
		}
		
		if($this->DB->MySQLiObj->affected_rows)
		{
			//Bestehende Session wüeder aktuallisiert
			return true;
		}
		
		//Es besteht keine Session, die Session wird neu erstellt
		$sessionStatement = "INSERT INTO sessions (id, lastUpdated, start, value) VALUES ('$sesID', '".time()."', '".time()."', '$data')";
		$result = $this->DB->query($sessionStatement);

		//Ergebnis zurückgeben
		return $result;
	 }
	 
	 /** 
     * Session aus der Datenbank löschen 
     *    
     * @param varchar eindeutige Session-Nr. 
     *  
     * @return boolean Gibt den Status des Zerstörens zurück 
     */
	 
	 public function _destroy($sesID)
	 {
		$sessionStatement = "DELETE FROM sessions WHERE id='$sesID'";
		$result = $this->DB->query($sessionStatement);
		//Ergebnis zurückgeben (true/false)	
		return $result; 
	 }
	 
    /** 
     * Garbage-Collector
     *  
     * Löscht abgelaufene Sessions aus der Datenbank 
     *  
     * @return boolean Gibt den Status des Bereinigens zurück 
     */
	 
	 public function _gc($life)
	 {
		 //Zeitraum nachdem die Sitzung als abgelaufen gilt
		 // 30 min
		 $sessionLife = strtotime("-30minutes");
		 $sessionStatement = "DELETE FROM sessions WHERE lastUPdated < $sessionLife";
		 
		 $result = $this->DB->query($sessionStatement);
		 
		 //Ergabis zurückgeben
		 return $result;
	 }
}
?>