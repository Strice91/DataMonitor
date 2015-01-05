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

//bar1
$wassergehalt=array();
//bar2
$holz_frisch=array();
$holz_atro= array();
//bar3
$lieferant1=array();
$lieferant2=array();
$lieferant3=array();
$sonstige = array();
$menge=array();
$co2 = array();

//line1
$heizwert=array();
$asche= array();
$waerme = array();
$strom = array();

//foreach ($menge as &$y) { $y -=10; }


if($end != 0)
{
	$db_table = $GLOBALS['DB']->query("SELECT * FROM ".$table." WHERE date > ".$start." && date < ".$end." ORDER BY date ASC");
}
else
{
	$db_table = $GLOBALS['DB']->query("SELECT * FROM ".$table." WHERE id > ".$start." ORDER BY date ASC");
}

$sollwerte = $GLOBALS['DB']->query("SELECT * FROM soll WHERE tabelle = 'hackschnitzel'");
$i = 0;
$j = 0;
$z = 0;
foreach($db_table as $values)
{
	if($intervall == 'tage')
	{
		$wassergehalt[] = $values['wassergehalt'];
	  	$holz_frisch[] = $values['holzgewicht_frisch'];
		$holz_atro[] = $values['holzgewicht_atro'];
		$lieferant1[] = $values['lieferant1'];
		$lieferant2[] = $values['lieferant2'];
		$lieferant3[] = $values['lieferant3'];
		$sonstige[] = $values['sonstige'];
		$menge[] = $values['menge'];
		$heizwert[] = $values['heizwert']*1000;
		$co2[] = $values['co2'];
		$asche[] = $values['asche'];
		$waerme[] = $values['waerme'];
		$strom[] = $values['strom'];
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
			$wassergehalt[$i] = 0;
			$holz_frisch[$i] = 0;
			$holz_atro[$i] = 0;
			$lieferant1[$i] = 0;
			$lieferant2[$i] = 0;
			$lieferant3[$i] = 0;
			$sonstige[$i] = 0;
			$menge[$i] = 0;
			$heizwert[$i] = 0;
			$co2[$i] = 0;
			$asche[$i] = 0;
			$waerme[$i] = 0;
			$strom[$i] = 0;
			$datum[$i] = 0;
		}
		
		$wassergehalt[$i] = $wassergehalt[$i] + $values['wassergehalt'];
	  	$holz_frisch[$i] = $holz_frisch[$i] + $values['holzgewicht_frisch'];
		$holz_atro[$i] = $holz_atro[$i] + $values['holzgewicht_atro'];
		$lieferant1[$i] = $lieferant1[$i] + $values['lieferant1'];
		$lieferant2[$i] = $lieferant2[$i] + $values['lieferant2'];
		$lieferant3[$i] = $lieferant3[$i] + $values['lieferant3'];
		$sonstige[$i] = $sonstige[$i] + $values['sonstige'];
		$menge[$i] = $menge[$i] + $values['menge'];
		$heizwert[$i] = $heizwert[$i] + $values['heizwert']*1000;
		$co2[$i] = $co2[$i] + $values['co2'];
		$asche[$i] = $asche[$i] + $values['asche'];
		$waerme[$i] = $waerme[$i] + $values['waerme'];
		$strom[$i] = $strom[$i] + $values['strom'];
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
			$wassergehalt[$i] = $wassergehalt[$i]/$j;
			$i++;
			$j = 0;
			$z = 0;
			
		}
		
		if($j == 0)
		{
			$wassergehalt[$i] = 0;
			$holz_frisch[$i] = 0;
			$holz_atro[$i] = 0;
			$lieferant1[$i] = 0;
			$lieferant2[$i] = 0;
			$lieferant3[$i] = 0;
			$sonstige[$i] = 0;
			$menge[$i] = 0;
			$heizwert[$i] = 0;
			$co2[$i] = 0;
			$asche[$i] = 0;
			$waerme[$i] = 0;
			$strom[$i] = 0;
			$datum[$i] = 0;
		}
		
		$wassergehalt[$i] = $wassergehalt[$i] + $values['wassergehalt'];
	  	$holz_frisch[$i] = $holz_frisch[$i] + $values['holzgewicht_frisch'];
		$holz_atro[$i] = $holz_atro[$i] + $values['holzgewicht_atro'];
		$lieferant1[$i] = $lieferant1[$i] + $values['lieferant1'];
		$lieferant2[$i] = $lieferant2[$i] + $values['lieferant2'];
		$lieferant3[$i] = $lieferant3[$i] + $values['lieferant3'];
		$sonstige[$i] = $sonstige[$i] + $values['sonstige'];
		$menge[$i] = $menge[$i] + $values['menge'];
		$heizwert[$i] = $heizwert[$i] + $values['heizwert']*1000;
		$co2[$i] = $co2[$i] + $values['co2'];
		$asche[$i] = $asche[$i] + $values['asche'];
		$waerme[$i] = $waerme[$i] + $values['waerme'];
		$strom[$i] = $strom[$i] + $values['strom'];
		$datum[$i] = date('j.n', $values['date']);
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
			$wassergehalt[$i] = 0;
			$holz_frisch[$i] = 0;
			$holz_atro[$i] = 0;
			$lieferant1[$i] = 0;
			$lieferant2[$i] = 0;
			$lieferant3[$i] = 0;
			$sonstige[$i] = 0;
			$menge[$i] = 0;
			$heizwert[$i] = 0;
			$co2[$i] = 0;
			$asche[$i] = 0;
			$waerme[$i] = 0;
			$strom[$i] = 0;
			$datum[$i] = 0;
		}
		
		$wassergehalt[$i] = $wassergehalt[$i] + $values['wassergehalt'];
	  	$holz_frisch[$i] = $holz_frisch[$i] + $values['holzgewicht_frisch'];
		$holz_atro[$i] = $holz_atro[$i] + $values['holzgewicht_atro'];
		$lieferant1[$i] = $lieferant1[$i] + $values['lieferant1'];
		$lieferant2[$i] = $lieferant2[$i] + $values['lieferant2'];
		$lieferant3[$i] = $lieferant3[$i] + $values['lieferant3'];
		$sonstige[$i] = $sonstige[$i] + $values['sonstige'];
		$menge[$i] = $menge[$i] + $values['menge'];
		$heizwert[$i] = $heizwert[$i] + $values['heizwert']*1000;
		$co2[$i] = $co2[$i] + $values['co2'];
		$asche[$i] = $asche[$i] + $values['asche'];
		$waerme[$i] = $waerme[$i] + $values['waerme'];
		$strom[$i] = $strom[$i] + $values['strom'];
		$datum[$i] = date('j.n', $values['date']);
		$j++;
		
		if(isset($sollwert[date('Y',$values['date'])]))
		{
			$soll[$i] = $sollwert[date('Y',$values['date'])];
		}
	}
	  
}

