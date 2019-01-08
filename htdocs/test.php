<?php
ini_set('output_buffering', 'off');
ini_set('implicit_flush', 'on');
for($i = 0; $i < 10; $i++)
{
	print 'hi<br>';
	flush();
	sleep(5);
	
}
print 'd';