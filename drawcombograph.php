<?php // content="text/plain; charset=utf-8"
/*
**    Copyright 2010-2014 Erik Landsness
**    This file is part of 360 Feedback.
**
**    360 Feedback is free software: you can redistribute it and/or modify
**    it under the terms of the GNU General Public License as published by
**    the Free Software Foundation, either version 3 of the License, or any later version.
**
**    360 Feedback is distributed in the hope that it will be useful,
**    but WITHOUT ANY WARRANTY; without even the implied warranty of
**    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**    GNU General Public License for more details.
**
**    You should have received a copy of the GNU General Public License
**    along with 360 Feedback.  If not, see <http://www.gnu.org/licenses/>.
*/

require ('config.php');
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_radar.php');

// Some data to plot
$data = array($_GET['en'], $_GET['cr'], $_GET['tb'], $_GET['st'], $_GET['pr'], $_GET['co']);
$badstuff = array($_GET['pe'], $_GET['dr'], $_GET['pl'], $_GET['av'], $_GET['cm'], $_GET['at']);
foreach ($badstuff as $k => $v){
    if ($v > 1){
        $badstuff[$k] = $v + 20;
    } else {
        $badstuff[$k] = $data[$k];
    }
}

// Create the graph and the plot
$graph = new RadarGraph(800,600);
$graph->SetScale('lin',0,40);
//$graph->yscale->ticks->Set(10);
$graph->axis->HideLine();
$graph->axis->HideTicks();
$graph->axis->HideLabels();
$graph->axis->Hide();


// Add a drop shadow to the graph
$graph->SetShadow();

// Create the titles for the axis
//$titles = array('Performer', 'Drifter', 'Pleaser', 'Avoider',
//    'Commander', 'Attacker');
//$graph->SetTitles($titles);

// Add grid lines
//$graph->grid->Show();
//$graph->grid->SetLineStyle('solid');
//$graph->grid->SetColor('black');

$plot = new RadarPlot($data);
$plot->SetFillColor('forestgreen');
//$plot->SetColor('forestgreen');
//$plot->SetLineWeight(3);

$plot2 = new RadarPlot($badstuff);
$plot2->SetFillColor('red');
//$plot2->SetColor('red');
//$plot2->SetLineWeight(3);

// Add the middle labels
$txt=new Text("Entrepreneur");
$txt->SetPos(405,200,"center","middle");
$txt->SetFont(FF_ARIAL,FS_NORMAL,14);
$txt->SetColor("black");
$graph->AddText($txt);

$txt2=new Text("Creator");
$txt2->SetPos(301,279,"center","middle");
$txt2->SetFont(FF_ARIAL,FS_NORMAL,14);
$txt2->SetColor("black");
$txt2->SetAngle(59.5);
$graph->AddText($txt2);

$txt3=new Text("Team Builder");
$txt3->SetPos(312,308,"center","middle");
$txt3->SetFont(FF_ARIAL,FS_NORMAL,14);
$txt3->SetColor("black");
$txt3->SetAngle(-59.5);
$graph->AddText($txt3);

$txt4=new Text("Stabilizer");
$txt4->SetPos(400,420,"center","middle");
$txt4->SetFont(FF_ARIAL,FS_NORMAL,14);
$txt4->SetColor("black");
$graph->AddText($txt4);

$txt5=new Text("Competitor");
$txt5->SetPos(498,210,"center","middle");
$txt5->SetFont(FF_ARIAL,FS_NORMAL,14);
$txt5->SetColor("black");
$txt5->SetAngle(-59.5);
$graph->AddText($txt5);

$txt6=new Text("Producer");
$txt6->SetPos(500,402,"center","middle");
$txt6->SetFont(FF_ARIAL,FS_NORMAL,14);
$txt6->SetColor("black");
$txt6->SetAngle(59.5);
$graph->AddText($txt6);

//Add bg image
$graph->SetBackgroundImage("images/chartbglight.png",BGIMG_FILLFRAME);

// Add the plot and display the graph
$graph->Add($plot2);
$graph->Add($plot);
$graph->Stroke();
?>
