<?php
/**
  * graph.php
  * This page uses ChartDirector to display data from the excel file as a graphic.
  */
session_start();
require_once ("./ChartDirector/lib/phpchartdir.php");
$_SESSION['data'];
$_SESSION['labels'];
//Create a PieChart object of size 360 x 300 pixels
$c = new PieChart(360, 300);
//Set the center of the pie at (180, 140) and the radius to 100 pixels
$c->setPieSize(180, 180, 80);
//Add Title to pie chart
$c->addTitle($_SESSION['sheet_title']);
//Set the pie data and the pie labels
$c->setData($_SESSION['data'],$_SESSION['labels']);
//Output the chart
header("Content-type: image/png");
print($c->makeChart2(PNG));
?>