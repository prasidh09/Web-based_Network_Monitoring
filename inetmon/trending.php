<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 <?php
$con = mysql_connect("localhost","intern","intern@123");

if (!$con) {
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("test", $con);

$result = mysql_query("SELECT SUM( totalBits ) AS totalBits, EXTRACT( YEAR FROM timeStampID ) AS YEAR, 
EXTRACT( MONTH FROM timeStampID ) AS MONTH , 
EXTRACT( DAY FROM timeStampID ) AS DAY , 
EXTRACT( HOUR FROM timeStampID ) AS HOUR , 
EXTRACT( MINUTE FROM timeStampID ) AS 
MINUTE FROM `ipByteReceive1` 
WHERE `timeStampID` > '2013-01-21 02:00:00'
GROUP BY YEAR, MONTH , DAY , HOUR , MINUTE");

while ($row = mysql_fetch_array($result)) {
   $day[] = $row['DAY'];
   $month[] = $row['MONTH'];
   $year[] = $row['YEAR'];
   $hour[] = $row['HOUR'];
   $min[] = $row['MINUTE'];
$bits[] = $row['totalBits'];
   }
   $result = mysql_query("SELECT overallThreshold  from threshold WHERE  `threshold`.`AdminId` =  'admin'");
while ($row = mysql_fetch_array($result)) 
$threshold=$row['overallThreshold'];
?>


<title>Trending</title>

	<script>
		!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
	</script>
	<script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.4.css" media="screen" />
 	<link rel="stylesheet" href="style.css" />
<script src="js/highcharts.js"></script>
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
var threshold=1300;
var threshold1=<?php echo $threshold; ?>;

	var chart;
			$(document).ready(function() {
				var options = {
					chart: {
						renderTo: 'container',
						defaultSeriesType: 'areaspline',
						zoomType: 'x',
						marginRight: 130,
						marginBottom: 25
					},
					title: {
						text: 'Complete Trending(Average Threshold)',
						x: -20,
						y:35						
					},
					subtitle: {
						text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' :
                    'Drag your finger over the plot to zoom in',
						x: -20,
						y:50
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
				var options1 = {
					chart: {
						renderTo: 'container1',
						defaultSeriesType: 'areaspline',
						zoomType: 'x',
						marginRight: 130,
						marginBottom: 25
					},
					title: {
						text: 'Complete Trending(Admin Threshold)',
						x: -20,
						y:35						
					},
					subtitle: {
						text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' :
                    'Drag your finger over the plot to zoom in',
						x: -20,
						y:50
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
					value : threshold1,
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
		
  
				chart1 = new Highcharts.Chart(options1);
				for(var i=0;i<bits.length;i++)
				{
				if(bits[i]>threshold1)
					{
				 if(i!=0)
						{
				 if(bits[i-1]>threshold1)
					chart1.series[1].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),parseInt(bits[i])]);
				 else
				 {
					 var x1=0;var y1=threshold1;
					 var x2=100000;var y2=threshold1;
					 var x3=i-1;var y3=parseInt(bits[i-1]);
					 var x4=i;var y4=parseInt(bits[i]);
					 
					var d = (x1-x2)*(y3-y4) - (y1-y2)*(x3-x4);
					var xi = ((x3-x4)*(x1*y2-y1*x2)-(x1-x2)*(x3*y4-y3*x4))/d;
					var yi = ((y3-y4)*(x1*y2-y1*x2)-(y1-y2)*(x3*y4-y3*x4))/d;
			
					
					chart1.series[0].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),yi]);
					


						 chart1.series[0].addPoint(null);
					
						 chart1.series[1].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),yi]);
						 				 chart1.series[1].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),parseInt(bits[i])]);
			             
																				 //chart.series[1].addPoint(null);
						 }
						 }
						 else
						 {
						 chart1.series[0].addPoint(null);
					
											 				 chart1.series[1].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),parseInt(bits[i])]);

						 }
}
else
{
if((bits[i-1]>threshold1)&&(i!=0))
{
var x1=0;var y1=threshold1;
				 var x2=1000;var y2=threshold1;
				 var x3=i-1;var y3=parseInt(bits[i-1]);
				 var x4=i;var y4=parseInt(bits[i]);
				 
					var d = (x1-x2)*(y3-y4) - (y1-y2)*(x3-x4);
					var xi = ((x3-x4)*(x1*y2-y1*x2)-(x1-x2)*(x3*y4-y3*x4))/d;
					var yi = ((y3-y4)*(x1*y2-y1*x2)-(y1-y2)*(x3*y4-y3*x4))/d;
				
						 chart1.series[1].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),yi]);
						 chart1.series[0].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),yi])

						 }
						 chart1.series[0].addPoint([Date.UTC(year[i], month[i], day[i], hour[i], min[i]),parseInt(bits[i])]);
				 chart1.series[1].addPoint(null);
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

<div id="container" style="width: 100%; height: 300px; margin: 0 auto"></div>
<br>
<br>
<br>
<div id="container1" style="width: 100%; height: 300px; margin: 0 auto"></div>
<div style="display: none;">

		<div id="inline1" style="width:800px;height:200px;overflow:auto;">
<b>Visualization of Trending</b>
<br><br>
We monitor the trends pertaining to each day of the week and the overall trending. The network administrator can set the threshold as per his requirements. An average threshold is calculated and made use of for setting the weekday threshold limits. You can pan in and view the intricate details that the visualization has to offer.
<br>
<br>
The bandwidth simply represents the capacity of the communication media to transfer data from source to destination. Our system predicts occurrence of attacks in the near future and provides an alert to the network administrator whenever the bandwidth exceeds a threshold. A trend is set by monitoring the bandwidth and when it increases exponentially there is a notification sent as an alarm.

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