if($intervall == 'wochen')
{
	for($y=0; $y <= $i; $y++)
	{
		$wassergehalt[$y] = $wassergehalt[$y]/7;
	}
}
elseif($intervall == 'monate')
{
	if($j != 0)
	{
		$wassergehalt[$i] = $wassergehalt[$i]/$j;
	}
}
elseif($intervall == 'jahre')
{
	if($j != 0)
	{
		$wassergehalt[$i] = $wassergehalt[$i]/$j;
	}
}

// Create the graph2. These two calls are always required
$graph = new graph(850,250);
$graph->SetScale("lin");
//$graph->SetY2Scale("lin");
//$graph->SetY2OrderBack(false);

$graph->SetMargin(55,50,20,5);

//$theme_class = new UniversalTheme;
//$graph->SetTheme($theme_class);

//$graph->yaxis->SetTickPositions(array(0,50,100,150,200,250,300,350), array(25,75,125,175,275,325));
//$graph->y2axis->SetTickPositions(array(30,40,50,60,70,80,90));

$graph->SetBox(false);

$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels($datum);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$txt=new Text("[srm]");
$graph->AddText($txt);
$txt->SetFont(FF_ARIAL,FS_NORMAL,8);
$txt->SetColor("#143D55");
$txt->SetPos(10,190);


