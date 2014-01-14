<?php
function convertIpDec($originalIpAdd)
{
  ////FIRST GET THE IP AND REPLACE THE VALUE OF MY $getIP
  $getIP = $originalIpAdd;

  ////SEPARATE EACH OCTAL 
  $NoDotIP = explode('.',$getIP);
  
  ////INSTEAD OF CONVERTING OCTAL TO BINARY AND BINARY TO DECIMAL YOU CAN USE PHP's BUILT-IN FUNCTION ip2long(), BUT FOR SOME UNKNOWN REASON IT DIDN'T WORKED FOR ME SO I DECIDED TO DO IT MANUALLY  
  ////CONVERT EACH OCTAL TO BINARY 
  $a[0] = decbin($NoDotIP[0]);
  $a[1] = decbin($NoDotIP[1]);
  $a[2] = decbin($NoDotIP[2]);
  $a[3] = decbin($NoDotIP[3]);
  
  ////MAKE SURE THAT EACH ONE CONTAINS EIGHT DIGITS 
  for($i=0;$i<4;$i++){
      for($j=strlen($a[$i]);$j<8;$j++){
          $a[$i] = "0".$a[$i];
      }
  }
  
  ////CONCATENATE ALL FOUR BINARIES AND CONVERT TO DECIMAL 
  $binary = $a[0].$a[1].$a[2].$a[3];
  $result = bindec($binary);
  $decimal_ip = $result;
  
  return ($decimal_ip);
}

?>
