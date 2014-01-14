<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 

<title>Using Highcharts with button click event</title>

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
<script type="text/javascript">
var simple = new Array();
simple = <?php echo json_encode($data); ?>;
alert(simple);
var chart;	
$(document).ready(function() {
var options = ({
      chart: {
         renderTo: 'container'
      },
	  series: [{
	name: 'Normal Activity',
        data: ['0','1']    
    },
	{
	name: 'Suspicious Activity',
        data: []       
    }]
      
});
 
)};

chart=new Highcharts.Chart(options);
</script>
</head>
<body>
<div id="container" style="height: 250px"></div>

					
</body>
</html>