//$graph->yaxis->SetLabelFormat('%3d%%');
// Setup month as labels on the X-axis

// Create the bar plots

$b2plot = new BarPlot($menge);
$b3plot = new BarPlot($lieferant1);
$b4plot = new BarPlot($lieferant2);
$b5plot = new BarPlot($lieferant3);
$b6plot = new BarPlot($sonstige);

// Create the grouped bar plot
$gbbplot = new AccBarPlot(array($b3plot,$b4plot,$b5plot,$b6plot));
$gbplot = new GroupBarPlot(array($b2plot,$gbbplot));

// ...and add it to the graph2
$graph->Add($gbplot);

$b2plot->SetColor("#FFCC33");
$b2plot->SetFillColor("#FFCC33");
$b2plot->SetLegend("Menge");

$b3plot->SetColor("#8B008B");
$b3plot->SetFillColor("#8B008B");
$b3plot->SetLegend("Alzinger");

$b4plot->SetColor("#DA70D6");
$b4plot->SetFillColor("#DA70D6");
$b4plot->SetLegend("WBV");

$b5plot->SetColor("#9370DB");
$b5plot->SetFillColor("#9370DB");
$b5plot->SetLegend("Euringer");

$b6plot->SetColor("#9370DB");
$b6plot->SetFillColor("#9370DB");
$b6plot->SetLegend("Sonstige");

$graph->legend->SetFrameWeight(1);
$graph->legend->SetColumns(6);
$graph->legend->SetColor('#4E4E4E','#00A78A');

/*$band = new PlotBand(VERTICAL,BAND_RDIAG,11,"max",'khaki4');
$band->ShowFrame(true);
$band->SetOrder(DEPTH_BACK);
$graph->Add($band);*/

$graph->title->Set("Hackschnitzelumschlag");


// Create the graph2. These two calls are always required
$graph2 = new graph(850,210);
$graph2->SetScale("lin");
$graph2->SetY2Scale("lin");
//$graph2->SetY2OrderBack(false);

$graph2->SetMargin(55,50,5,20);

//$theme_class = new UniversalTheme;
//$graph2->SetTheme($theme_class);

//$graph2->yaxis->SetTickPositions(array(0,50,100,150,200,250,300,350), array(25,75,125,175,275,325));
//$graph2->y2axis->SetTickPositions(array(30,40,50,60,70,80,90));

$graph2->SetBox(false);

$graph2->ygrid->SetFill(false);
$graph2->xaxis->SetTickLabels($datum);
$graph2->yaxis->HideLine(false);
$graph2->yaxis->HideTicks(false,false);
$graph2->yaxis->SetLabelFormat('%3d%%');
// Setup month as labels on the X-axis

$txt2=new Text("[t]");
$graph2->AddText($txt2);
$txt2->SetFont(FF_ARIAL,FS_NORMAL,8);
$txt2->SetColor("#143D55");
$txt2->SetPos(820,160);

// Create the bar plots
$sp1 = new ScatterPlot($wassergehalt);
$sp1->mark->SetType(MARK_SQUARE);
$sp1->mark->SetFillColor("blue");
$sp1->SetImpuls();
$sp1->SetColor("blue");
$sp1->SetWeight(1);
$sp1->mark->SetWidth(3);
$sp1->SetLegend("Wassergehalt");

$b2plot2 = new BarPlot($holz_frisch);
$b3plot2 = new BarPlot($holz_atro);
$b4plot2 = new BarPlot($co2);

