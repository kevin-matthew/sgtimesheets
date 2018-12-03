<?php
function getDB():PDO
{
	static $db;
	if(!isset($db))
	{
		$db = new PDO('mysql:host=localhost;dbname=sgdb', "root", "d");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	}
	return $db;
}
