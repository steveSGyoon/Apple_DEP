!#/usr/local/php/bin/php -q
<?php
	include "/home/manager/dep/header/functionsY.php";

	$DBSERVER = "localhost";
	$DBUSER = "usr_dep";
	$DBPASSWORD = "dovmfeldlvl1*";
	$DBNAME = "_apple_DEP";


	$cntDB = DBCONNECT_start();

	$sql = "INSERT INTO t_test (insert_date) VALUES (now())";
	$rsTemp = x_SQL($sql, $cntDB);

	DBCLOSE_end($cntDB);
?>
