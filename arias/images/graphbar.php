<?
	//usage - pass data as as array of values
	//example - <img src="images/graphbar.php?data[]='.$earlyorddols.'&data[]='.$otorddols.'&data[]='.$lateorddols.'">
	include ("../includes/jpgraph/jpgraph.php");
	include ("../includes/jpgraph/jpgraph_bar.php");
	include ("../includes/jpgraph/jpgraph_line.php");
	if ($data) {
		$graph = new Graph(400,250);
		$graph->SetScale("textlin");
		$graph->SetShadow();
		$pl = new BarPlot($data);
//        $pl->SetLegends($dataname);
   		$pl->SetWidth(0.9);
		$graph->Add($pl);
		$graph->Stroke();
	};
?>

