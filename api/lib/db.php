<?php


require_once __DIR__."/config.php";

$dsn = 'mysql:host='.HOST.';dbname='.DBNAME.';charset=utf8';

$conn =  new PDO($dsn, DBUSER, DBPASS);

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

return $conn ;
