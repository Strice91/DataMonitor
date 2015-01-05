<?php
//Namespace der Klasse
namespace System;

/**
* Die Option Klasse
*
*/
 
class Option
{
	/**
	*	Erstellt einen Bearbeiten Button
	*
	*	@param int ID des zu bearbeitenden Eintrags
	*
	*	@param varchar Tabelle in der etwas geändert wird
	*/
	
	public function edit($id, $table)
	{
		return "<a href='main.php?content=edit&table=".$table."&id=".$id."'><img src='".PROJECT_HTTP_ROOT."/images/menue/edit.png' title='Bearbeiten' /></a>";
	}
	
	
	/**
	*	Erstellt einen Löschen Button
	*
	*	@param int ID des zu löschenden Eintrags
	*
	*	@param varchar Tabelle aus der gelöscht wird
	*/
	
	public function delete($id, $table)
	{
		return "<a href='".PROJECT_HTTP_ROOT."/scripts/execute.php?action=mysql_delete&table=".$table."&id=".$id."' onClick=\"return confirm('Wollen Sie wirklich löschen?')\">
		<img src='".PROJECT_HTTP_ROOT."/images/menue/delete.png' title='L&ouml;schen' /></a>";
	}
	
	/**
	*	Erstellt einen Löschen Button
	*
	*	@param int ID des zu löschenden Eintrags
	*
	*	@param varchar Tabelle aus der gelöscht wird
	*/
	
