<?php
function getDB():PDO
{
	static $db;
	if(!isset($db))
	{
		$db = new PDO('mysql:host=localhost;dbname=sgdb', "root", "d");
	}
	return $db;
}