// Create the grouped bar plot
$gbplot2 = new GroupBarPlot(array($b2plot2,$b3plot2,$b4plot2));

// ...and add it to the graph2
$graph2->AddY2($gbplot2);
$graph2->Add($sp1);


$b2plot2->SetColor("#FFCC33");
$b2plot2->SetFillColor("#FFCC33");
$b2plot2->SetLegend("Holzgewicht frisch");

$b3plot2->SetColor("#8B008B");
$b3plot2->SetFillColor("#8B008B");
$b3plot2->SetLegend("Holzgewicht atro");

$b4plot2->SetColor("#DA70D6");
$b4plot2->SetFillColor("#DA70D6");
$b4plot2->SetLegend("CO² Einsparung");

$graph2->legend->SetFrameWeight(1);
$graph2->legend->SetColumns(6);
$graph2->legend->SetColor('#4E4E4E','#00A78A');

/*$band = new PlotBand(VERTICAL,BAND_RDIAG,11,"max",'khaki4');
$band->ShowFrame(true);
$band->SetOrder(DEPTH_BACK);
$graph2->Add($band);*/


// Create the graph2. These two calls are always required
$graph3 = new graph(800,210);
$graph3->SetScale("lin");
$graph3->SetY2Scale("lin");
//$graph3->SetY2OrderBack(false);

$graph3->SetMargin(150,50,5,5);

//$theme_class = new UniversalTheme;
//$graph3->SetTheme($theme_class);

//$graph3->yaxis->SetTickPositions(array(0,50,100,150,200,250,300,350), array(25,75,125,175,275,325));
//$graph3->y2axis->SetTickPositions(array(30,40,50,60,70,80,90));

$graph3->SetBox(false);

$graph3->ygrid->SetFill(false);
$graph3->xaxis->SetTickLabels($datum);
$graph3->yaxis->HideLine(false);
$graph3->yaxis->HideTicks(false,false);

$txt3=new Text("[t]");
$graph3->AddText($txt3);
$txt3->SetFont(FF_ARIAL,FS_NORMAL,8);
$txt3->SetColor("#143D55");
$txt3->SetPos(770,160);

// Create the bar plots
$l1plot3 = new LinePlot($heizwert);
$l3plot3 = new LinePlot($waerme);
$l4plot3 = new LinePlot($strom);

$sp3 = new ScatterPlot($asche);
$sp3->mark->SetType(MARK_SQUARE);
$sp3->SetImpuls();
$sp3->mark->SetFillColor("#999999");
$sp3->SetLegend("Asche");

// ...and add it to the graph2
$graph3->Add($l1plot3);
$graph3->Add($l3plot3);
$graph3->Add($l4plot3);
$graph3->AddY2($sp3);

$l1plot3->SetColor("#FFCC33");
$l1plot3->SetLegend("Heizwert");

$l3plot3->SetColor("#990000");
$l3plot3->SetLegend("Wärme");

$l4plot3->SetColor("#0066FF");
$l4plot3->SetLegend("Strom");

$graph3->legend->SetFrameWeight(1);
$graph3->legend->SetColumns(6);
$graph3->legend->SetColor('#4E4E4E','#00A78A');
$graph3->legend->SetPos(0,0,'left','left');
$graph3->legend->SetColumns(1);

/*$band = new PlotBand(VERTICAL,BAND_RDIAG,11,"max",'khaki4');
$band->ShowFrame(true);
$band->SetOrder(DEPTH_BACK);
$graph3->Add($band);*/


//-----------------------
// Create a multigraph
//----------------------
$mgraph = new MGraph();
$mgraph->SetMargin(2,2,2,2);
$mgraph->SetFrame(true,'darkgray',2);
$mgraph->Add($graph);
$mgraph->Add($graph2,0,245);
$mgraph->Add($graph3,50,460);
$mgraph->Stroke();



?>

