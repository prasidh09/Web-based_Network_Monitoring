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
EXTRACT( DAY FROM timeStampID ) AS DAY , 
EXTRACT( HOUR FROM timeStampID ) AS HOUR , 
EXTRACT( MINUTE FROM timeStampID ) AS 
MINUTE,DAYNAME(timeStampID) as DayName FROM `ipByteReceive1` 
WHERE DAYNAME(timeStampID) ='Friday'
GROUP BY YEAR, MONTH , DAY , HOUR , MINUTE");

while ($row = mysql_fetch_array($result)) {
   $day[] = $row['DAY'];
   $month[] = $row['MONTH']-1;
   $year[] = $row['YEAR'];
   $hour[] = $row['HOUR'];
   $min[] = $row['MINUTE'];
$bits[] = $row['totalBits'];
   }

$result = mysql_query("SELECT AVG(totalBits) as average from (SELECT SUM( totalBits ) AS totalBits, EXTRACT( YEAR FROM timeStampID ) AS YEAR, 
EXTRACT( MONTH FROM timeStampID ) AS MONTH , 
EXTRACT( DAY FROM timeStampID ) AS DAY , DAYNAME(timeStampID) as DayName FROM `ipByteReceive1` 
WHERE DAYNAME(timeStampID) ='Friday'
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
hour = <?php echo json_encode($hour); ?>;
var min = new Array();
min = <?php echo json_encode($min); ?>;
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
						events: {
           load: function() {
               this.renderer.image('/Threshold.png', 40, 40, 130, 50)
                   .on('click', function() {
                       document.getElementById('Threshold').click();
                   })
                   .css({
                       cursor: 'pointer'
                   })
                   .css({
                       position: 'relative',
                       "margin-left": "-90px",
                       opacity: 0.75
                   })
                   .attr({
                       zIndex: -100
                   })
                   .add();
				   this.renderer.image('/trending.png', 220, 10, 160, 20)
                   .on('click', function() {
                       document.getElementById('All').click();
                   })
                   .css({
                       cursor: 'pointer'
                   })
                   .css({
                       position: 'relative',
                       "margin-left": "-90px",
                       opacity: 0.75
                   })
                   .attr({
                       zIndex: -100
                   })
                   .add();
           }
       },
						defaultSeriesType: 'areaspline',
						zoomType: 'x',
						marginRight: 130,
						marginBottom: 25
					},
					title: {
style: {
               
                fontWeight: 'bold',
				fontSize: '24px'
            },					
						text: 'Complete Trending',
						x: -20,
						y:70						
					},
					subtitle: {
					style: {
               
                
				fontSize: '18px'
            },	
						text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' :
                    'Drag your finger over the plot to zoom in',
						x: -20,
						y:100
					},
					xAxis: {
					type: 'datetime',
					maxZoom:  1800, //  
					
 min: Date.UTC(year[0],month[0],day[0],hour[0],min[0])
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
				                return this.y+" bits";
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'top',
						x: 0,
						y: 50,
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
					chart.series[1].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),parseInt(bits[i])]);
				 else
				 {
					 var x1=0;var y1=threshold;
					 var x2=100000;var y2=threshold;
					 var x3=i-1;var y3=parseInt(bits[i-1]);
					 var x4=i;var y4=parseInt(bits[i]);
					 
					var d = (x1-x2)*(y3-y4) - (y1-y2)*(x3-x4);
					var xi = ((x3-x4)*(x1*y2-y1*x2)-(x1-x2)*(x3*y4-y3*x4))/d;
					var yi = ((y3-y4)*(x1*y2-y1*x2)-(y1-y2)*(x3*y4-y3*x4))/d;
			
					
					chart.series[0].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),yi]);
					


						 chart.series[0].addPoint(null);
					
						 chart.series[1].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),yi]);
						 				 chart.series[1].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),parseInt(bits[i])]);
			             
																				 //chart.series[1].addPoint(null);
						 }
						 }
						 else
						 {
						 chart.series[0].addPoint(null);
					
											 				 chart.series[1].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),parseInt(bits[i])]);

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
				
						 chart.series[1].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),yi]);
						 chart.series[0].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),yi])

						 }
						 chart.series[0].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),parseInt(bits[i])]);
				 chart.series[1].addPoint(null);
				 }
				}
		
$("#various1").fancybox({
				'titlePosition'		: 'outside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
			});			
		
				});
function myFunction()
{

var reason =prompt("Enter the threshold value...");

if(isNaN(reason)|| reason.indexOf(" ")!=-1)
	{
              			alert("Enter a number!!!");
			
                }
else
{	
        $.post("save.php", { reason: reason});
		location.reload();
}}
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
<a id="All" href="admin_trending.php" title="Activity Distribution">Inline</a>
<a id="Mon" href="admin_trendingmon.php" title="Activity Distribution">Inline</a>
<a id="Tue" href="admin_trendingtue.php" title="Activity Distribution">Inline</a>
<a id="Wed" href="admin_trendingwed.php" title="Activity Distribution">Inline</a>
<a id="Thu" href="admin_trendingthu.php" title="Activity Distribution">Inline</a>
<a id="Fri" href="admin_trendingfri.php" title="Activity Distribution">Inline</a>
<a id="Sat" href="admin_trendingsat.php" title="Activity Distribution">Inline</a>
<a id="Sun" href="admin_trendingsun.php" title="Activity Distribution">Inline</a>
<input id="Threshold" type="button" onclick="myFunction()" value="Show alert box">
</div>

</body>
</html>