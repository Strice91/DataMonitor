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
	*	Erstellt eine Tablle aus vorgegbenen Mustern
	*
	*	@param varchar Typ der Tablle für spezifitationen
	*
	*	@param int Zeitpunkt ab dem die Werte agezeigt werden
	*
	*	@param int Zeitpunkt bis zu dem die Werte angezeitg werden
	*/
	
	public function create($type, $start=0, $end=0)
	{
		$tabledata = Table::tabledata($type);
		
		if($tabledata  === false)
		{
			echo "Tabelle nicht gefunden";
			exit;
		}
		
		
		if($end != 0)	
		{
				$db_table = $GLOBALS['DB']->query("SELECT * FROM ".$tabledata['db']." WHERE date >= ".$start." && date < ".$end." ORDER BY date ASC");
				$heading = "<span class='heading2'>".$tabledata['head']."</span> <span class='heading3'> - Vom ".date('d.m.Y', $start)." bis ".date('d.m.Y', $end)."</span>";
		}
		else
		{
			if($type == "lieferplan")
			{
				$db_table = $GLOBALS['DB']->query("SELECT * FROM ".$tabledata['db']." WHERE jahr = ".$start."");
				$heading = "<span class='heading2'>".$tabledata['head']."</span> <span class='heading3'> - Vom Jahr ".$start."</span>";
			}
			elseif($type == "anlieferung")
			{
				$db_table = $GLOBALS['DB']->query("SELECT * FROM ".$tabledata['db']." WHERE date >= ".$start."");
				$heading = "<span class='heading2'>".$tabledata['head']."</span> <span class='heading3'>";
			}
			else
			{
				$db_table = $GLOBALS['DB']->query("SELECT * FROM ".$tabledata['db']." WHERE date >= ".$start." ORDER BY date ASC");
				$heading = "<span class='heading2'>".$tabledata['head']."</span>";
			}
		}
		
		
		//Tabelle erstellen
		echo '<link href="'.PROJECT_HTTP_ROOT.'/inc/css/table.css" rel="stylesheet" type="text/css" />';
		echo "<table width='".$tabledata['width']."' class='main_table'>"; 
		

		//Überschrift erstellen
		echo "<tr class='ueberschrift'>
    			<td colspan='".($tabledata['collums'])."' >
					<div>
						".$heading."
					</div>
					<div>";
		if($type == "lieferplan")
		{
			echo "".Option::select('jahr')."";
		}
		else
		{
			echo "".Option::select('date')."";
		}
		echo	"</div>
					<div>
						".Option::select('graph', $tabledata['db'], $start, $end)."
					</div>
				</td>
  			</tr>";
			
		//Namensspalten erstellen
		echo "<tr>";
		
		for($i=1; $i <= $tabledata['collums']; $i++)
		{
			echo "<th scope='col'>".$tabledata['collumName'][$i]."</th>";
		}

		echo "</tr>";
		
		
		//Tabellen einträge erstellen
		foreach($db_table as $values)
		{
			echo "<tr>";
			
				$i = 0;
				foreach($values as $wert)
				{
					if($i>0)
					{
						echo "<td width='".$tabledata['collumWidth'][$i]."'>".Table::valuetransform($type, $tabledata['collumClass'][$i], $wert)."</td>";	
					}
					$i++;
				}
				
				//Optionen anziegen
				if($tabledata['options'])
				{
					echo "<td>".Option::edit($values['id'], $tabledata['db'])." ".Option::delete($values['id'], $tabledata['db'])."</td>";
				}
				
				
			echo "</tr>";
		}
		

	}
	
	/*
	*	Speichert die Tabllen Spezifiationen
	*
	*	@param varchar Schlüsses für tabellenmerkmale
	*/
	
	public function tabledata($type)
	{
		switch($type)
		{
			case 'lieferplan':
			
				//db-Tabelle
				$return['db'] = "lieferplan";
				
				//Überschrift definieren
				$return['head'] = "Lieferplan";
				
				//Breite der Tablle
				$return['width'] = "";
				
				//Optionen anzeigen
				$return['options'] = true;
				
				//Spaletenanzahl
				$return['collums'] = 19;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "Jahr";
				$return['collumName'][2] = "";
				$return['collumName'][3] = "Status";
				$return['collumName'][4] = "";
				$return['collumName'][5] = "&Uuml;bertrag";
				$return['collumName'][6] = "Januar";
				$return['collumName'][7] = "Februar";
				$return['collumName'][8] = "M&auml;rz";
				$return['collumName'][9] = "April";
				$return['collumName'][10] = "Mai";
				$return['collumName'][11] = "Juni";
				$return['collumName'][12] = "Juli";
				$return['collumName'][13] = "August";
				$return['collumName'][14] = "September";
				$return['collumName'][15] = "Oktober";
				$return['collumName'][16] = "November";
				$return['collumName'][17] = "Dezember";
				$return['collumName'][18] = "Gesammt";
				$return['collumName'][19] = "";
				
				//Spaltenbreite
				$return['collumWidth'][1] = 10;
				$return['collumWidth'][2] = 10;
				$return['collumWidth'][3] = 10;
				$return['collumWidth'][4] = 10;
				$return['collumWidth'][5] = 10;
				$return['collumWidth'][6] = 10;
				$return['collumWidth'][7] = 10;
				$return['collumWidth'][8] = 10;
				$return['collumWidth'][9] = 10;
				$return['collumWidth'][10] = 10;
				$return['collumWidth'][11] = 10;
				$return['collumWidth'][12] = 10;
				$return['collumWidth'][13] = 10;
				$return['collumWidth'][14] = 10;
				$return['collumWidth'][15] = 10;
				$return['collumWidth'][16] = 10;
				$return['collumWidth'][17] = 10;
				$return['collumWidth'][18] = 10;
				$return['collumWidth'][19] = 10;

				//Klasse der Spalte --> Valuetransform
				$return['collumClass'][1] = "";
				$return['collumClass'][2] = "";
				$return['collumClass'][3] = "";
				$return['collumClass'][4] = "";
				$return['collumClass'][5] = "";
				$return['collumClass'][6] = "";
				$return['collumClass'][7] = "";
				$return['collumClass'][8] = "";
				$return['collumClass'][9] = "";
				$return['collumClass'][10] = "";
				$return['collumClass'][11] = "";
				$return['collumClass'][12] = "";
				$return['collumClass'][13] = "";
				$return['collumClass'][14] = "";
				$return['collumClass'][15] = "";
				$return['collumClass'][16] = "";
				$return['collumClass'][17] = "";
				$return['collumClass'][18] = "";
				$return['collumClass'][19] = "";
				
				break;
			
			case 'ableseprotokoll':
				
				//db-Tabelle
				$return['db'] = "ableseprotokoll";
				
				//Überschrift definieren
				$return['head'] = "Ableseprotokoll";
				
				//Breite der Tablle
				$return['width'] = "";
				
				//Optionen anzeigen
				$return['options'] = true;
				
				//Spaletenanzahl
				$return['collums'] = 12;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "Datum";
				$return['collumName'][2] = "Energiewert ORC-Anlage [kWh]";
				$return['collumName'][3] = "Kesseleinsch&uuml;be";
				$return['collumName'][4] = "Z&auml;hler 1 ORC [MWh]";
				$return['collumName'][5] = "Z&auml;hler 2 Spitzenlastkessel [MWh]";
				$return['collumName'][6] = "Z&auml;hler 3 R&uuml;cklauf [MWh]";
				$return['collumName'][7] = "Z&auml;hler 4 Heizung [MWh]";
				$return['collumName'][8] = "Z&auml;hler 5 Netz [MWh]";
				$return['collumName'][9] = "Z&auml;hler 6 Gas [MWh]";
				$return['collumName'][10] = "Z&auml;hler 7 [MWh]";
				$return['collumName'][11] = "Unterschrift";
				$return['collumName'][12] = "";

			
				//Spaltenbreite
				$return['collumWidth'][1] = 10;
				$return['collumWidth'][2] = 10;
				$return['collumWidth'][3] = 10;
				$return['collumWidth'][4] = 10;
				$return['collumWidth'][5] = 10;
				$return['collumWidth'][6] = 10;
				$return['collumWidth'][7] = 10;
				$return['collumWidth'][8] = 10;
				$return['collumWidth'][9] = 10;
				$return['collumWidth'][10] = 10;
				$return['collumWidth'][11] = 10;
				$return['collumWidth'][12] = 10;


				//Klasse der Spalte --> Valuetransform
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "";
				$return['collumClass'][3] = "";
				$return['collumClass'][4] = "";
				$return['collumClass'][5] = "";
				$return['collumClass'][6] = "";
				$return['collumClass'][7] = "";
				$return['collumClass'][8] = "";
				$return['collumClass'][9] = "";
				$return['collumClass'][10] = "";
				$return['collumClass'][11] = "";
				$return['collumClass'][11] = "";

				break;
			
			case 'orc':
			
				//db-Tabelle
				$return['db'] = "orc";
				
				//Überschrift definieren
				$return['head'] = "Energiewerte ORC-Anlage";
				
				//Breite der Tablle
				$return['width'] = "600";
				
				//Optionen anzeigen
				$return['options'] = true;
				
				//Spaletenanzahl
				$return['collums'] = 5;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "Ablesedatum";
				$return['collumName'][2] = "Z&auml;hlerstand/Vortag";
				$return['collumName'][3] = "Z&auml;hlerstand/Ablesetag";
				$return['collumName'][4] = "Energiewert [kWh]";
				$return['collumName'][5] = "";

				//Spaltenbreite
				$return['collumWidth'][1] = 150;
				$return['collumWidth'][2] = 100;
				$return['collumWidth'][3] = 100;
				$return['collumWidth'][4] = 100;
				$return['collumWidth'][5] = 100;
				
				//Klasse der Spalte --> Valuetransform
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "";
				$return['collumClass'][3] = "";
				$return['collumClass'][4] = "";
				$return['collumClass'][5] = "";
				
				//Namen der Spalten in der Datenbank
				$return['collumDB'][1] = "Ablesedatum";
				$return['collumDB'][2] = "Z&auml;hlerstand/Vortag";
				$return['collumDB'][3] = "Z&auml;hlerstand/Ablesetag";
				$return['collumDB'][4] = "Energiewert [kWh]";
				break;
			
			case 'anlieferung':
			
				//db-Tabelle
				$return['db'] = "anlieferung";
				
				//Überschrift definieren
				$return['head'] = "Hackschnitzelanlieferung";
				
				//Breite der Tablle
				$return['width'] = "700";
				
				//Optionen anzeigen
				$return['options'] = true;
				
				//Spaletenanzahl
				$return['collums'] = 7;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "Datum";
				$return['collumName'][2] = "Wassergehalt %";
				$return['collumName'][3] = "Leergewicht [kg]";
				$return['collumName'][4] = "Beladen [kg]";
				$return['collumName'][5] = "Lieferant";
				$return['collumName'][6] = "Eingetragen";
				$return['collumName'][7] = "";

				//Spaltenbreite
				$return['collumWidth'][1] = 150;
				$return['collumWidth'][2] = 100;
				$return['collumWidth'][3] = 100;
				$return['collumWidth'][4] = 100;
				$return['collumWidth'][5] = 100;
				$return['collumWidth'][6] = 100;
				$return['collumWidth'][7] = 100;
				
				//Klasse der Spalte --> Valuetransform
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "";
				$return['collumClass'][3] = "";
				$return['collumClass'][4] = "";
				$return['collumClass'][5] = "";
				$return['collumClass'][6] = "updated";
				$return['collumClass'][7] = "";
				break;
			
			case 'kesseleinschuebe':
				//db-Tabelle
				$return['db'] = "kesseleinschuebe";
				
				//Überschrift definieren
				$return['head'] = "Kesseleinsch&uuml;be";
				
				//Breite der Tablle
				$return['width'] = "600";
				
				//Optionen anzeigen
				$return['options'] = true;
				
				//Spaletenanzahl
				$return['collums'] = 5;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "Ablesedatum";
				$return['collumName'][2] = "Anzahl/Vortag";
				$return['collumName'][3] = "Anzahl/Ablesetag";
				$return['collumName'][4] = "Anzahl pro Tag";
				$return['collumName'][5] = "";

				//Spaltenbreite
				$return['collumWidth'][1] = 150;
				$return['collumWidth'][2] = 100;
				$return['collumWidth'][3] = 100;
				$return['collumWidth'][4] = 100;
				$return['collumWidth'][5] = 100;
				
				//Klasse der Spalte --> Valuetransform
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "";
				$return['collumClass'][3] = "";
				$return['collumClass'][4] = "";
				$return['collumClass'][5] = "";
				
				break;
			
			case 'waermeerzeugung':
				//db-Tabelle
				$return['db'] = "waermeerzeugung";
				
				//Überschrift definieren
				$return['head'] = "W&auml;meerzeugung";
				
				//Breite der Tablle
				$return['width'] = "";
				
				//Optionen anzeigen
				$return['options'] = true;
				
				//Spaletenanzahl
				$return['collums'] = 23;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = 	"<span style='font-size:10px;' >Datum</span>";
				$return['collumName'][2] = 	"<span class='small' title='ORC Vortag'>						Z1 [MWh]</span>";
				$return['collumName'][3] = 	"<span class='small' title='ORC Ablesetag'>						Z1 [MWh]</span>";
				$return['collumName'][4] = 	"<span class='small' title='ORC W&auml;mewert'>					Z1 [MWh]</span>";
				$return['collumName'][5] = 	"<span class='small' title='Spitzenlastkessel Vortag'>			Z2 [MWh]</span>";
				$return['collumName'][6] = 	"<span class='small' title='Spitzenlastkessel Ablesetag'>		Z2 [MWh]</span>";
				$return['collumName'][7] = 	"<span class='small' title='Spitzenlastkessel W&auml;mewert'>	Z2 [MWh]</span>";
				$return['collumName'][8] = 	"<span class='small' title='R&uuml;cklauf Vortag'>				Z3 [MWh]</span>";
				$return['collumName'][9] = 	"<span class='small' title='R&uuml;cklauf Ablesetag'>			Z3 [MWh]</span>";
				$return['collumName'][10] = "<span class='small' title='R&uuml;cklauf W&auml;mewert'>		Z3 [MWh]</span>";
				$return['collumName'][11] = "<span class='small' title='Heizung Vortag'>					Z4 [MWh]</span>";
				$return['collumName'][12] = "<span class='small' title='Heizung Ablesetag'>					Z4 [MWh]</span>";
				$return['collumName'][13] = "<span class='small' title='Heizung W&auml;mewert'>				Z4 [MWh]</span>";
				$return['collumName'][14] = "<span class='small' title='Netz Vortag'>						Z5 [MWh]</span>";
				$return['collumName'][15] = "<span class='small' title='Netz Ablesetag'>					Z5 [MWh]</span>";
				$return['collumName'][16] = "<span class='small' title='Netz W&auml;mewert'>				Z5 [MWh]</span>";
				$return['collumName'][17] = "<span class='small' title='Gas Vortag'>						Z6 [MWh]</span>";
				$return['collumName'][18] = "<span class='small' title='Gas Ablesetag'>						Z6 [MWh]</span>";
				$return['collumName'][19] = "<span class='small' title='Gas W&auml;mewert'>					Z6 [MWh]</span>";
				$return['collumName'][20] = "<span class='small' title=''>									Z7 [MWh]</span>";
				$return['collumName'][21] = "<span class='small' title=''>									Z7 [MWh]</span>";
				$return['collumName'][22] = "<span class='small' title=''>									Z7 [MWh]</span>";
				$return['collumName'][23] = "";
				
				//Spaltenbreite
				$return['collumWidth'][1] = 5;
				$return['collumWidth'][2] = 10;
				$return['collumWidth'][3] = 10;
				$return['collumWidth'][4] = 10;
				$return['collumWidth'][5] = 10;
				$return['collumWidth'][6] = 10;
				$return['collumWidth'][7] = 10;
				$return['collumWidth'][8] = 10;
				$return['collumWidth'][9] = 10;
				$return['collumWidth'][10] = 10;
				$return['collumWidth'][11] = 10;
				$return['collumWidth'][12] = 10;
				$return['collumWidth'][13] = 10;
				$return['collumWidth'][14] = 10;
				$return['collumWidth'][15] = 10;
				$return['collumWidth'][16] = 10;
				$return['collumWidth'][17] = 10;
				$return['collumWidth'][18] = 10;
				$return['collumWidth'][19] = 10;
				$return['collumWidth'][20] = 10;
				$return['collumWidth'][21] = 10;
				$return['collumWidth'][22] = 10;
				$return['collumWidth'][23] = 10;

				//Klasse der Spalte --> Valuetransform
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "";
				$return['collumClass'][3] = "";
				$return['collumClass'][4] = "";
				$return['collumClass'][5] = "";
				$return['collumClass'][6] = "";
				$return['collumClass'][7] = "";
				$return['collumClass'][8] = "";
				$return['collumClass'][9] = "";
				$return['collumClass'][10] = "";
				$return['collumClass'][11] = "";
				$return['collumClass'][12] = "";
				$return['collumClass'][13] = "";
				$return['collumClass'][14] = "";
				$return['collumClass'][15] = "";
				$return['collumClass'][16] = "";
				$return['collumClass'][17] = "";
				$return['collumClass'][18] = "";
				$return['collumClass'][19] = "";
				$return['collumClass'][20] = "";
				$return['collumClass'][21] = "";
				$return['collumClass'][22] = "";
				$return['collumClass'][23] = "";
				break;
			
			case 'sew':
				$return = false;
				break;
			
			case 'hackschnitzel':
				//db-Tabelle
				$return['db'] = "hackschnitzel";
				
				//Überschrift definieren
				$return['head'] = "Hackschnitzelumschlag";
				
				//Breite der Tablle
				$return['width'] = "";
				
				//Optionen anzeigen
				$return['options'] = true;
				
				//Spaletenanzahl
				$return['collums'] = 16;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "Datum";
				$return['collumName'][2] = "Wassergehalt";
				$return['collumName'][3] = "Holzgewicht frisch";
				$return['collumName'][4] = "Menge  [srm]";
				$return['collumName'][5] = "Holzgewicht atro";
				$return['collumName'][6] = "Heizwert";
				$return['collumName'][7] = "CO2-Einsparung";
				$return['collumName'][8] = "Alzinger [srm]";
				$return['collumName'][9] = "WBV KEH [srm]";
				$return['collumName'][10] = "Euringer [srm]";
				$return['collumName'][11] = "Sonstige";
				$return['collumName'][12] = "Asche";
				$return['collumName'][13] = "W&auml;rme";
				$return['collumName'][14] = "Strom";
				$return['collumName'][15] = "Lieferungen";
				$return['collumName'][16] = "";
			
				//Spaltenbreite
				$return['collumWidth'][1] = 10;
				$return['collumWidth'][2] = 10;
				$return['collumWidth'][3] = 10;
				$return['collumWidth'][4] = 10;
				$return['collumWidth'][5] = 10;
				$return['collumWidth'][6] = 10;
				$return['collumWidth'][7] = 10;
				$return['collumWidth'][8] = 10;
				$return['collumWidth'][9] = 10;
				$return['collumWidth'][10] = 10;
				$return['collumWidth'][11] = 10;
				$return['collumWidth'][12] = 10;
				$return['collumWidth'][13] = 10;
				$return['collumWidth'][14] = 10;
				$return['collumWidth'][15] = 50;
				$return['collumWidth'][16] = 50;

				//Klasse der Spalte --> Valuetransform
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "";
				$return['collumClass'][3] = "";
				$return['collumClass'][4] = "";
				$return['collumClass'][5] = "";
				$return['collumClass'][6] = "";
				$return['collumClass'][7] = "";
				$return['collumClass'][8] = "";
				$return['collumClass'][9] = "";
				$return['collumClass'][10] = "";
				$return['collumClass'][11] = "";
				$return['collumClass'][12] = "";
				$return['collumClass'][13] = "";
				$return['collumClass'][14] = "";
				$return['collumClass'][15] = "";
				$return['collumClass'][16] = "";

				break;
				
			case 'soll':
				//db-Tabelle
				$return['db'] = "soll";
				
				//Überschrift definieren
				$return['head'] = "Sollwertliste";
				
				//Breite der Tablle
				$return['width'] = "";
				
				//Optionen anzeigen
				$return['options'] = true;
				
				//Spaletenanzahl
				$return['collums'] = 4;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "Tabelle";
				$return['collumName'][2] = "Jahr";
				$return['collumName'][3] = "Wert";
			
				//Spaltenbreite
				$return['collumWidth'][1] = 10;
				$return['collumWidth'][2] = 10;
				$return['collumWidth'][3] = 10;


				//Klasse der Spalte --> Valuetransform
				$return['collumClass'][1] = "";
				$return['collumClass'][2] = "";
				$return['collumClass'][3] = "";

				break;
			
			default:
				$return = false;
				break;
					
		}
		
		return $return;
	}
	
	/*
	*	Transformiert die Werte in das richitge Layout
	*
	*	@param varchar Schlüsses für Layout
	*
	*	@param mixed Werte die angepasst werden sollen
	*/
	
	public function valuetransform($type, $class, $value)
	{	
		//Typ der Tabelle auswählen
		switch($type)
		{
			case 'lieferplan':
				$return = $value;
				break;
			
			case 'ableseprotokoll':
				//Art der Transformierung auswählen
				switch($class)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "-";
						}
						else
						{
							$return = date('d.m.Y H:i', $value);
						}
						break;

					default:
						$return = $value;
						break;
				}
				break;
			
			case 'orc':
				//Art der Transformierung auswählen
				switch($class)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "-";
						}
						else
						{
							$return = date('d.m.Y H:i', $value);
						}
						break;

					default:
						$return = $value;
						break;
				}
				break;
			
			case 'kesseleinschuebe':
				//Art der Transformierung auswählen
				switch($class)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "-";
						}
						else
						{
							$return = date('d.m.Y H:i', $value);
						}
						break;

					default:
						$return = $value;
						break;
				}
				break;
			
			case 'waermeerzeugung':
				//Art der Transformierung auswählen
				switch($class)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "-";
						}
						else
						{
							$return = date('d.m.Y H:i', $value);
						}
						break;

					default:
						$return = $value;
						break;
				}
				break;
			
			case 'anlieferung':
				//Art der Transformierung auswählen
				switch($class)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "-";
						}
						else
						{
							$return = date('d.m.Y H:i', $value);
						}
						break;
						
					case 'updated':
						if($value == 0)
						{
							$reutrn = "Nein";
						}
						else
						{
							$return = "Ja";
						}
						break;

					default:
						$return = $value;
						break;
				}
				break;
			
			case 'swe':
				$return = $value;
				break;
			
			case 'hackschnitzel':
				switch($class)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "-";
						}
						else
						{
							$return = date('d.m.Y', $value);
						}
						break;

					default:
						$return = $value;
						break;
				}
				break;
			
			default:
				$return = $value;
				break;
					
		}
		
		return $return;
	}
	
	/*
	*	Gibt an ob ein Wert aus der Tabelle zweimal verwendet wird
	*
	*	@param varchar Schlüsses für Layout
	*
	*	@param mixed Werte die angepasst werden sollen
	*/
	
	
}
?>