	public function select($type, $graph=0, $start=0, $end=0)
	{
		switch($type)
		{
			case 'tables':
				$return = "<form name='sel_table' method='post' action='".$_SERVER['REQUEST_URI']."'>";
				$return .= 	"<select name='sel_table'>";
				$return .= 		"<option value='ableseprotokoll'>Ableseprotokoll</option>";
				$return .= 		"<option value='anlieferung'>Anlieferung</option>";
				$return .= 		"<option value='orc'>ORC-Anlage</option>";
				$return .= 		"<option value='kesseleinschuebe'>Kesseleinsch&uuml;be</option>";
				$return .= 		"<option value='lieferplan'>Lieferplan</option>";
				$return .= 		"<option value='hackschnitzel'>Hackschnitzel</option>";
				$return .= 		"<option value='sew'>Schubboden</option>";
				$return .= 		"<option value='waermeerzeugung'>W&auml;rmeerzeugung</option>";
				$return .= 		"<option value='soll'>Sollwert</option>";
				$return .= 	"</select>";
				$return .= 	"&nbsp <input name='submit' type='submit' value='Ausw&auml;hlen'>";
				$return .= "</form>";
				break;
				
			case 'date':
				$start = mktime(0,0,0,1,1,2011);
				$j = 1;
				$year = date('Y');
				$return = "<form name='date' method='post' action='".$_SERVER['REQUEST_URI']."'>";
				$return .= 	"<select name='sel_start'>";
				$return .= 		"<option value='".$start."' selected='selected'>Start</option>";

				for($i=$start; $i < time(); $i = mktime(0,0,0,$j,1,date('Y', $start)))
				{
					$j++;
					$return .= 	"<option value='".$i."'>".date('d.m.Y', $i)."</option>";	
				}
				
				$return .= 	"</select>";
				$return .=	"<select name='sel_end'>";
				
				$j=1;
				for($i=$start; $i < time(); $i=mktime(0,0,0,$j,1,date('Y', $start)))
				{
					$j++;
					$return .= 	"<option value='".$i."'>".date('d.m.Y', $i)."</option>";	
				}
				$return .= 		"<option value='".time()."' selected='selected'>Heute</option>";
				$return .= 	"</select>";
				$return .= 	"&nbsp <input name='submit' type='submit' value='Ausw&auml;hlen'>";
				$return .= "</form>";
				break;
				
			case 'jahr':
				$start = 2011;
				$j = 1;
				$year = date('Y');
				$return = "<form name='date' method='post' action='".$_SERVER['REQUEST_URI']."'>";
				$return .= 	"<select name='sel_jahr'>";
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
				$return .= 	"&nbsp <input name='submit' type='submit' value='Ausw&auml;hlen'>";
				$return .= "</form>";
				break;
				
			case 'graph':
				switch ($graph)
				{
					case'orc':
						$return = "<form name='graph' method='post' action='".PROJECT_HTTP_ROOT."/inc/graphs/orc_graph.php' onSubmit='window.open(\"\",\"show\",\"width=700, height=300, scrollbars=no, resizable=yes, menubar=no, location=no\");' target=\"show\">";
						$return .= "<input name='table' type='hidden' value='orc' />";
						$return .= "<input name='start' type='hidden' value='".$start."' />";
						$return .= "<input name='end' type='hidden' value='".$end."' />";
						$return .= 	"<select name='intervall'>";
						$return .= 		"<option value='tage'>Tage</option>";
						$return .= 		"<option value='wochen'>Wochen</option>";
						$return .= 		"<option value='monate'>Monate</option>";
						$return .= 		"<option value='jahre'>Jahre</option>";
						$return .= 	"</select>";
						$return .= 	"&nbsp <input name='submit' type='submit' value='Graph' >";
						$return .= "</form>";
					
						break;
						
					case'kesseleinschuebe':
						$return = "<form name='graph' method='post' action='".PROJECT_HTTP_ROOT."/inc/graphs/kessel_graph.php' onSubmit='window.open(\"\",\"show\",\"width=700, height=300, scrollbars=no, resizable=yes, menubar=no, location=no\");' target=\"show\">";
						$return .= "<input name='table' type='hidden' value='orc' />";
						$return .= "<input name='start' type='hidden' value='".$start."' />";
						$return .= "<input name='end' type='hidden' value='".$end."' />";
						$return .= 	"<select name='intervall'>";
						$return .= 		"<option value='tage'>Tage</option>";
						$return .= 		"<option value='wochen'>Wochen</option>";
						$return .= 		"<option value='monate'>Monate</option>";
						$return .= 		"<option value='jahre'>Jahre</option>";
						$return .= 	"</select>";
						$return .= 	"&nbsp <input name='submit' type='submit' value='Graph' >";
						$return .= "</form>";
						
						break;
						
					case'hackschnitzel':
						$return = "<form name='graph' method='post' action='".PROJECT_HTTP_ROOT."/inc/graphs/hackschnitzel_graph.php' onSubmit='window.open(\"\",\"show\",\"width=900, height=900, scrollbars=no, resizable=yes, menubar=no, location=no\");' target=\"show\">";
						$return .= "<input name='table' type='hidden' value='hackschnitzel' />";
						$return .= "<input name='start' type='hidden' value='".$start."' />";
						$return .= "<input name='end' type='hidden' value='".$end."' />";
						$return .= 	"<select name='intervall'>";
						$return .= 		"<option value='tage'>Tage</option>";
						$return .= 		"<option value='wochen'>Wochen</option>";
						$return .= 		"<option value='monate'>Monate</option>";
						$return .= 		"<option value='jahre'>Jahre</option>";
						$return .= 	"</select>";
						$return .= 	"&nbsp <input name='submit' type='submit' value='Graph' >";
						$return .= "</form>";
						
						break;
						
					case'waermeerzeugung':
						$return = "<form name='graph' method='post' action='".PROJECT_HTTP_ROOT."/inc/graphs/waerme_graph.php' onSubmit='window.open(\"\",\"show\",\"width=1300, height=600, scrollbars=no, resizable=yes, menubar=no, location=no\");' target=\"show\">";
						$return .= "<input name='table' type='hidden' value='hackschnitzel' />";
						$return .= "<input name='start' type='hidden' value='".$start."' />";
						$return .= "<input name='end' type='hidden' value='".$end."' />";
						$return .= 	"<select name='intervall'>";
						$return .= 		"<option value='tage'>Tage</option>";
						$return .= 		"<option value='wochen'>Wochen</option>";
						$return .= 		"<option value='monate'>Monate</option>";
						$return .= 		"<option value='jahre'>Jahre</option>";
						$return .= 	"</select>";
						$return .= 	"&nbsp <input name='submit' type='submit' value='Graph' >";
						$return .= "</form>";
						
						break;
						
					default:
						$return = false;
						break;
				}
				break;
		
			default:
				exit;
		}
		
		return $return;
	}
}