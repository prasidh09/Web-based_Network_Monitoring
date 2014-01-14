<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
<?php
include('ipConvertDec.php'); 
$con = mysql_connect("localhost","intern","intern@123");

if (!$con) {
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("test", $con);

$result = mysql_query("select ipsource,ipdest,count(portDest)as NumberOfTimes from packetheader group by ipsource,ipdest ORDER BY portDest ASC");
while ($row = mysql_fetch_array($result)) {
    $scans[]=$row['NumberOfTimes'];
	$ipDec= convertIpDec($row['ipsource']);
	$subresult = mysql_query("SELECT distinct * FROM `tblipv4range` WHERE $ipDec BETWEEN `ipv4from` AND `ipv4to`");
while ($subrow = mysql_fetch_array($subresult))   
	{
	$data[]=$subrow;
	$index=$subrow['ipv4country2'];
	 $scans[$index]+=$row['NumberOfTimes'];
	}
}
?>


<title>Activity Distribution</title>

 <link rel="stylesheet" media="all" href="tooltip.css"/>
 <link rel="stylesheet" media="all" href="jquery-jvectormap.css"/>
  <script src="jquery-1.8.2.js"></script>
  <script src="jquery-jvectormap.js"></script>
  <script src="jquery-mousewheel.js"></script>

  <script src="lib/jvectormap.js"></script>

  <script src="lib/abstract-element.js"></script>
  <script src="lib/abstract-canvas-element.js"></script>
  <script src="lib/abstract-shape-element.js"></script>

  <script src="lib/svg-element.js"></script>
  <script src="lib/svg-group-element.js"></script>
  <script src="lib/svg-canvas-element.js"></script>
  <script src="lib/svg-shape-element.js"></script>
  <script src="lib/svg-path-element.js"></script>
  <script src="lib/svg-circle-element.js"></script>

  <script src="lib/vml-element.js"></script>
  <script src="lib/vml-group-element.js"></script>
  <script src="lib/vml-canvas-element.js"></script>
  <script src="lib/vml-shape-element.js"></script>
  <script src="lib/vml-path-element.js"></script>
  <script src="lib/vml-circle-element.js"></script>

  <script src="lib/vector-canvas.js"></script>
  <script src="lib/simple-scale.js"></script>
  <script src="lib/numeric-scale.js"></script>
  <script src="lib/ordinal-scale.js"></script>
  <script src="lib/color-scale.js"></script>
  <script src="lib/data-series.js"></script>
  <script src="lib/proj.js"></script>
  <script src="lib/world-map.js"></script>

  <script src="jquery-jvectormap-world-mill-en.js"></script>
  <style>
.jvectormap-label {
	border:  1px solid white;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 10px;
	background: #4A7E2A;
	color: white;
	font-family: Arial;
	padding: 5px 10px;
	font-size:13px;
}
  </style>  
<script type="text/javascript">

	var countries = new Array();
countries = <?php echo json_encode($data); ?>;
scans = <?php echo json_encode($scans); ?>;
var i=0;
var countryData = []; 
var scanData = []; 

//for each country, set the code and value

$.each(countries, function() {
    countryData[this.ipv4country2] = scans[this.ipv4country2];
});



$(function() {
    $('#world-map').vectorMap({
        map: 'world_mill_en',
        series: {
            regions: [{
                values: countryData, //load the data
                scale: ['#00FF00', '#FF0000'],
                normalizeFunction: 'polynomial'}]
        },
       
        onRegionLabelShow: function(e, el, code) {
            //search through dataC to find the selected country by it's code
            var country = $.grep(countries, function(obj, index) {
                return obj.ipv4country2 == code;
            })[0]; //snag the first one
            //only if selected country was found in dataC
            if (country != undefined) { 
                el.html(el.html() +"<br/><b>Number Of Ports Scanned: </b>" + countryData[country.ipv4country2]);
            }
			else
			el.html(el.html() +"<br/><b>Number Of Ports Scanned: 0 </b>");
        }
    });
	$('#view').tooltip({ 
    delay: 0, 
    showURL: false, 
    bodyHandler: function() { 
        return $("<img/>").attr("src", this.src); 
    } 
	
});

});
</script>
</head>
<body>
<a href="#" class="tooltip">
    <img id="view" src="Click.png" align="top" >
    <span>
        <img class="callout" src="callout_black.gif" />
        <strong>Port Scanning</strong><br />
        <img src="port.jpg" style="float:right;" />
        The countries are distinguished by colours depending on the number of scans made by each country.
<br>
        Programs use ports (like we use doors) to visit and communicate with the outside world (the net). By monitoring the ports on your computer, you can be alerted when changes are made. This information is provided to the network administrator on time through email. End users are also notified in case of bursts. Our application hence allows the network administrator to set thresholds for the bandwidth utilization, get alerts and emails when the threshold value is exceeded. This helps in quicker understanding of the problem in the network and hence quicker action
<img src="legend.png" style="float:bottom;" /> 
    </span>
</a><div id="world-map" style="width: 1337px; height: 575px; margin: -5 auto"></div>
					
</body>
</html>