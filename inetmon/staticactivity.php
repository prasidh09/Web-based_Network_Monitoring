<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 

<title>Using Highcharts with button click event</title>

<script type="text/javascript" src="js/jquery-1.7.1.min.js" ></script>
<script type="text/javascript" src="js/highcharts.js" ></script>
<script type="text/javascript" src="js/themes/gray.js" ></script>

<?php 

function getIntersectionPoint(double x1,double y1,double x2,double y2,double x3,double y3,double x4,double y4)
{
$d = (x1-x2)*(y3-y4) - (y1-y2)*(x3-x4);
$xi = ((x3-x4)*(x1*y2-y1*x2)-(x1-x2)*(x3*y4-y3*x4))/d;
$yi = ((y3-y4)*(x1*y2-y1*x2)-(y1-y2)*(x3*y4-y3*x4))/d;
series[0].addPoint([$xi,$yi]);
}


?>


<script type="text/javascript">
	$(function () {
var options = {
    chart: {
        renderTo: 'container'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    
    series: [{
	name: 'Normal Activity',
        data: []        
    },
	{
	name: 'Suspicious Activity',
        data: []        
    }]
};
jQuery.get('data.php', null, function(tsv) {
					var lines = [];
					traffic = [];
					try {
						// split the data return into lines and parse them
						tsv = tsv.split(/\n/g);
						jQuery.each(tsv, function(i, line) {
							line = line.split(/\t/);
							date = Date.parse(line[0] +' UTC');
							traffic.push([
								date,
								parseInt(line[1].replace(',', ''), 10)
							]);
						});
					} catch (e) {  }
					options.series[0].data = traffic;
					chart = new Highcharts.Chart(options);
				
});



// $('#button').click(function() {
    // var series = chart.series[0];
    // series.color = "#FF00FF";
    // series.graph.attr({ 
        // stroke: '#FF00FF'
    // });
    // chart.legend.colorizeItem(series, series.visible);
    // $.each(series.data, function(i, point) {

        // point.graphic.attr({
            // fill: '#FF00FF'
        // });
    // });
    // series.redraw();
// });
</script>
</head>
<body>
<div id="container" style="height: 250px"></div>

					
</body>
</html>