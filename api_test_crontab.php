<?php
	include "header/header_DB.php";
	include "header/functionsY.php";

	$cntDB = DBCONNECT_start();

	$sql = "INSERT INTO t_test ( insert_date ) VALUES ( now() )"; 
	$rs = x_SQL($sql, $cntDB);

	DBCLOSE_end($cntDB);
?>
