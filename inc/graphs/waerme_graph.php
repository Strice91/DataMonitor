<?php 
//Datenbankklasse
require_once "../../inc/classes/DB/class.MySQL.php";

#Datenbanksettings und Systemeinstellungen
require_once '../../Settings.php';

// content="text/plain; charset=utf-8"
require_once ('../../extLibs/graph/src/jpgraph.php');
require_once ('../../extLibs/graph/src/jpgraph_bar.php');
require_once ('../../extLibs/graph/src/jpgraph_line.php');
require_once ('../../extLibs/graph/src/jpgraph_mgraph.php');
require_once ('../../extLibs/graph/src/jpgraph_utils.inc.php');
require_once ('../../extLibs/graph/src/jpgraph_scatter.php');

#Datenbankobjekt erstellen (wenn nicht vorhanden)
if(!isset($GLOBALS['DB']))
{
	$DB = new System\Database\MySQL(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);	
}

$table = $_POST['table'];
$start = $_POST['start'];
$end = $_POST['end'];
$intervall = $_POST['intervall'];
 
$z1 = array();

if($end != 0)
{
	$db_table = $GLOBALS['DB']->query("SELECT * FROM waermeerzeugung WHERE date > ".$start." && date < ".$end." ORDER BY date ASC");
}
else
{
	$db_table = $GLOBALS['DB']->query("SELECT * FROM waermeerzeugung WHERE id > ".$start." ORDER BY date ASC");
}

$sollwerte = $GLOBALS['DB']->query("SELECT * FROM soll WHERE tabelle = 'waermeerzeugung'");
$i = 0;
$j = 0;
$z = 0;

foreach($db_table as $values)
{
	if($intervall == 'tage')
	{
		$z1[] = $values['z1_ablesetag'];
	  	$z1_w[] = $values['z1_waerme'];
		$z2[] = $values['z2_ablesetag'];
		$z2_w[] = $values['z2_waerme'];
		$z3[] = $values['z3_ablesetag'];
		$z3_w[] = $values['z3_waerme'];
		$z4[] = $values['z4_ablesetag'];
		$z4_w[] = $values['z4_waerme'];
		$z5[] = $values['z5_ablesetag'];
		$z5_w[] = $values['z5_waerme'];
		$z6[] = $values['z6_ablesetag'];
		$z6_w[] = $values['z6_waerme']/100;
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
			$z1[$i] = 0;
			$z1_w[$i] = 0;
			$z2[$i] = 0;
			$z2_w[$i] = 0;
			$z3[$i] = 0;
			$z3_w[$i] = 0;
			$z4[$i] = 0;
			$z4_w[$i] = 0;
			$z5[$i] = 0;
			$z5_w[$i] = 0;
			$z6[$i] = 0;
			$z6_w[$i] = 0;
			$datum[$i] = 0;
		}
		
		$z1[$i] = $values['z1_ablesetag'];
	  	$z1_w[$i] = $z1_w[$i] + $values['z1_waerme'];
		$z2[$i] = $values['z2_ablesetag'];
		$z2_w[$i] = $z2_w[$i] + $values['z2_waerme'];
		$z3[$i] = $values['z3_ablesetag'];
		$z3_w[$i] = $z3_w[$i] + $values['z3_waerme'];
		$z4[$i] = $values['z4_ablesetag'];
		$z4_w[$i] = $z4_w[$i] + $values['z4_waerme'];
		$z5[$i] = $values['z5_ablesetag'];
		$z5_w[$i] = $z5_w[$i] + $values['z5_waerme'];
		$z6[$i] = $values['z6_ablesetag'];
		$z6_w[$i] = $z6_w[$i] + $values['z6_waerme']/100;
		$datum[] = date('j.n', $values['date']);
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
			$z = 0;
		}
		
		if($j == 0)
		{
			$z1[$i] = 0;
			$z1_w[$i] = 0;
			$z2[$i] = 0;
			$z2_w[$i] = 0;
			$z3[$i] = 0;
			$z3_w[$i] = 0;
			$z4[$i] = 0;
			$z4_w[$i] = 0;
			$z5[$i] = 0;
			$z5_w[$i] = 0;
			$z6[$i] = 0;
			$z6_w[$i] = 0;
			$datum[$i] = 0;
		}
		
		$z1[$i] = $values['z1_ablesetag'];
	  	$z1_w[$i] = $z1_w[$i] + $values['z1_waerme'];
		$z2[$i] = $values['z2_ablesetag'];
		$z2_w[$i] = $z2_w[$i] + $values['z2_waerme'];
		$z3[$i] = $values['z3_ablesetag'];
		$z3_w[$i] = $z3_w[$i] + $values['z3_waerme'];
		$z4[$i] = $values['z4_ablesetag'];
		$z4_w[$i] = $z4_w[$i] + $values['z4_waerme'];
		$z5[$i] = $values['z5_ablesetag'];
		$z5_w[$i] = $z5_w[$i] + $values['z5_waerme'];
		$z6[$i] = $values['z6_ablesetag'];
		$z6_w[$i] = $z6_w[$i] + $values['z6_waerme']/100;
		$datum[] = date('j.n', $values['date']);
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
		}
		
		if($j == 0)
		{
			$z1[$i] = 0;
			$z1_w[$i] = 0;
			$z2[$i] = 0;
			$z2_w[$i] = 0;
			$z3[$i] = 0;
			$z3_w[$i] = 0;
			$z4[$i] = 0;
			$z4_w[$i] = 0;
			$z5[$i] = 0;
			$z5_w[$i] = 0;
			$z6[$i] = 0;
			$z6_w[$i] = 0;
			$datum[$i] = 0;
		}
		
		$z1[$i] = $values['z1_ablesetag'];
	  	$z1_w[$i] = $z1_w[$i] + $values['z1_waerme'];
		$z2[$i] = $values['z2_ablesetag'];
		$z2_w[$i] = $z2_w[$i] + $values['z2_waerme'];
		$z3[$i] = $values['z3_ablesetag'];
		$z3_w[$i] = $z3_w[$i] + $values['z3_waerme'];
		$z4[$i] = $values['z4_ablesetag'];
		$z4_w[$i] = $z4_w[$i] + $values['z4_waerme'];
		$z5[$i] = $values['z5_ablesetag'];
		$z5_w[$i] = $z5_w[$i] + $values['z5_waerme'];
		$z6[$i] = $values['z6_ablesetag'];
		$z6_w[$i] = $z6_w[$i] + $values['z6_waerme']/100;
		$datum[] = date('j.n', $values['date']);
		$j++;
		
		if(isset($sollwert[date('Y',$values['date'])]))
		{
			$soll[$i] = $sollwert[date('Y',$values['date'])];
		}
	}
}

