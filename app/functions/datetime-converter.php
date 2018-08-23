<?php 
function datetimeConverterVn($date){
	$result="";
	if (!empty($date) && $date != '0000-00-00 00:00:00' && $date != '0000-00-00') {
		$arrDate    = date_parse_from_format('Y-m-d H:i:s', $date) ;
		$ts         = mktime($arrDate["hour"],$arrDate["minute"],$arrDate["second"],$arrDate['month'],$arrDate['day'],$arrDate['year']);
		$result     = date('d/m/Y', $ts);
	}
	return $result;
}
function getDateTime($date){
	$result="";
	if (!empty($date) && $date != '0000-00-00 00:00:00' && $date != '0000-00-00') {
		$arrDate    = date_parse_from_format('Y-m-d H:i:s', $date) ;
		$ts         = mktime($arrDate["hour"],$arrDate["minute"],$arrDate["second"],$arrDate['month'],$arrDate['day'],$arrDate['year']);
		$result     = date('d/m/Y H:i:s', $ts);
	}
	return $result;
}
?>