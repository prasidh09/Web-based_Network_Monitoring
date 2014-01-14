<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 <?php
$simple = 'simple string';
$con = mysql_connect("localhost","root","malaysia");

if (!$con) {
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("test", $con);

$result = mysql_query("SELECT * FROM highcharts_php");

while ($row = mysql_fetch_array($result)) {
   $data[] = $row['visits'];
   
}
?>


<title>HighStock</title>

	<script>
		!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
	</script>
	<script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.4.css" media="screen" />
 	<link rel="stylesheet" href="style.css" />

<script src="exporting.src.js"></script>
<script src="http://code.highcharts.com/stock/highstock.js"></script>


<script type="text/javascript">
	var simple = new Array();
simple = <?php echo json_encode($data); ?>;
var data_array=[];
var series0=[];
var series1=[];
var joinpoints=[];
var threshold=1300;

	var chart;
			$(document).ready(function() {
				var options = {
					chart: {
						renderTo: 'container',
						defaultSeriesType: 'line',
						marginRight: 130,
						marginBottom: 25
					},
					rangeSelector : {
				selected : 1
			},
					title: {
						text: 'Hourly Visits',
						x: -20 //center
					},
					subtitle: {
						text: '',
						x: -20
					},
					xAxis: {
						
					},
					yAxis: {
						title: {
							text: 'Bandwidth Usage'
						}
						,	plotLines : [{
					value : threshold,
					color : 'red',
					dashStyle : 'shortdash',
					width : 2,
					label : {
						text : 'Threshold'
					}
				}]
					},
					tooltip: {
						formatter: function() {
				                return this.x+","+this.y;
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'top',
						x: -10,
						y: 100,
						borderWidth: 0
					},
					series: [{
						name: 'Normal Activity',
							data: []        
						},
						{
						name: 'Suspicious Activity',
							data: []        
						}]
				}
		
  
				chart = new Highcharts.StockChart(options);
				for(var i=0;i<simple.length;i++)
				{
				if(simple[i]>threshold)
					{
				 if(i!=0)
						{
				 if(simple[i-1]>threshold)
					chart.series[1].addPoint([i,parseInt(simple[i])]);
				 else
				 {
					 var x1=0;var y1=threshold;
					 var x2=1000;var y2=threshold;
					 var x3=i-1;var y3=parseInt(simple[i-1]);
					 var x4=i;var y4=parseInt(simple[i]);
					 
					var d = (x1-x2)*(y3-y4) - (y1-y2)*(x3-x4);
					var xi = ((x3-x4)*(x1*y2-y1*x2)-(x1-x2)*(x3*y4-y3*x4))/d;
					var yi = ((y3-y4)*(x1*y2-y1*x2)-(y1-y2)*(x3*y4-y3*x4))/d;
			
					
					chart.series[0].addPoint([xi,yi]);
					


						 chart.series[0].addPoint(null);
					
						 chart.series[1].addPoint([xi,yi]);
						 				 chart.series[1].addPoint([i,parseInt(simple[i])]);
			             
																				 //chart.series[1].addPoint(null);
						 }
						 }
						 else
						 {
						 chart.series[0].addPoint(null);
					
											 				 chart.series[1].addPoint([i,parseInt(simple[i])]);

						 }
}
else
{
if(simple[i-1]>threshold)
{
var x1=0;var y1=threshold;
				 var x2=1000;var y2=threshold;
				 var x3=i-1;var y3=parseInt(simple[i-1]);
				 var x4=i;var y4=parseInt(simple[i]);
				 
					var d = (x1-x2)*(y3-y4) - (y1-y2)*(x3-x4);
					var xi = ((x3-x4)*(x1*y2-y1*x2)-(x1-x2)*(x3*y4-y3*x4))/d;
					var yi = ((y3-y4)*(x1*y2-y1*x2)-(y1-y2)*(x3*y4-y3*x4))/d;
				
						 chart.series[1].addPoint([xi,yi]);
						 chart.series[0].addPoint([xi,yi])

						 }
						 chart.series[0].addPoint([i,parseInt(simple[i])]);
				 chart.series[1].addPoint(null);
				 }
				}
		
$("#various1").fancybox({
				'titlePosition'		: 'outside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
			});			
		
				});

</script>
</head>
<body>

<div id="container" style="width: 100%; height: 400px; margin: 0 auto"></div>
<div style="display: none;">

		<div id="inline1" style="width:800px;height:200px;overflow:auto;">
			This web based network monitoring system for usage and network monitoring will assist network administrators to visualize and monitor their networks using data visualization tools. It is the key to avoid bandwidth, performance bottlenecks and identifying sudden spikes in the network.
 <br><br>
Chart 1: Points of intersection at the threshold limit is taken into consideration. Distinct colour is used to separate the values above and below the threshold limit. An intermediate point of intersection is plotted to distinguish the values above and below the threshold.
 <br><br>
Chart 2: Points of intersection at the threshold limit is not taken into consideration. No intermediate point is plotted in the graph.

		</div>
	</div>
<div style="display: none;">
<a id="various1" href="#inline1" title="Overview about the chart">Inline</a>
<a id="vectormap" href="jvectormap.php" title="Activity Distribution">Inline</a>
</div>

</body>
</html>