// Create the graph. 
$graph = new Graph(1250,580);	
$graph->SetScale("lin");
$graph->img->SetMargin(60,330,40,70);
$graph->xaxis->SetFont(FF_FONT1,FS_BOLD);
$graph->title->Set("Wärmeerzeugung");
$graph->xaxis->SetTickLabels($datum);

$graph->SetYScale(0,'lin');
$graph->SetYScale(1,'lin');
$graph->SetYScale(2,'lin');
$graph->SetYScale(3,'lin');
$graph->SetYScale(4,'lin');
$graph->SetYScale(5,'lin');

//Z1
$lineplot1=new LinePlot($z1);
$lineplot1->SetLegend("Z1 ORC");
$lineplot1->SetColor("#800000");
$lineplot1->SetWeight(5);

$barplot1=new BarPlot($z1_w);
$barplot1->SetLegend("Wärmewert ORC");
$barplot1->SetColor("#800000");

//Z2
$lineplot2=new LinePlot($z2);
$lineplot2->SetLegend("Z2 Spitzenlastk.");
$lineplot2->SetColor("#CC0099");
$lineplot2->SetWeight(5);

$barplot2=new BarPlot($z2_w);
$barplot2->SetLegend("Wärmewert Spitzenlastk.");
$barplot2->SetColor("#CC0099");

//Z3
$lineplot3=new LinePlot($z3);
$lineplot3->SetLegend("Z3 Rücklauf");
$lineplot3->SetColor("#0099FF");
$lineplot3->SetWeight(5);

$barplot3=new BarPlot($z3_w);
$barplot3->SetLegend("Wärmewert Rücklauf");
$barplot3->SetColor("#0099FF");

//Z4
$lineplot4=new LinePlot($z4);
$lineplot4->SetLegend("Z4 Heizung");
$lineplot4->SetColor("#000099");
$lineplot4->SetWeight(5);

$barplot4=new BarPlot($z4_w);
$barplot4->SetLegend("Wärmewert Heizung");
$barplot4->SetColor("#000099");

//Z5
$lineplot5=new LinePlot($z5);
$lineplot5->SetLegend("Z5 Netz");
$lineplot5->SetColor("#006600");
$lineplot5->SetWeight(5);

$barplot5=new BarPlot($z5_w);
$barplot5->SetLegend("Wärmewert Netz");
$barplot5->SetColor("#006600");

//Z6
$lineplot6=new LinePlot($z6);
$lineplot6->SetLegend("Z6 Gas");
$lineplot6->SetColor("#F9DA00");
$lineplot6->SetWeight(5);

$barplot6=new BarPlot($z6_w);
$barplot6->SetLegend("Verbrauch Gas");
$barplot6->SetColor("#F9DA00");

//GroupBar

$gbplot = new GroupBarPlot(array($barplot1,$barplot2,$barplot3,$barplot4,$barplot5,$barplot6));

// Add the plot to the graph
$graph->AddY(0,$lineplot1);
$graph->AddY(1,$lineplot2);
$graph->AddY(2,$lineplot3);
$graph->AddY(3,$lineplot4);
$graph->AddY(4,$lineplot5);
$graph->AddY(5,$lineplot6);
$graph->Add($gbplot);


$graph->ynaxis[0]->SetColor('#CC0099');
$graph->ynaxis[1]->SetColor('#CC0099');
$graph->ynaxis[2]->SetColor('#0099FF');
$graph->ynaxis[3]->SetColor('#000099');
$graph->ynaxis[4]->SetColor('#006600');
$graph->ynaxis[5]->SetColor('#F9DA00');

// Display the graph
$graph->Stroke();
?>
