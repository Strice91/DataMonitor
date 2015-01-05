<?php
//Einbinden der Konfigurationsdatei
require_once "../common.php";
require_once "../session.php";

//Backslashes entfernen
System\Security::globalStripSlashes();

//Prüfen ob eine Action ausgewählt ist
if(isset($_POST['action']))
{
	$action = $_POST['action'];
	$execute = true;
}
elseif(isset($_GET['action']))
{
	$action = $_GET['action'];
	$execute = true;
}
else
{
	$execute = false;	
}


if($execute)
{
	switch($action)
	{
		/** 
		* Macht einen Datenbank Eintrag
		*  
		*/
		
		case 'mysql_insert':
			$table = mysql_real_escape_string($_POST['table']);
			
			$mysqlStructur = System\Database\MySQL::getStructur($table);
			
			//Auswählen ob eine ID bereits exsisitert
			if($_POST['id'] == "")
			{
				$i = 1;
			}
			else
			{
				$i = 0;	
			}
			
			// MySQL query zusammensetzen
		
			$sql = "INSERT INTO ".mysql_real_escape_string($mysqlStructur['db'])." SET";
			
			for($i;$i <= $mysqlStructur['collums']; $i++)
			{	
				if($mysqlStructur['collumName'][$i] == "date")
				{
					if(isset($_POST["date_stunde"]) && isset($_POST["date_minute"]))
					{
						$value = mysql_real_escape_string(mktime($_POST["date_stunde"], $_POST["date_minute"], 0, $_POST["date_mon"], $_POST["date_tag"], $_POST["date_jahr"]));	
					}
					else
					{
						$value = mysql_real_escape_string(mktime(0, 0, 0, $_POST["date_mon"], $_POST["date_tag"], $_POST["date_jahr"]));
					}
					
				}
				else
				{
					$value = mysql_real_escape_string($_POST[$mysqlStructur['collumName'][$i]]);
				}
				
				if($i == $mysqlStructur['collums'])
				{
					$sql .= " ".$mysqlStructur['collumName'][$i]."='".$value."'";
				}
				else
				{
					$sql .= " ".$mysqlStructur['collumName'][$i]."='".$value."',";	
				}
				
			}
			
			$DB->query($sql);
			$result = $DB->MySQLiObj->error;
			//echo $sql;
			
			$content = $mysqlStructur['db'];
			
			if($mysqlStructur['db'] == "soll")
			{
				$content = "insert";
			}
		
		
			header("Location: ".PROJECT_HTTP_ROOT."/main.php?content=".$content."");
			
			break;
			
		/** 
		* Ändert einen Datenbank Eintrag
		*  
		*/

		case 'mysql_update':
			$table = mysql_real_escape_string($_POST['table']);
			
			$mysqlStructur = System\Database\MySQL::getStructur($table);
			
			//Prüfe ob ID vorhanden ist
			
			$query = $DB->query("SELECT * FROM ".mysql_real_escape_string($mysqlStructur['db'])." WHERE id='".mysql_real_escape_string($_POST['id'])."'");	
			if(empty($query))
			{
				echo "Eintrag nicht gefunden!";
				exit;
			}
					
			// MySQL query zusammensetzen
			$sql = "UPDATE ".$table." SET";
			
			for($i = 1;$i <= $mysqlStructur['collums']; $i++)
			{	
				if($mysqlStructur['collumName'][$i] == "date")
				{
					if(isset($_POST["date_stunde"]) && isset($_POST["date_minute"]))
					{
						$value = mysql_real_escape_string(mktime($_POST["date_stunde"], $_POST["date_minute"], 0, $_POST["date_mon"], $_POST["date_tag"], $_POST["date_jahr"]));	
					}
					else
					{
						$value = mysql_real_escape_string(mktime(0, 0, 0, $_POST["date_mon"], $_POST["date_tag"], $_POST["date_jahr"]));
					}
					
				}
				else
				{
					$value = mysql_real_escape_string($_POST[$mysqlStructur['collumName'][$i]]);
				}
				
				if($i == $mysqlStructur['collums'])
				{
					$sql .= " ".$mysqlStructur['collumName'][$i]."='".$value."'";
				}
				else
				{
					$sql .= " ".$mysqlStructur['collumName'][$i]."='".$value."',";	
				}
				
			}
			
			$sql .= "WHERE id=".$_POST['id']."";
			
			//echo $sql;
			
			$DB->query($sql);
			$result = $DB->MySQLiObj->error;
			$content = $mysqlStructur['db'];
			
			if($mysqlStructur['db'] == "soll")
			{
				$content = "insert";
			}
			
			header("Location: ".PROJECT_HTTP_ROOT."/main.php?content=".$content."");
			
			
			break;
			
				
		/** 
		* Löscht einen Datenbank Eintrag
		*  
		*/
		
		case 'mysql_delete':
			$table = mysql_real_escape_string($_GET['table']);
			
			$mysqlStructur = System\Database\MySQL::getStructur($table);
			
			//Prüfe ob ID vorhanden ist
			
			$query = $DB->query("SELECT * FROM ".$mysqlStructur['db']." WHERE id='".mysql_real_escape_string($_GET['id'])."'");	
			if(empty($query))
			{
				echo "Eintrag nicht gefunden!";
				exit;
			}
			
			// MySQL query zusammensetzen
			$sql = "DELETE FROM ".$table." WHERE id=".$_GET['id']."";
			echo $sql;
			
			$DB->query($sql);
			$result = $DB->MySQLiObj->error;
			header("Location: ".PROJECT_HTTP_ROOT."/main.php?content=".$mysqlStructur['db']."");
			break;
		
	}
}
else
{
	echo "<script type='text/javascript'>document.location.href='".PROJECT_HTTP_ROOT."/main.php?content=home';</script>";	
	header("Location: ".PROJECT_HTTP_ROOT."/main.php?content=home");
}
	 
?>