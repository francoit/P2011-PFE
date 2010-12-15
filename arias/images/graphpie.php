<?
     //usage - pass name as the unique name for the image (to prevent caching), data as as array of values, and dataname as an array of names
     //example - <img src="images/graphpie.php?name=arorderstatontimegraphdols&data[]='.$earlyorddols.'&data[]='.$otorddols.'&data[]='.$lateorddols.'&dataname[]=Early&dataname[]=On+Time&dataname[]=Late">
     include ("../includes/jpgraph/jpgraph.php");
     include ("../includes/jpgraph/jpgraph_pie.php");
     if ($data&&$dataname&&$name) {
          $graph = new PieGraph(200,150,$name);
          $graph->SetShadow();
          $pl = new PiePlot($data);
          $pl->SetLegends($dataname);
          $graph->Add($pl);
          $graph->Stroke();
     };
?>
