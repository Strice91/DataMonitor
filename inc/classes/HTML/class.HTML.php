<?php
//Namespace der Klasse
namespace System;

/**
* Die HTML Klasse
*
*/
 
class HTML
{
	/**
	* HTML-Doukmentkopf erstellen
	*
	*/
	 
	 public static function printHead()
	 {
		//Workaround UTF-8 codierung 
		header('Content-Type: text/html; charset=UTF-8');
		//Head ausgeben
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
		echo '<html>';
		echo '<head>';
		echo '<title>'.HTML_TITLE.'</title>';
		
		//Favicon einbinden
		echo '<link rel="shortcut icon" href="'.PROJECT_HTTP_ROOT.'/images/favicon.ico" type="image/x-icon">';
		
		//Stylsheet einbinden
		echo '<link href="'.PROJECT_HTTP_ROOT.'/inc/css/login.css" rel="stylesheet" type="text/css" />';
		echo '<link href="'.PROJECT_HTTP_ROOT.'/inc/css/default.css" rel="stylesheet" type="text/css" />';
		echo '<mets http-equiv="content-type" content="text/html; charset=UTF-8">'."\n";

		echo '<script src="'.PROJECT_HTTP_ROOT.'/inc/js/default.js" type="text/javascript"></script>';
	 }
	 
	 
	 /** 
     * Erstellt den 'Körper' eines HTML-Dokuments. 
     *  
     * @param varchar Zusätzliche Cascading Stylesheets 
     */
	 
	 public static function printBody($css = NULL, $withConsole = true)
	 {
		echo '</head>'."\n"; 
        echo '<body'; 
        if ($css != null) 
        { 
            echo ' style="'.$css.'"'; 
        } 
        echo '>'."\n";
		
		//DebugConsole einbinden
		//if($withConsole AND DEBUG)DebugConsole::displayConsole();
	 }
	 
	 
	  /** 
     * HTML-Dokument beenden
     *  
     */
	 
	 public static function printFoot() 
     { 
        echo '</body></html>'; 
     }
	 
	/** 
     * Erstellt eine Überschrift
     *  
     * @param varchar Überschrifttext 
     * @param boolean Mit oder ohne DEBUG-Leiste 
     */ 
	 
    public static function printHeadline($headline) 
    { 
             
        echo '<div class="headline">'; 
        echo $headline; 
        echo '</div>'; 

    }
	
	
}
?>