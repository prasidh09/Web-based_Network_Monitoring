<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 

<title>Using Highcharts with PHP and MySQL</title>
<script type="text/javascript" src="js/highcharts.js" ></script>
<script type="text/javascript" src="js/themes/gray.js" ></script>


<script type="text/javascript">
	$(function () {
var chart = new Highcharts.Chart({
    chart: {
        renderTo: 'container'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    
    series: [{
        data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]        
    }]
});

$('#button').click(function() {
    var series = chart.series[0];
    series.color = "#FF00FF";
    series.graph.attr({ 
        stroke: '#FF00FF'
    });
    chart.legend.colorizeItem(series, series.visible);
    $.each(series.data, function(i, point) {
        point.graphic.attr({
            fill: '#FF00FF'
        });
    });
    series.redraw();
});});
</script>
</head>
<body>
<div id="container" style="height: 250px"></div>

<button id="button">Update color</button>
					
</body>
</html>