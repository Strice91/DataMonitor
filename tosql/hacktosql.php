<?php


          // Definition der Datenbanken und csv-Dateien
			$select_db = 0;
          $db_MenuTitle[0]  = "Eintrag 1";         // Bezeichnung des Eintrags - erscheint im Dropdown
          $db_Hostname[0]   = "localhost";         // Datenbank-Host (muss nicht zwingend immer localhost sein)
          $db_UserName[0]   = "root";                  // Benutzername f&uuml;r diese Datenbank
          $db_Password[0]   = "";                  // Zugehoeriges Passwort
          $db_Database[0]   = "bmhkw";                  // Datenbank, auf die zugegriffen werden soll
          $db_Table[0]      = "hackschnitzel";                  // Table, in den die CSV-Datei &uuml;bertragen werden soll
          $db_File[0]       = "hackschnitzel.csv";                  // Verzeichnispfad zur Textdatei (CSV etc.) auf dem Webserver
          $db_Terminated[0] = ";";                 // Trennzeichen, das in der Textdatei verwendet wird




    if (isset ($select_db)) {


            // Connect zur Datenbank
            mysql_connect($db_Hostname[$select_db], $db_UserName[$select_db], $db_Password[$select_db]) || die("Can't Connect to Database: ".mysql_error());
            mysql_select_db($db_Database[$select_db]);

            


			$row = 1;                                     					// Anzahl der Arrays
			$handle = fopen ($db_File[0],"r");             					// Datei zum Lesen öffnen
			while ( ($data = fgetcsv ($handle, 100000, ";")) !== FALSE ) { 	// Daten werden aus der Datei
                                               								// in ein Array $data gelesen
 			  	$num = count ($data);                     					// Felder im Array $data
                                               								// werden gezählt
   				$row++; 
				if(($data[0] != null))
				{
				  $datum = explode('.', $data[0]);
				  $jahr = $datum[2];
				  $monat = $datum[1];
				  $tag = $datum[0];

				  
				  $date = mktime(0, 0, 0, $monat, $tag, $jahr);
				}
				else
				{
					$date = "";	
				}
				$data = str_replace(",",".",$data);
				echo "Datum:".$date." ";
				echo "Wasser".$data[1]." ";
				echo "Holz".$data[2]." ";
				echo "Menge".$data[3]." ";
				echo "Holz".$data[4]." ";
				echo "Heiz".$data[5]." ";
				echo "CO".$data[6]." ";
				echo "L1".$data[7]." ";
				echo "L2".$data[8]." ";          
				echo "L3".$data[9]." ";
				echo "S".$data[10]." ";
				echo "A".$data[11]." ";       
				echo "W".$data[12]." ";  
				echo "S".$data[13]." ";  
					
                // CSV-Datei in die Datenbank &uuml;bertragen
            	$sql = "INSERT INTO hackschnitzel SET date='".$date."', wassergehalt='".$data[1]."', holzgewicht_frisch='".($data[2]*1000)."', menge='".$data[3]."', holzgewicht_atro='".($data[4]*1000)."', heizwert='".($data[5]*1000)."', co2='".$data[6]."', lieferant1='".$data[7]."', lieferant2='".$data[8]."', lieferant3='".$data[9]."', 	sonstige='".$data[10]."', asche='".$data[11]."', waerme='".$data[12]."', strom='".$data[13]."'";
				
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
			

?>