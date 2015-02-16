<?php 
//Namespace der Klasse 
namespace System; 

/** 
 * Sicherheitsfunktionen 
 *  
 *  
 */
 
class Security
{
	/** 
    * Diese Methode korrigiert alle übergebenen Parameter (Slashes) 
    *  
    * Egal ob POST oder GET-Parameter, die Methode berichtigt beide Arrays und 
    * entfernt die Slashes, die durch PHP automatisch eingefügt wurden. 
    *  
    */
	
	public static function globalStripSlashes()
	{
		if(get_magic_quotes_gpc() == 1)
		{
			$_GET 		= array_map(array ('self', 'recursiveStripSlashes'), $_GET);
			$_POST 		= array_map(array ('self', 'recursiveStripSlashes'), $_POST);
			$_SESSION 	= array_map(array ('self', 'recursiveStripSlashes'), $_SESSION);
			$_COOKIE 	= array_map(array ('self', 'recursiveStripSlashes'), $_COOKIE);
			$_REQUEST 	= array_map(array ('self', 'recursiveStripSlashes'), $_REQUEST);
		}
	}
	
	 /** 
     * Rekursive Hilfsfunktion zur Entfernung von Backslashes 
     *  
     * @param varchar Wert, dessen Slashes entfernt werden sollen 
     *  
     * @return Gibt den übergebenen Wert ohne Slashes zurück 
     */
	 
	private static function recursiveStripSlashes($value)
	{
		//Prüfe ob Wert ein Array ist
		if(is_array($value))
		{
			
			//Rekursiver Aufruf
			return array_map(array ('self', 'recursiveStripSlashes'), $value);
				
		}
		else
		{
			//Rückgabe des Berichtigten Wertes
			return stripslashes($value);
		}
	}
	
	 /** 
     * Überprüft, ob ein Benutzer angemeldet ist. 
     *  
     * @return boolean Loginstatus ist true oder false 
     */
	 
	 public static function checkLoginStatus()
	 {
		if(isset($_SESSION['login'])) 
		{
			return true;
		}
		else
		{
			return false;	
		}
	 }
	 
	 public static function checkAdminAndRedirect($redirect = false){
		if($_SESSION['admin'] == 1){
			return true;
		}
		else {
			if($redirect){
				echo "<script type='text/javascript'>document.location.href='".PROJECT_HTTP_ROOT."/main.php?content=home';</script>";	
				header("Location: ".PROJECT_HTTP_ROOT."/main.php?content=home");
			}
			else{
				return false;
			}
		}
	 }
}
 
?>