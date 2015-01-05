<?php
//Namespace der Klasse
namespace System;

/**
* Die Formular Klasse
*
*/
 
class Form
{
	/**
	*	Erstellt ein Formular aus vorgegbenen Mustern
	*
	*	@param varchar Typ und Eigentschaften des Formulars
	*
	*	@param array Übergebene Werte zur initalsierung des Fromulars
	*/
	public function create($type, $values, $execute = false)
	{
		$tabledata = Table::tabledata($type);
		$formdata = Form::formdata($type);
		$mysqlStructur = Database\MySQL::getStructur($type);
		
		if($formdata  === false)
		{
			echo "Fromular nicht gefunden";
			exit;
		}
		
		//Formular erstellen
		echo "<form name='insert' method='post' action='".PROJECT_HTTP_ROOT."/scripts/execute.php'>";
		echo	"<table class='form_table' border='0px'>";
		
		
		
		for($i=0; $i < $formdata['collums']; $i++)
		{
			if(!empty($values))
			{
				$value = $values[$i];
				$action = "mysql_update";
			}
			else
			{
				$value = "";
				$action = "mysql_insert";
			}
			
			if($i == 0)
			{
				echo "<input type='hidden' name='id' id='id' value='".$value."'>";	
				echo "<input type='hidden' name='table' id='table' value='".$tabledata['db']."'>";	
				echo "<input type='hidden' name='action' id='action' value='".$action."'>";	
			}
			else
			{	
			    
 				if($formdata['collumClass'][$i] == "datum")
				{
					if(empty($value))
					{
						$date = date('n.j.Y', time());
						$d_array = explode('.', $date);
						$vortag = mktime(0,0,0,$d_array[0],$d_array[1]-1,$d_array[2]);
						$heute = time();
						$heute_morgen = mktime(0,0,0,$d_array[0],$d_array[1],$d_array[2]);
						$heute_abend = mktime(23,59,59,$d_array[0],$d_array[1],$d_array[2]);
						//echo "vortag=".date('j.n.Y',$vortag)." heute=".date('j.n.Y', $heute)."";
					}
					else
					{
						$date = date('n.j.Y', $value);
						$d_array = explode('.', $date);
						$vortag = mktime(0,0,0,$d_array[0],$d_array[1]-1,$d_array[2]);
						$heute = mktime(0,0,0,$d_array[0],$d_array[1],$d_array[2]);
						$heute_morgen = mktime(0,0,0,$d_array[0],$d_array[1],$d_array[2]);
						$heute_abend = mktime(23,59,59,$d_array[0],$d_array[1],$d_array[2]);
						//echo "vortag=".date('j.n.Y',$vortag)." heute=".date('j.n.Y', $heute)."";
					}
					
				}
				if($formdata['collumClass'][$i] == "vortag")
				{
					$db_vortag = $GLOBALS['DB']->query("SELECT * FROM ".$tabledata['db']." WHERE date < ".$heute." && date > ".$vortag." ORDER BY id DESC limit 1");
					$db_heute = $GLOBALS['DB']->query("SELECT * FROM ".$tabledata['db']." WHERE date < ".$heute_abend." && date > ".$heute_morgen." ORDER BY id DESC limit 1");
					
					foreach($db_vortag as $db)
					{	
						$wertVortag = $db['ablesetag'];
					}
					foreach($db_heute as $db)
					{	
						$wertHeute = $db['ablesetag'];
					}
					if(isset($wertHeute) && isset($wertVortag))
					{
					  if($wertHeute > $wertVortag)
					  {
						  $value = $wertVortag;
					  }
					  else
					  {
						  $value = 0;	
					  }
					}
				}
				
				echo "<tr>";
				echo 	"<td>";
				echo 		"<label for='i1'>".$formdata['collumName'][$i]."</label>";
				echo 	"</td>";
				echo 	"<td>";
				echo 		Form::input_style($type, $formdata['collumClass'][$i], $mysqlStructur['collumName'][$i], $i, $formdata['collumClass'][$i], $value)."&nbsp<span class='unit'>".$formdata['collumUnit'][$i]."</span>";
				echo 	"</td>";
				echo 	"<td>";
				echo 		"";
				echo	"</td>";
				echo "</tr>";
			}
			
		}
		echo		 "<tr>";
		echo 			"<td colspan='".($tabledata['collums']-1)."' align='right'>";
		echo				"<input type='submit' name='submit' id='submit' value='Speichern'>";
		echo 			"</td>";
		echo 		"</tr>";
		echo 	"</table>";
		echo "</form>";
	}
	
