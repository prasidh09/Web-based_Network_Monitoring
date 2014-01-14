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

$result = mysql_query("SELECT * FROM countries");

while ($row = mysql_fetch_array($result)) {
   $data[]=$row;
   
}
?>


<title>Using Highcharts with PHP and MySQL</title>

 <link rel="stylesheet" media="all" href="../jquery-jvectormap.css"/>
  <script src="assets/jquery-1.8.2.js"></script>
  <script src="../jquery-jvectormap.js"></script>
  <script src="../jquery-mousewheel.js"></script>

  <script src="../lib/jvectormap.js"></script>

  <script src="../lib/abstract-element.js"></script>
  <script src="../lib/abstract-canvas-element.js"></script>
  <script src="../lib/abstract-shape-element.js"></script>

  <script src="../lib/svg-element.js"></script>
  <script src="../lib/svg-group-element.js"></script>
  <script src="../lib/svg-canvas-element.js"></script>
  <script src="../lib/svg-shape-element.js"></script>
  <script src="../lib/svg-path-element.js"></script>
  <script src="../lib/svg-circle-element.js"></script>

  <script src="../lib/vml-element.js"></script>
  <script src="../lib/vml-group-element.js"></script>
  <script src="../lib/vml-canvas-element.js"></script>
  <script src="../lib/vml-shape-element.js"></script>
  <script src="../lib/vml-path-element.js"></script>
  <script src="../lib/vml-circle-element.js"></script>

  <script src="../lib/vector-canvas.js"></script>
  <script src="../lib/simple-scale.js"></script>
  <script src="../lib/numeric-scale.js"></script>
  <script src="../lib/ordinal-scale.js"></script>
  <script src="../lib/color-scale.js"></script>
  <script src="../lib/data-series.js"></script>
  <script src="../lib/proj.js"></script>
  <script src="../lib/world-map.js"></script>

  <script src="assets/jquery-jvectormap-world-mill-en.js"></script>
  
<script type="text/javascript">



$(function(){
      $('#world-map').vectorMap();
    });


</script>
</head>
<body>

<div id="world-map" style="width: 100%; height: 610px; margin: 0 auto"></div>
					
</body>
</html>