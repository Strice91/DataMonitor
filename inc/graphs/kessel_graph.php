<?php 
//Datenbankklasse
require_once "../../inc/classes/DB/class.MySQL.php";

#Datenbanksettings und Systemeinstellungen
require_once '../../Settings.php';

#Datenbankobjekt erstellen (wenn nicht vorhanden)
if(!isset($GLOBALS['DB']))
{
	$DB = new System\Database\MySQL(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);	
}

$table = $_POST['table'];
$start = $_POST['start'];
$end = $_POST['end'];
$intervall = $_POST['intervall'];


if($end != 0)
{
	$db_table = $GLOBALS['DB']->query("SELECT * FROM ".$table." WHERE date > ".$start." && date < ".$end." ORDER BY date ASC");
}
else
{
	$db_table = $GLOBALS['DB']->query("SELECT * FROM ".$table." WHERE id > ".$start." ORDER BY date ASC");
}

$sollwerte = $GLOBALS['DB']->query("SELECT * FROM soll WHERE tabelle = 'kesseleinschuebe'");


// content="text/plain; charset=utf-8"
require_once ('../../extLibs/graph/src/jpgraph.php');
require_once ('../../extLibs/graph/src/jpgraph_bar.php');
require_once ('../../extLibs/graph/src/jpgraph_line.php');


//data
$ydata = array();
$ydata2 = array();
$datum = array();
$sollwert = array();
$soll = array();

foreach($sollwerte as $s)
{
	$sollwert[$s['jahr']] = $s['wert'];	
}

$i = 0;
$j = 0;
$z = 0;

$summe1 = 0;
$summe2 = 0;
$anzahl = count($db_table);

foreach($db_table as $values)
{
	if($intervall == 'tage')
	{
		$ydata[] = $values['energiewert'];
	  	$ydata2[] = $values['ablesetag'];
		$datum[] = date('j.n', $values['date']);
		if(isset($sollwert[date('Y',$values['date'])]))
		{
			$soll[] = $sollwert[date('Y',$values['date'])];
		}
		
	}
	elseif($intervall == 'wochen')
	{	
		
		if($j > 6)
		{
			$i++;
			$j = 0;
		}
		
		if($j == 0)
		{
			$ydata[$i] = 0;
			$ydata2[$i] = 0;
			$datum[$i] = 0;
		}
		
		$ydata[$i] = $ydata[$i] + $values['energiewert'];
	  	$ydata2[$i] = $values['ablesetag'];
		$datum[$i] = date('j.n', $values['date']);
		$j++;
		
		if(isset($sollwert[date('Y',$values['date'])]))
		{
			$soll[$i] = $sollwert[date('Y',$values['date'])];
		}
	}
	elseif($intervall == 'monate')
	{
		if($z == 0)
		{
			$mon = date('n', $values['date']);
		}
		$z++;
		if($mon != date('n', $values['date']))
		{
			$i++;
			$j = 0;
			$mon = date('n', $values['date']);
		}
		
		if($j == 0)
		{
			$ydata[$i] = 0;
			$ydata2[$i] = 0;
			$datum[$i] = 0;
		}
		
		$ydata[$i] = $ydata[$i] + $values['energiewert'];
	  	$ydata2[$i] = $values['ablesetag'];
		$datum[$i] = date('m.Y', $values['date']);
		$j++;
		
		if(isset($sollwert[date('Y',$values['date'])]))
		{
			$soll[$i] = $sollwert[date('Y',$values['date'])];
		}
	}
	elseif($intervall == 'jahre')
	{
		if($z == 0)
		{
			$jahr = date('Y', $values['date']);
			$z++;
		}
		
		if($jahr != date('Y', $values['date']))
		{
			$i++;
			$j = 0;
			$z = 0;
			//$jahr = date('Y', $values['date']);
		}
		
		if($j == 0)
		{
			$ydata[$i] = 0;
			$ydata2[$i] = 0;
			$datum[$i] = 0;
		}
		
		$ydata[$i] = $ydata[$i] + $values['energiewert'];
	  	$ydata2[$i] = $values['ablesetag'];
		$datum[$i] = date('Y', $values['date']);
		$j++;
		
		if(isset($sollwert[date('Y',$values['date'])]))
		{
			$soll[$i] = $sollwert[date('Y',$values['date'])];
		}
	}
	  
}

// Create the graph. 
$graph = new Graph(600,250);	
$graph->SetScale('datlin');
$graph->SetY2Scale('lin');
$graph->yaxis->SetColor('darkgreen');
$graph->SetMarginColor('white');


// Adjust the margin slightly so that we use the 
// entire area (since we don't use a frame)
$graph->SetMargin(70,70,30,70);

// Box around plotarea
$graph->SetBox(); 

// No frame around the image
$graph->SetFrame(false);

// Setup the tab title
$graph->tabtitle->Set('Kesseleinschübe');
$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,10);

// Setup the X and Y grid
$graph->ygrid->SetFill(true,'#DDDDDD@0.5','#BBBBBB@0.5');
$graph->ygrid->SetLineStyle('dashed');
$graph->ygrid->SetColor('gray');

//$graph->yaxis->title->Set("Energiewerte");
//$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle('dashed');
$graph->xgrid->SetColor('gray');

//$graph->xaxis->title->Set("X-title");
//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);


// Setup month as labels on the X-axis
$graph->xaxis->SetTickLabels($datum);
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
$graph->xaxis->SetLabelAngle(0);


// Create a bar pot
$bplot = new BarPlot($ydata);
$bplot->SetWidth(0.5);
$fcol='#143D55';
$tcol='#68B0D7';

$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);

// Set line weigth to 0 so that there are no border
// around each bar
$bplot->SetWeight(0);

$graph->Add($bplot);

$txt=new Text("Einschübe");
$graph->AddText($txt);
$txt->SetFont(FF_ARIAL,FS_NORMAL,8);
$txt->SetColor("#143D55");
$txt->SetPos(535,215);

$txt2=new Text("pro Tag");
$graph->AddText($txt2);
$txt2->SetFont(FF_ARIAL,FS_NORMAL,8);
$txt2->SetColor("#143D55");
$txt2->SetPos(535,227);

$txt4=new Text("Zähler");
$graph->AddText($txt4);
$txt4->SetFont(FF_ARIAL,FS_NORMAL,8);
$txt4->SetColor("darkgreen");
$txt4->SetPos(20,200);

$txt5=new Text("stand");
$graph->AddText($txt5);
$txt5->SetFont(FF_ARIAL,FS_NORMAL,8);
$txt5->SetColor("darkgreen");
$txt5->SetPos(20,212);


// Create filled line plot
$lplot = new LinePlot($ydata2);
$lplot->SetFillColor('darkgreen@0.7');
$lplot->SetColor('darkgreen@0.7');
$lplot->SetBarCenter();

/*$lplot->mark->SetType(MARK_SQUARE);
$lplot->mark->SetColor('blue@0.5');
$lplot->mark->SetFillColor('lightblue');
$lplot->mark->SetSize(6);*/

// Soll  linie 
/*
if(!empty($soll))
{
	$lplot2 = new LinePlot($soll);
	$lplot2->SetColor('RED');
	$lplot2->SetBarCenter();
	$lplot2->SetLegend('Sollwert');
	$lplot2->SetWeight(5);
	//$lplot2->SetFillColor('red@0.95');
	$graph->Add($lplot2);
}*/

//Set Legend
$lplot->SetLegend('Zählerstand');



$graph->Add($lplot);
$graph->AddY2($bplot);



// .. and finally send it back to the browser
$graph->Stroke();

?>