	/**
	*	Generiert ein Eingabefeld
	*
	*	@param varchar Typ der Tabelle
	*
	*	@param varchar Typ des zu erstellenden Eingabefeldes
	*
	*	@param varchar Name des zu erstellenden Eingabefeldes
	*
	*	@param varchar id des zu erstellenden Eingabefeldes
	*
	*	@param varchar Klasse des Inhalts des Eingabefeldes
	*
	*	@param varchar Wert der im Eingabefeld stehen soll
	*/
	
	public function input_style($table, $type, $name, $id, $class, $value)
	{
		//Typ der Tabelle auswählen
		
		$formdata = Form::formdata($table);
		
		switch($table)
		{
			case 'lieferplan':
				switch($type)
				{
					case 'jahr':
						
						$start = 2011;
						$j = 1;
						$year = date('Y');
						
						$return = 	"<select name='".$name."' id='".$id."'>";
						for($i=$start; $i <= $year; $i++)
						{
							$j++;
							if($i == $year)
							{
								$return .= 	"<option value='".$i."' selected='selected'>".$i."</option>";	
							}
							else
							{
								$return .= 	"<option value='".$i."'>".$i."</option>";
							}
						}
						$return .= 	"</select>";
						
						break;
						
					case 'status':
						
						$return = 	"<select name='".$name."' id='".$id."'>";
						$return .= 		"<option value='plan'>Plan-Wert</option>";	
						$return .= 		"<option value='ist'>Ist-Wert</option>";
						$return .= 	"</select>";
						
						break;
						
					case 'einheit':
						
						$return = 	"<select name='".$name."' id='".$id."'>";
						$return .= 		"<option value='srm'>srm</option>";	
						$return .= 		"<option value='MWh'>MWh</option>";
						$return .= 	"</select>";
						
						break;
					
					case 'summe':
						{
							$return = "<input size='' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."'>";
							echo "<script type='text/javascript'>
								function rechne(){
								var uebertrag = parseInt(document.getElementsByName('uebertrag')[0].value,10);
								var januar = parseInt(document.getElementsByName('januar')[0].value,10);
								var februar = parseInt(document.getElementsByName('februar')[0].value,10);
								var maerz = parseInt(document.getElementsByName('maerz')[0].value,10);
								var april = parseInt(document.getElementsByName('april')[0].value,10);
								var mai = parseInt(document.getElementsByName('mai')[0].value,10);
								var juni = parseInt(document.getElementsByName('juni')[0].value,10);
								var juli = parseInt(document.getElementsByName('juli')[0].value,10);
								var august = parseInt(document.getElementsByName('august')[0].value,10);
								var september = parseInt(document.getElementsByName('september')[0].value,10);
								var oktober = parseInt(document.getElementsByName('oktober')[0].value,10);
								var november = parseInt(document.getElementsByName('november')[0].value,10);
								var dezember = parseInt(document.getElementsByName('dezember')[0].value,10);
								document.getElementsByName('".$name."')[0].value= uebertrag + januar + februar + maerz + april + mai + juni + juli + august + september + oktober + november + dezember;
								}
								</script>";
						}
						break;
						
					case 'nummer':
						if($value == 0)
						{
							$return = "<input size='' type='text' name='".$name."' id='".$id."' value='0' onBlur='rechne()'>";
						}
						else
						{
							$return = "<input size='' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."' onBlur='rechne()'>";
						}

						break;
						
							
					case 'text':
						
							$return = "<input size='' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."'>";

						break;
						
					default:
						$return = $value;
						break;
				}
				break;
			
			case 'ableseprotokoll':
				//Art des einzutragenden Wertes auswählen
				switch($type)
				{
					case 'datum':
					if($value == 0)
						{
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d')."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m')."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y')."'>";
							$return .= " um <input size='".$formdata['collumSize']['stunde']."' maxlength='2' type='text' name='".$name."_stunde' id='".$id."_jahr' value='".date('h')."'>";
							$return .= ":<input size='".$formdata['collumSize']['minute']."' maxlength='2' type='text' name='".$name."_minute' id='".$id."_jahr' value='".date('i')."'>";
						}
						else
						{
							
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y', $value)."'>";
							$return .= " um <input size='".$formdata['collumSize']['stunde']."' maxlength='2' type='text' name='".$name."_stunde' id='".$id."_jahr' value='".date('h', $value)."'>";
							$return .= ":<input size='".$formdata['collumSize']['minute']."' maxlength='2' type='text' name='".$name."_minute' id='".$id."_jahr' value='".date('i', $value)."'>";

						}
						break;
						
					case 'text':
					case 'vortag':
						{
							$return = "<input size='' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."'>";
						}
						break;

					default:
						$return = $value;
						break;
				}
				break;
			
			case 'orc':
		
				//Art des einzutragenden Wertes auswählen
				switch($type)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d')."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m')."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y')."'>";
							$return .= " um <input size='".$formdata['collumSize']['stunde']."' maxlength='2' type='text' name='".$name."_stunde' id='".$id."_jahr' value='".date('h')."'>";
							$return .= ":<input size='".$formdata['collumSize']['minute']."' maxlength='2' type='text' name='".$name."_minute' id='".$id."_jahr' value='".date('i')."'>";
						}
						else
						{
							
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y', $value)."'>";
							$return .= " um <input size='".$formdata['collumSize']['stunde']."' maxlength='2' type='text' name='".$name."_stunde' id='".$id."_jahr' value='".date('h', $value)."'>";
							$return .= ":<input size='".$formdata['collumSize']['minute']."' maxlength='2' type='text' name='".$name."_minute' id='".$id."_jahr' value='".date('i', $value)."'>";
						
						}
						break;
						
					case 'text':
					case 'vortag':
						{
							$return = "<input size='' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."' onBlur='rechne()'>";
						}
						break;
						
					case 'summe':
						{
							$return = "<input size='' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."'>";
							echo "<script type='text/javascript'>
								function rechne(){
								var ablesetag =parseInt(document.getElementsByName('ablesetag')[0].value,10);
								var vortag = parseInt(document.getElementsByName('vortag')[0].value,10);
								// entsprechend die restlichen Felder
								document.getElementsByName('".$name."')[0].value=ablesetag-vortag;
								}
								</script>";
						}
						break;
						
					default:
						$return = $value;
						break;
				}
				break;
			
			case 'kesseleinschuebe':
				switch($type)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d')."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m')."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y')."'>";
							$return .= " um <input size='".$formdata['collumSize']['stunde']."' maxlength='2' type='text' name='".$name."_stunde' id='".$id."_jahr' value='".date('h')."'>";
							$return .= ":<input size='".$formdata['collumSize']['minute']."' maxlength='2' type='text' name='".$name."_minute' id='".$id."_jahr' value='".date('i')."'>";
						}
						else
						{
							
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y', $value)."'>";
							$return .= " um <input size='".$formdata['collumSize']['stunde']."' maxlength='2' type='text' name='".$name."_stunde' id='".$id."_jahr' value='".date('h', $value)."'>";
							$return .= ":<input size='".$formdata['collumSize']['minute']."' maxlength='2' type='text' name='".$name."_minute' id='".$id."_jahr' value='".date('i', $value)."'>";

						}
						break;
						
					case 'text':
					case 'vortag':
						{
							$return = "<input size='' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."' onBlur='rechne()'>";
						}
						break;
						
					case 'summe':
						{
							$return = "<input size='' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."'>";
							echo "<script type='text/javascript'>
								function rechne(){
								var ablesetag =parseInt(document.getElementsByName('ablesetag')[0].value,10);
								var vortag = parseInt(document.getElementsByName('vortag')[0].value,10);
								// entsprechend die restlichen Felder
								document.getElementsByName('".$name."')[0].value=ablesetag-vortag;
								}
								</script>";
						}
						break;

					default:
						$return = $value;
						break;
				}
				break;
			
			case 'waermeerzeugung':
				switch($type)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d')."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m')."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y')."'>";
							$return .= " um <input size='".$formdata['collumSize']['stunde']."' maxlength='2' type='text' name='".$name."_stunde' id='".$id."_jahr' value='".date('h')."'>";
							$return .= ":<input size='".$formdata['collumSize']['minute']."' maxlength='2' type='text' name='".$name."_minute' id='".$id."_jahr' value='".date('i')."'>";
						}
						else
						{
							
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y', $value)."'>";
							$return .= " um <input size='".$formdata['collumSize']['stunde']."' maxlength='2' type='text' name='".$name."_stunde' id='".$id."_jahr' value='".date('h', $value)."'>";
							$return .= ":<input size='".$formdata['collumSize']['minute']."' maxlength='2' type='text' name='".$name."_minute' id='".$id."_jahr' value='".date('i', $value)."'>";

						}
						break;
						
					case 'text':
					case 'vortag':
						{
							$return = "<input size='' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."'>";
						}
						break;

					default:
						$return = $value;
						break;
				}
				break;
			
			case 'swe':
				$return = false;
				break;
			
			case 'hackschnitzel':
				switch($type)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d')."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m')."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y')."'>";
						}
						else
						{
							
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y', $value)."'>";

						}
						break;
						
					case 'text':
					case 'vortag':
						{
							$return = "<input size='".$formdata['collumSize']['wert']."'  maxlength='10' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."'>";
						}
						break;
				

					default:
						$return = $value;
						break;
				}
				break;
			
			case 'anlieferung':
				switch($type)
				{
					case 'datum':
						if($value == 0)
						{
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d')."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m')."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y')."'>";
							$return .= " um <input size='".$formdata['collumSize']['stunde']."' maxlength='2' type='text' name='".$name."_stunde' id='".$id."_jahr' value='".date('h')."'>";
							$return .= ":<input size='".$formdata['collumSize']['minute']."' maxlength='2' type='text' name='".$name."_minute' id='".$id."_jahr' value='".date('i')."'>";
						}
						else
						{
							
							$return = "<input size='".$formdata['collumSize']['tag']."' maxlength='2' type='text' name='".$name."_tag' id='".$id."_tag' value='".date('d', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['monat']."' maxlength='2' type='text' name='".$name."_mon' id='".$id."_mon' value='".date('m', $value)."'>";
							$return .= ".<input size='".$formdata['collumSize']['jahr']."' maxlength='4' type='text' name='".$name."_jahr' id='".$id."_jahr' value='".date('Y', $value)."'>";
							$return .= " um <input size='".$formdata['collumSize']['stunde']."' maxlength='2' type='text' name='".$name."_stunde' id='".$id."_jahr' value='".date('h', $value)."'>";
							$return .= ":<input size='".$formdata['collumSize']['minute']."' maxlength='2' type='text' name='".$name."_minute' id='".$id."_jahr' value='".date('i', $value)."'>";

						}
						break;
						
					case 'text':
						{
							$return = "<input size='".$formdata['collumSize']['wert']."'  maxlength='10' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."'>";
						}
						break;
						
					case 'lieferant':
						{
							$return = 	"<select name='".$name."'>";
							$return .= 		"<option value='Alzinger'>Alzinger</option>";
							$return .= 		"<option value='WBV KEH'>WBV KEH</option>";
							$return .= 		"<option value='Euringer'>Euringer</option>";
							$return .= 		"<option value='Sonstige'>Sonstige</option>";
							$return .= 	"</select>";
						}
						break;
				

					default:
						$return = $value;
						break;
				}
				break;
				
			case 'soll':
			 	switch($type)
				{
					case 'tabelle':
						if($value === "")
						{
							$return = 	"<select name='".$name."'>";
							$return .= 		"<option value='ableseprotokoll'>Ableseprotokoll</option>";
							$return .= 		"<option value='orc'>ORC-Anlage</option>";
							$return .= 		"<option value='kesseleinschuebe'>Kesseleinsch&uuml;be</option>";
							$return .= 		"<option value='lieferplan'>Lieferplan</option>";
							$return .= 		"<option value='hackschnitzel'>Hackschnitzel</option>";
							$return .= 		"<option value='sew'>Schubboden</option>";
							$return .= 		"<option value='waermeerzeugung'>W&auml;rmeerzeugung</option>";
							$return .= 		"<option value='Reporting|ORC'>Reporting|ORC</option>";
							$return .= 		"<option value='Reporting|PV'>Reporting|PV</option>";
							$return .= 		"<option value='Reporting|Hackschnitzel'>Reporting|Hackschnitzel</option>";
							$return .= 		"<option value='Reporting|Gas-Spitzenlast'>Reporting|Gas-Spitzenlast</option>";
							$return .= 		"<option value='Reporting|Strombezug-BMHKW'>Reporting|Strombezug-BMHKW</option>";
							$return .= 		"<option value='Reporting|Gas-HZ2'>Reporting|Gas-HZ2</option>";
							$return .= 		"<option value='Reporting|Strombezug-HZ2'>Reporting|Strombezug-HZ2</option>";
							$return .= 	"</select>";
						}
						else
						{
							$selected[0] = "";
							$selected[1] = "";
							$selected[2] = "";
							$selected[3] = "";
							$selected[4] = "";
							$selected[5] = "";
							$selected[6] = "";
							$selected[7] = "";
							$selected[8] = "";
							$selected[9] = "";
							$selected[10] = "";
							$selected[11] = "";
							$selected[12] = "";
							$selected[13] = "";
							switch($value)
							{
								case 'ableseprotokoll':
									$selected[0] = "selected='selected'";
								break;
								case 'orc':
									$selected[1] = "selected='selected'";
								break;
								case 'kesseleinschuebe':
									$selected[2] = "selected='selected'";
								break;
								case 'lieferplan':
									$selected[3] = "selected='selected'";
								break;
								case 'hackschnitzel':
									$selected[4] = "selected='selected'";
								break;
								case 'sew':
									$selected[5] = "selected='selected'";
								break;
								case 'waermeerzeugung':
									$selected[6] = "selected='selected'";
								break;
								case 'Reporting|ORC':
									$selected[7] = "selected='selected'";
								break;
								case 'Reporting|PV':
									$selected[8] = "selected='selected'";
								break;
								case 'Reporting|Hackschnitzel':
									$selected[9] = "selected='selected'";
								break;
								case 'Reporting|Gas-Spitzenlast':
									$selected[10] = "selected='selected'";
								break;
								case 'Reporting|Strombezug-BMHKW':
									$selected[11] = "selected='selected'";
								break;
								case 'Reporting|Gas-HZ2':
									$selected[12] = "selected='selected'";
								break;
								case 'Reporting|Strombezug-HZ2':
									$selected[13] = "selected='selected'";
								break;
							}
							
							$return = 	"<select name='".$name."'>";
							$return .= 		"<option value='ableseprotokoll' ".$selected[0].">Ableseprotokoll</option>";
							$return .= 		"<option value='orc' ".$selected[1].">ORC-Anlage</option>";
							$return .= 		"<option value='kesseleinschuebe' ".$selected[2].">Kesseleinsch&uuml;be</option>";
							$return .= 		"<option value='lieferplan' ".$selected[3].">Lieferplan</option>";
							$return .= 		"<option value='hackschnitzel' ".$selected[4].">Hackschnitzel</option>";
							$return .= 		"<option value='sew' ".$selected[5].">Schubboden</option>";
							$return .= 		"<option value='waermeerzeugung' ".$selected[6].">W&auml;rmeerzeugung</option>";
							$return .= 		"<option value='Reporting|ORC' ".$selected[7].">Reporting|ORC</option>";
							$return .= 		"<option value='Reporting|PV' ".$selected[8].">Reporting|PV</option>";
							$return .= 		"<option value='Reporting|Hackschnitzel' ".$selected[9].">Reporting|Hackschnitzel</option>";
							$return .= 		"<option value='Reporting|Gas-Spitzenlast' ".$selected[10].">Reporting|Gas-Spitzenlast</option>";
							$return .= 		"<option value='Reporting|Strombezug-BMHKW' ".$selected[11].">Reporting|Strombezug-BMHKW</option>";
							$return .= 		"<option value='Reporting|Gas-HZ2' ".$selected[12].">Reporting|Gas-HZ2</option>";
							$return .= 		"<option value='Reporting|Strombezug-HZ2' ".$selected[13].">Reporting|Strombezug-HZ2</option>";
							$return .= 	"</select>";
						}
						break;
						
					case 'text':
						{
							$return = "<input size='".$formdata['collumSize']['wert']."'  maxlength='10' type='text' name='".$name."' id='".$id."' value='".Table::valuetransform($table, $class, $value)."'>";
						}
						break;

					default:
						$return = "";
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
	*	Speichert die Formular Spezifiationen
	*
	*	@param varchar Schlüsses für Formularmerkmale
	*/
	
	public function formdata($table)
	{
		switch($table)
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
				$return['collumName'][2] = "Bezeichnung";
				$return['collumName'][3] = "Status";
				$return['collumName'][4] = "Einheit";
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

				
				//Einheiten der Spalten
				$return['collumUnit'][1] = "";
				$return['collumUnit'][2] = "";
				$return['collumUnit'][3] = "";
				$return['collumUnit'][4] = "";
				$return['collumUnit'][5] = "";
				$return['collumUnit'][6] = "";
				$return['collumUnit'][7] = "";
				$return['collumUnit'][8] = "";
				$return['collumUnit'][9] = "";
				$return['collumUnit'][10] = "";
				$return['collumUnit'][11] = "";
				$return['collumUnit'][12] = "";
				$return['collumUnit'][13] = "";
				$return['collumUnit'][14] = "";
				$return['collumUnit'][15] = "";
				$return['collumUnit'][16] = "";
				$return['collumUnit'][17] = "";
				$return['collumUnit'][18] = "";
				
				//Spaltenbreite
				$return['collumSize']['tag'] = 2;
				$return['collumSize']['monat'] = 2;
				$return['collumSize']['jahr'] = 4;
				$return['collumSize']['wert'] = 10;


				//Klasse der Spalte --> Input_syle
				$return['collumClass'][1] = "jahr";
				$return['collumClass'][2] = "text";
				$return['collumClass'][3] = "status";
				$return['collumClass'][4] = "einheit";
				$return['collumClass'][5] = "nummer";
				$return['collumClass'][6] = "nummer";
				$return['collumClass'][7] = "nummer";
				$return['collumClass'][8] = "nummer";
				$return['collumClass'][9] = "nummer";
				$return['collumClass'][10] = "nummer";
				$return['collumClass'][11] = "nummer";
				$return['collumClass'][12] = "nummer";
				$return['collumClass'][13] = "nummer";
				$return['collumClass'][14] = "nummer";
				$return['collumClass'][15] = "nummer";
				$return['collumClass'][16] = "nummer";
				$return['collumClass'][17] = "nummer";
				$return['collumClass'][18] = "summe";
				
				break;
			
			case 'ableseprotokoll':
				//db-Tabelle
				$return['db'] = "ableseprotokoll";
				
				//Überschrift definieren
				$return['head'] = "Ableseprotokoll";
				
				//Breite der Tablle
				$return['width'] = "";
				
				//Optionen anzeigen
				$return['options'] = "";
				
				//Spaletenanzahl
				$return['collums'] = 11;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "Datum";
				$return['collumName'][2] = "Energiewert ORC-Anlage";
				$return['collumName'][3] = "Kesseleinsch&uuml;be";
				$return['collumName'][4] = "Z&auml;hler 1 ORC";
				$return['collumName'][5] = "Z&auml;hler 2 Spitzenlastkessel";
				$return['collumName'][6] = "Z&auml;hler 3 R&uuml;cklauf";
				$return['collumName'][7] = "Z&auml;hler 4 Heizung";
				$return['collumName'][8] = "Z&auml;hler 5 Netz";
				$return['collumName'][9] = "Z&auml;hler 6 Gas";
				$return['collumName'][10] = "Z&auml;hler 7";
				$return['collumName'][11] = "Unterschrift";
				
				//Einheiten der Spalten
				$return['collumUnit'][1] = "";
				$return['collumUnit'][2] = "[kWh]";
				$return['collumUnit'][3] = "";
				$return['collumUnit'][4] = "[MWh]";
				$return['collumUnit'][5] = "[MWh]";
				$return['collumUnit'][6] = "[MWh]";
				$return['collumUnit'][7] = "[MWh]";
				$return['collumUnit'][8] = "[MWh]";
				$return['collumUnit'][9] = "[MWh]";
				$return['collumUnit'][10] = "[MWh]";
				$return['collumUnit'][11] = "";

				
				//Spaltenbreite
				$return['collumSize']['tag'] = 2;
				$return['collumSize']['monat'] = 2;
				$return['collumSize']['jahr'] = 4;
				$return['collumSize']['stunde'] = 2;
				$return['collumSize']['minute'] = 2;
				$return['collumSize']['wert'] = 10;


				//Klasse der Spalte --> Input_syle
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "text";
				$return['collumClass'][3] = "text";
				$return['collumClass'][4] = "text";
				$return['collumClass'][5] = "text";
				$return['collumClass'][6] = "text";
				$return['collumClass'][7] = "text";
				$return['collumClass'][8] = "text";
				$return['collumClass'][9] = "text";
				$return['collumClass'][10] = "text";
				$return['collumClass'][11] = "text";
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
				$return['collumName'][4] = "Energiewert";
				$return['collumName'][5] = "";
				
				//Einheiten der Spalten
				$return['collumUnit'][1] = "";
				$return['collumUnit'][2] = "";
				$return['collumUnit'][3] = "";
				$return['collumUnit'][4] = "kWh";
				$return['collumUnit'][5] = "";

				//Spaltenbreite
				$return['collumSize']['tag'] = 2;
				$return['collumSize']['monat'] = 2;
				$return['collumSize']['jahr'] = 4;
				$return['collumSize']['stunde'] = 2;
				$return['collumSize']['minute'] = 2;
				$return['collumSize']['wert'] = 10;
				
				//Klasse der Spalte --> Input_syle
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "vortag";
				$return['collumClass'][3] = "text";
				$return['collumClass'][4] = "summe";
				$return['collumClass'][5] = "text";
				
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
				$return['head'] = "Anlieferung Hackschnitzel";
				
				//Breite der Tablle
				$return['width'] = "600";
				
				//Optionen anzeigen
				$return['options'] = true;
				
				//Spaletenanzahl
				$return['collums'] = 6;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "Datum";
				$return['collumName'][2] = "Wassergehalt";
				$return['collumName'][3] = "Leergewicht";
				$return['collumName'][4] = "Beladen";
				$return['collumName'][5] = "Lieferant";
				
				//Einheiten der Spalten
				$return['collumUnit'][1] = "";
				$return['collumUnit'][2] = "%";
				$return['collumUnit'][3] = "kg";
				$return['collumUnit'][4] = "kg";
				$return['collumUnit'][5] = "";

				//Spaltenbreite
				$return['collumSize']['tag'] = 2;
				$return['collumSize']['monat'] = 2;
				$return['collumSize']['jahr'] = 4;
				$return['collumSize']['stunde'] = 2;
				$return['collumSize']['minute'] = 2;
				$return['collumSize']['wert'] = 10;
				
				//Klasse der Spalte --> Input_syle
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "text";
				$return['collumClass'][3] = "text";
				$return['collumClass'][4] = "text";
				$return['collumClass'][5] = "lieferant";
				
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

				
				//Einheiten der Spalten
				$return['collumUnit'][1] = "";
				$return['collumUnit'][2] = "";
				$return['collumUnit'][3] = "";
				$return['collumUnit'][4] = "";
				$return['collumUnit'][5] = "";


				//Spaltenbreite
				$return['collumSize']['tag'] = 2;
				$return['collumSize']['monat'] = 2;
				$return['collumSize']['jahr'] = 4;
				$return['collumSize']['stunde'] = 2;
				$return['collumSize']['minute'] = 2;
				$return['collumSize']['wert'] = 10;
				
				//Klasse der Spalte --> Input_syle
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "vortag";
				$return['collumClass'][3] = "text";
				$return['collumClass'][4] = "summe";
				$return['collumClass'][5] = "text";
				
				break;
			
			case 'waermeerzeugung':
				//db-Tabelle
				$return['db'] = "waermeerzeugung";
				
				//Überschrift definieren
				$return['head'] = "W&auml;rmeerzeugung";
				
				//Breite der Tablle
				$return['width'] = "";
				
				//Optionen anzeigen
				$return['options'] = "";
				
				//Spaletenanzahl
				$return['collums'] = 23;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = 	"Datum";
				$return['collumName'][2] = 	"ORC Vortag	Z1";
				$return['collumName'][3] = 	"ORC Ablesetag Z1";
				$return['collumName'][4] = 	"ORC W&auml;mewert Z1";
				$return['collumName'][5] = 	"Spitzenlastkessel Vortag Z2";
				$return['collumName'][6] = 	"Spitzenlastkessel Ablesetag Z2";
				$return['collumName'][7] = 	"Spitzenlastkessel W&auml;mewert Z2";
				$return['collumName'][8] = 	"R&uuml;cklauf Vortag Z3";
				$return['collumName'][9] = 	"R&uuml;cklauf Ablesetag Z3";
				$return['collumName'][10] = "R&uuml;cklauf W&auml;mewert Z3";
				$return['collumName'][11] = "Heizung Vortag Z4";
				$return['collumName'][12] = "Heizung Ablesetag Z4";
				$return['collumName'][13] = "Heizung W&auml;mewert Z4";
				$return['collumName'][14] = "Netz Vortag Z5";
				$return['collumName'][15] = "Netz Ablesetag Z5";
				$return['collumName'][16] = "Netz W&auml;mewert Z5";
				$return['collumName'][17] = "Gas Vortag Z6";
				$return['collumName'][18] = "Gas Ablesetag Z6";
				$return['collumName'][19] = "Gas W&auml;mewert Z6";
				$return['collumName'][20] = "";
				$return['collumName'][21] = "";
				$return['collumName'][22] = "";
				$return['collumName'][23] = "";
				
				//Einheiten der Spalten
				$return['collumUnit'][1] = "";
				$return['collumUnit'][2] = "[MWh]";
				$return['collumUnit'][3] = "[MWh]";
				$return['collumUnit'][4] = "[MWh]";
				$return['collumUnit'][5] = "[MWh]";
				$return['collumUnit'][6] = "[MWh]";
				$return['collumUnit'][7] = "[MWh]";
				$return['collumUnit'][8] = "[MWh]";
				$return['collumUnit'][9] = "[MWh]";
				$return['collumUnit'][10] = "[MWh]";
				$return['collumUnit'][11] = "[MWh]";
				$return['collumUnit'][12] = "[MWh]";
				$return['collumUnit'][13] = "[MWh]";
				$return['collumUnit'][14] = "[MWh]";
				$return['collumUnit'][15] = "[MWh]";
				$return['collumUnit'][16] = "[MWh]";
				$return['collumUnit'][17] = "[MWh]";
				$return['collumUnit'][18] = "[MWh]";
				$return['collumUnit'][19] = "[MWh]";
				$return['collumUnit'][20] = "[MWh]";
				$return['collumUnit'][21] = "[MWh]";
				$return['collumUnit'][22] = "[MWh]";
				$return['collumUnit'][23] = "[MWh]";
				
				//Spaltenbreite
				$return['collumSize']['tag'] = 2;
				$return['collumSize']['monat'] = 2;
				$return['collumSize']['jahr'] = 4;
				$return['collumSize']['stunde'] = 2;
				$return['collumSize']['minute'] = 2;
				$return['collumSize']['wert'] = 10;


				//Klasse der Spalte --> Input_syle
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "text";
				$return['collumClass'][3] = "text";
				$return['collumClass'][4] = "text";
				$return['collumClass'][5] = "text";
				$return['collumClass'][6] = "text";
				$return['collumClass'][7] = "text";
				$return['collumClass'][8] = "text";
				$return['collumClass'][9] = "text";
				$return['collumClass'][10] = "text";
				$return['collumClass'][11] = "text";
				$return['collumClass'][12] = "text";
				$return['collumClass'][13] = "text";
				$return['collumClass'][14] = "text";
				$return['collumClass'][15] = "text";
				$return['collumClass'][16] = "text";
				$return['collumClass'][17] = "text";
				$return['collumClass'][18] = "text";
				$return['collumClass'][19] = "text";
				$return['collumClass'][20] = "text";
				$return['collumClass'][21] = "text";
				$return['collumClass'][22] = "text";
				$return['collumClass'][23] = "text";
				break;
			
			case 'swe':
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
				$return['collums'] = 15;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "Datum";
				$return['collumName'][2] = "Wassergehalt";
				$return['collumName'][3] = "Holzgewicht frisch";
				$return['collumName'][4] = "Menge";
				$return['collumName'][5] = "Holzgewicht atro";
				$return['collumName'][6] = "Heizwert";
				$return['collumName'][7] = "CO2-Einsparung";
				$return['collumName'][8] = "Alzinger";
				$return['collumName'][9] = "WBV KEH";
				$return['collumName'][10] = "Euringer";
				$return['collumName'][11] = "Sonstige";
				$return['collumName'][12] = "Asche";
				$return['collumName'][13] = "W&auml;rme";
				$return['collumName'][14] = "Strom";
				$return['collumName'][15] = "";
				
				//Einheiten der Spalten
				$return['collumUnit'][1] = "";
				$return['collumUnit'][2] = "%";
				$return['collumUnit'][3] = "kg";
				$return['collumUnit'][4] = "srm";
				$return['collumUnit'][5] = "kg";
				$return['collumUnit'][6] = "";
				$return['collumUnit'][7] = "t";
				$return['collumUnit'][8] = "srm";
				$return['collumUnit'][9] = "srm";
				$return['collumUnit'][10] = "srm";
				$return['collumUnit'][11] = "srm";
				$return['collumUnit'][12] = "";
				$return['collumUnit'][13] = "";
				$return['collumUnit'][14] = "";
				$return['collumUnit'][15] = "";

			
				//Spaltenbreite
				$return['collumSize']['tag'] = 2;
				$return['collumSize']['monat'] = 2;
				$return['collumSize']['jahr'] = 4;
				$return['collumSize']['wert'] = 10;


				//Klasse der Spalte --> Input_syle
				$return['collumClass'][1] = "datum";
				$return['collumClass'][2] = "text";
				$return['collumClass'][3] = "text";
				$return['collumClass'][4] = "text";
				$return['collumClass'][5] = "text";
				$return['collumClass'][6] = "text";
				$return['collumClass'][7] = "text";
				$return['collumClass'][8] = "text";
				$return['collumClass'][9] = "text";
				$return['collumClass'][10] = "text";
				$return['collumClass'][11] = "text";
				$return['collumClass'][12] = "text";
				$return['collumClass'][13] = "text";
				$return['collumClass'][14] = "text";
				$return['collumClass'][15] = "text";

				break;
			
			case 'soll':
				//db-Tabelle
				$return['db'] = "soll";
				
				//Überschrift definieren
				$return['head'] = "Sollwerte";
				
				//Breite der Tablle
				$return['width'] = "600";
				
				//Optionen anzeigen
				$return['options'] = true;
				
				//Spaletenanzahl
				$return['collums'] = 5;
				
				//Kopfzeilen der Spalten
				$return['collumName'][1] = "";
				$return['collumName'][2] = "Tabelle";
				$return['collumName'][3] = "Jahr";
				$return['collumName'][4] = "Wert";
				
				//Einheiten der Spalten
				$return['collumUnit'][1] = "";
				$return['collumUnit'][2] = "";
				$return['collumUnit'][3] = "";
				$return['collumUnit'][4] = "";

				//Spaltenbreite
				$return['collumSize']['tag'] = 2;
				$return['collumSize']['monat'] = 2;
				$return['collumSize']['jahr'] = 4;
				$return['collumSize']['stunde'] = 2;
				$return['collumSize']['minute'] = 2;
				$return['collumSize']['wert'] = 10;
				
				//Klasse der Spalte --> Input_syle
				$return['collumClass'][1] = "";
				$return['collumClass'][2] = "tabelle";
				$return['collumClass'][3] = "text";
				$return['collumClass'][4] = "text";
				
				break;
			
			default:
				$return = false;
				break;
					
		}
		
		return $return;
	}
}

?>