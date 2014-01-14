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
MINUTE,DAYNAME(timeStampID) as DayName FROM `ipByteReceive1` 
WHERE DAYNAME(timeStampID) ='Wednesday'
GROUP BY YEAR, MONTH , DAY , HOUR , MINUTE");

while ($row = mysql_fetch_array($result)) {
   echo $row['HOUR'];
   }
?>