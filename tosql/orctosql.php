<?php


          // Definition der Datenbanken und csv-Dateien
			$select_db = 0;
          $db_MenuTitle[0]  = "Eintrag 1";         // Bezeichnung des Eintrags - erscheint im Dropdown
          $db_Hostname[0]   = "localhost";         // Datenbank-Host (muss nicht zwingend immer localhost sein)
          $db_UserName[0]   = "root";                  // Benutzername f&uuml;r diese Datenbank
          $db_Password[0]   = "";                  // Zugehoeriges Passwort
          $db_Database[0]   = "bmhkw";                  // Datenbank, auf die zugegriffen werden soll
          $db_Table[0]      = "orc";                  // Table, in den die CSV-Datei &uuml;bertragen werden soll
          $db_File[0]       = "orc.csv";                  // Verzeichnispfad zur Textdatei (CSV etc.) auf dem Webserver
          $db_Terminated[0] = ";";                 // Trennzeichen, das in der Textdatei verwendet wird




    if (isset ($select_db)) {


            // Connect zur Datenbank
            mysql_connect($db_Hostname[$select_db], $db_UserName[$select_db], $db_Password[$select_db]) || die("Can't Connect to Database: ".mysql_error());
            mysql_select_db($db_Database[$select_db]);

            


			$row = 1;                                     					// Anzahl der Arrays
			$handle = fopen ("orc.csv","r");             					// Datei zum Lesen öffnen
			while ( ($data = fgetcsv ($handle, 1000, ";")) !== FALSE ) { 	// Daten werden aus der Datei
                                               								// in ein Array $data gelesen
 			  	$num = count ($data);                     					// Felder im Array $data
                                               								// werden gezählt
   				$row++; 
				if(($data[0] != null) && ($data[1] != null))
				{
				  $datum = explode('.', $data[0]);
				  $jahr = $datum[2];
				  $monat = $datum[1];
				  $tag = $datum[0];
				  
				  $zeit = explode(':', $data[1]);
				  $stunde = $zeit[0];
				  $minute = $zeit[1];
				  
				  $date = mktime($stunde, $minute, 0, $monat, $tag, $jahr);
				}
				else
				{
					$date = "";	
				}
				
				echo "Datum:".$date." ";
				echo "Vortag".$data[2]." ";
				echo "Ablesetag".$data[3]." ";
				echo "ew:".$data[4];                     					
                // CSV-Datei in die Datenbank &uuml;bertragen
            	$sql = "INSERT INTO orc SET date='".$date."', vortag='".$data[2]."', ablesetag='".$data[3]."', energiewert='".$data[4]."'";

           		 // MySQL-Statements ausf&uuml;hren
            	if (mysql_query ($sql)) 
				{
                	$message = "&Uuml;bertragung erfolgreich";
                }
            	else 
				{
                	$message = "&Uuml;bertragung fehlgeschlagen. Grund: ". mysql_error ();
                }                               								

				echo "<br>";
				
			}
			fclose ($handle);

			echo $message;
            

			
           }
			





/*
?>



<html>
  <head>
    <title>CSV to SQL</title>
  </head>
  <body bgcolor="#EAEAEA">
    <form action="<?php echo $PHP_SELF; ?>" method="POST">
      <table border="0" cellspacing="0" cellpadding="5" bgcolor="#C0C0C0" width="50%">
        <tr>
          <th>CSV to MySQL</th>
          <th>&nbsp;</th>
        </tr>
        <tr valign="bottom">
          <td>
            <select name="select_db" size="<?php echo count ($db_MenuTitle); ?>">
              <?php generate_dropdown (); ?>
            </select>
          </td>
          <td>
            <input type="Submit" name="submit" value="Und los!">
          </td>
        </tr>
      </table>
    </form>

    <p><?php echo $message; ?></p>

    <table border="0" cellspacing="0" cellpadding="5" bgcolor="#C0C0C0" width="50%">
      <tr>
        <td>
          <p>
            Mit diesem Tool hat der Benutzer die Moeglichkeit, CSV-Dateien
            in eine bestehende MySQL-Datenbank zu uebertragen.
            In erster Linie wurde diese Anwendung entwickelt, um Dateien
            (CSV, Text), die in externen Programmen entstehen
            (Tabellenkalkulation, Warenwirtschaft, Produktdatenbanken)
            und die regelmaessig in eine MySQL-Datenbank auf dem Web-Server
            uebertragen werden muessen, einfach und auf Knopfdruck zu
            konvertieren.
          </p>

          <p>
            Beim Aufruf des Scripts wird ein Select-Menue erzeugt, das auf
            den folgenden Eintraegen basiert. Jeder Block stellt ein
            Eintrag im Menue dar.
            Sollen mehrere Eintraege definiert werden, muessen die folgenden
            Definitions-Bloecke 'entkommentiert' werden.
          </p>

          <p>
            Beim Ausf&uuml;hren des Scripts wird der Inhalt der CSV direkt in die
            Datenbank uebertragen.
          </p>

          <p>
            Wichtig: Um die Daten erfolgreich in die Datenbank uebertragen zu
            koennen, muss eine passende Tabelle bereits bestehen und die Datei
            muss sich auf dem Server befinden. Die alten
            Daten werden dabei komplett gel&ouml;scht und durch die neuen Daten
            ersetzt.
          </p>

          <p>
            Verschiedene Ausgangsdateien und Datenbanktabellen k&ouml;nnen Sie im
            Quellcode des Scripts editieren.
          </p>
        </td>
      </tr>
    </table>



    <p align="right">Powered by <a href="http://www.stadtaus.com/" target="_new">Stadtaus.com</a></p>
  </body>
</html>
*/
?>