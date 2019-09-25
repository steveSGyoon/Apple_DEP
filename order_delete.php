<?php
	include "header/header_DB.php";
	include "header/functionsY.php";


	$cntDB0 = DBCONNECT_start();

	$ret = [];
	$ret['result'] = "success";
	$ret['result_msg'] = "result message = \n";

	$order_idx = $_POST["order_idx"];

	$sql = "UPDATE t_order SET status=0, is_valid=0, edit_date=now() WHERE idx = $order_idx"; 
	$rs1 = x_SQL($sql, $cntDB0);

	$sql = "UPDATE t_order_device SET is_valid=0 WHERE t_order_idx = $order_idx"; 
	$rs2 = x_SQL($sql, $cntDB0);

	// if () {
	// 	$ret['result'] = "fail";
	// 	$ret['error_msg'] .= $order_idx . ":" . $response . ":param error\n";
	// }

	header('Content-Type: application/json; charset=utf-8');
	echo json_encode( $ret );

	DBCLOSE_end($cntDB0);
?>
