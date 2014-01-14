 <?php
$con = mysql_connect("10.207.160.131","intern","intern@123");
$select_db = mysql_select_db("test" , $con);

$reason = mysql_real_escape_string($_POST['reason']);   // gets the data it received from the request

$result=mysql_query("UPDATE  `threshold` SET  `overallThreshold` =  $reason WHERE  `AdminId` =  'admin'");

?>
