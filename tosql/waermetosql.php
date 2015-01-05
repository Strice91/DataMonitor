<?php


          // Definition der Datenbanken und csv-Dateien
		  $select_db = 0;
          $db_MenuTitle[0]  = "Eintrag 1";         // Bezeichnung des Eintrags - erscheint im Dropdown
          $db_Hostname[0]   = "localhost";         // Datenbank-Host (muss nicht zwingend immer localhost sein)
          $db_UserName[0]   = "root";                  // Benutzername f&uuml;r diese Datenbank
          $db_Password[0]   = "";                  // Zugehoeriges Passwort
          $db_Database[0]   = "bmhkw";                  // Datenbank, auf die zugegriffen werden soll
          $db_Table[0]      = "waermeerzeugung";                  // Table, in den die CSV-Datei &uuml;bertragen werden soll
          $db_File[0]       = "waermeerzeugung.csv";                  // Verzeichnispfad zur Textdatei (CSV etc.) auf dem Webserver
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
				$data = str_replace(",",".",$data);
				
				echo "Datum:".$date." ";
				echo "z1=".$data[2]." ";
				echo "z1=".$data[3]." ";
				echo "z1=".$data[3]." ";
				echo "z2=".$data[4]." ";
				echo "z2=".$data[5]." ";
				echo "z2=".$data[6]." ";
				echo "z3=".$data[7]." ";
				echo "z3=".$data[8]." ";          
				echo "z3=".$data[9]." ";
				echo "z4=".$data[10]." ";
				echo "z4=".$data[11]." ";       
				echo "z4=".$data[12]." ";  
				echo "z5=".$data[13]." ";  
				echo "z5=".$data[14]." ";  
				echo "z5=".$data[15]." ";
				echo "z6=".$data[16]." ";
				echo "z6=".$data[17]." ";
				echo "z6=".$data[19]." ";
					
                // CSV-Datei in die Datenbank &uuml;bertragen
            	$sql = "INSERT INTO ".$db_Table[0]." SET date='".$date."', z1_vortag='".$data[2]."', z1_ablesetag='".$data[3]."', z1_waerme='".$data[4]."', z2_vortag='".$data[5]."', z2_ablesetag='".$data[6]."', z2_waerme='".$data[7]."', z3_vortag='".$data[8]."', z3_ablesetag='".$data[9]."', z3_waerme='".$data[10]."', z4_vortag='".$data[11]."', z4_ablesetag='".$data[12]."', z4_waerme='".$data[13]."', z5_vortag='".$data[14]."' , z5_ablesetag='".$data[15]."' , z5_waerme='".$data[16]."' , z6_vortag='".$data[17]."', z6_ablesetag='".$data[18]."', z6_waerme='".$data[19]."'";
				
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