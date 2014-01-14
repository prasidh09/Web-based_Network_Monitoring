<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 <?php
$con = mysql_connect("localhost","root","malaysia");

if (!$con) {
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("nav6teamcse", $con);

$result = mysql_query("SELECT SUM( totalBits ) AS totalBits, EXTRACT( YEAR FROM timeStampID ) AS YEAR, 
EXTRACT( MONTH FROM timeStampID ) AS MONTH , 
EXTRACT( DAY FROM timeStampID ) AS DAY , DAYNAME(timeStampID) as DayName FROM `ipByteReceive1` 
WHERE DAYNAME(timeStampID) ='Monday'
GROUP BY YEAR, MONTH , DAY ,DAYNAME(timeStampID)");

while ($row = mysql_fetch_array($result)) {
   $day[] = $row['DAY'];
   $month[] = $row['MONTH'];
   $year[] = $row['YEAR'];
$bits[] = $row['totalBits'];
   }
$result = mysql_query("SELECT AVG(totalBits) as average from (SELECT SUM( totalBits ) AS totalBits, EXTRACT( YEAR FROM timeStampID ) AS YEAR, 
EXTRACT( MONTH FROM timeStampID ) AS MONTH , 
EXTRACT( DAY FROM timeStampID ) AS DAY , DAYNAME(timeStampID) as DayName FROM `ipByteReceive1` 
WHERE DAYNAME(timeStampID) ='Monday'
GROUP BY YEAR, MONTH , DAY ,DAYNAME(timeStampID)) as q1");
while ($row = mysql_fetch_array($result)) {
$threshold=$row['average'];
}
   ?>


<title>Trending</title>

	<script>
		!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
	</script>
	<script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.4.css" media="screen" />
 	<link rel="stylesheet" href="style.css" />
<script src="/js/highcharts.js"></script>
<script src="trendingexport.src.js"></script>

<script type="text/javascript">
	var day = new Array();
day = <?php echo json_encode($day); ?>;
var month = new Array();
month = <?php echo json_encode($month); ?>;
var year = new Array();
year = <?php echo json_encode($year); ?>;
var hour = new Array();
var bits = new Array();
bits = <?php echo json_encode($bits); ?>;
var data_array=[];
var series0=[];
var series1=[];
var joinpoints=[];
var threshold=<?php echo $threshold; ?>;

	var chart;
			$(document).ready(function() {
				var options = {
					chart: {
						renderTo: 'container',
						defaultSeriesType: 'area',
						zoomType: 'x',
						marginRight: 130,
						marginBottom: 25
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
					type: 'datetime',
					tickInterval: null,
					
    min: Date.UTC(year[0],month[0]-1,day[0])
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
		
  
				chart = new Highcharts.Chart(options);
				for(var i=0;i<bits.length;i++)
				{
				if(bits[i]>threshold)
					{
				 if(i!=0)
						{
				 if(bits[i-1]>threshold)
					chart.series[1].addPoint([Date.UTC(year[i], month[i]-1, day[i]),parseInt(bits[i])]);
				 else
				 {
					 var x1=0;var y1=threshold;
					 var x2=100000;var y2=threshold;
					 var x3=i-1;var y3=parseInt(bits[i-1]);
					 var x4=i;var y4=parseInt(bits[i]);
					 
					var d = (x1-x2)*(y3-y4) - (y1-y2)*(x3-x4);
					var xi = ((x3-x4)*(x1*y2-y1*x2)-(x1-x2)*(x3*y4-y3*x4))/d;
					var yi = ((y3-y4)*(x1*y2-y1*x2)-(y1-y2)*(x3*y4-y3*x4))/d;
			
					
					chart.series[0].addPoint([Date.UTC(year[i], month[i]-1, day[i]),yi]);
					


						 chart.series[0].addPoint(null);
					
						 chart.series[1].addPoint([Date.UTC(year[i], month[i]-1, day[i]),yi]);
						 				 chart.series[1].addPoint([Date.UTC(year[i], month[i]-1, day[i]),parseInt(bits[i])]);
			             
																				 //chart.series[1].addPoint(null);
						 }
						 }
						 else
						 {
						 chart.series[0].addPoint(null);
					
											 				 chart.series[1].addPoint([Date.UTC(year[i], month[i]-1, day[i]),parseInt(bits[i])]);

						 }
}
else
{
if((bits[i-1]>threshold)&&(i!=0))
{
var x1=0;var y1=threshold;
				 var x2=1000;var y2=threshold;
				 var x3=i-1;var y3=parseInt(bits[i-1]);
				 var x4=i;var y4=parseInt(bits[i]);
				 
					var d = (x1-x2)*(y3-y4) - (y1-y2)*(x3-x4);
					var xi = ((x3-x4)*(x1*y2-y1*x2)-(x1-x2)*(x3*y4-y3*x4))/d;
					var yi = ((y3-y4)*(x1*y2-y1*x2)-(y1-y2)*(x3*y4-y3*x4))/d;
				
						 chart.series[1].addPoint([Date.UTC(year[i], month[i]-1, day[i]),yi]);
						 chart.series[0].addPoint([Date.UTC(year[i], month[i]-1, day[i]),yi])

						 }
						 chart.series[0].addPoint([Date.UTC(year[i], month[i]-1, day[i]),parseInt(bits[i])]);
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
<a id="All" href="trending.php" title="Activity Distribution">Inline</a>
<a id="Mon" href="trendingmon.php" title="Activity Distribution">Inline</a>
<a id="Tue" href="trendingtue.php" title="Activity Distribution">Inline</a>
<a id="Wed" href="trendingwed.php" title="Activity Distribution">Inline</a>
<a id="Thu" href="trendingthu.php" title="Activity Distribution">Inline</a>
<a id="Fri" href="trendingfri.php" title="Activity Distribution">Inline</a>
<a id="Sat" href="trendingsat.php" title="Activity Distribution">Inline</a>
<a id="Sun" href="trendingsun.php" title="Activity Distribution">Inline</a>
</div>

</body>
</html>