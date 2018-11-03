<?php
define("MONTHS", array(
	'Jan' => 'January'  ,
	'Feb' => 'February' ,
	'Mar' => 'March'    ,
	'Apr' => 'April'    ,
	'May' => 'May'      ,
	'Jun' => 'June'     ,
	'Jul' => 'July '    ,
	'Aug' => 'August'   ,
	'Sep' => 'September',
	'Oct' => 'October'  ,
	'Nov' => 'November' ,
	'Dec' => 'December' ,
));

function months_options()
{
	$r = "";
	$c=count(MONTHS);
	for($i = 0; $i < $c; $i++)
		$r .= "<option value=\"$i\">".MONTHS[$i]."</option>";
	
	return $r;
}
