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


<title>Using Highcharts with PHP and MySQL</title>

<script type="text/javascript" src="js/jquery-1.7.1.min.js" ></script>
<script type="text/javascript" src="js/highcharts.js" ></script>
<script type="text/javascript" src="js/themes/gray.js"></script>

<script type="text/javascript">
	var simple = new Array();
simple = <?php echo json_encode($data); ?>;
var data_array=[];
var series0=[];
var series1=[];


 
	var chart;
			$(document).ready(function() {
				var options = {
					chart: {
						renderTo: 'container',
						defaultSeriesType: 'line',
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
						
					},
					yAxis: {
						title: {
							text: 'Visits'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
						formatter: function() {
				                return Highcharts.dateFormat('%l%p', this.x-(1000*3600)) +'-'+ Highcharts.dateFormat('%l%p', this.x) +': <b>'+ this.y + '</b>';
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
				// Load data asynchronously using jQuery. On success, add the data
				// to the options and initiate the chart.
				// This data is obtained by exporting a GA custom report to TSV.
				// http://api.jquery.com/jQuery.get/
				//options.series[0].addPoint([6.0, 8.0]);
				for (var i=0;i<simple.length;i++)
  {
if(simple[i]>1285)
{  
series0.push(null);
 if((i!=0)&&(i!=simple.length))
 {

  series1.pop();
   
series1.push(parseInt(simple[i-1]));
     series1.push(parseInt(simple[i]));
        series1.push(parseInt(simple[i+1]));
  
  }
  else if(i==simple.length)
  {
  series1.pop();
  series1.push(parseInt(simple[i-1]));
     series1.push(parseInt(simple[i]));
 
  }
  else
  {
  
  series1.push(parseInt(simple[i]));
  }
  }
  else
  {
  series0.push(parseInt(simple[i]));
  series1.push(null);
  }
  }
					chart = new Highcharts.Chart(options);
			 chart.series[0].setData(series0);
			 chart.series[1].setData(series1);
			chart.redraw();
			});
</script>
</head>
<body>

<div id="container" style="width: 100%; height: 400px; margin: 0 auto"></div>
					
</body>
</html>