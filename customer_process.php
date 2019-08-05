<?php
	include "header/header_DB.php";
	include "header/functionsY.php";
	//include "header/_checkLogin.php";

	$cntDB = DBCONNECT_start();

	$ret = [];

	$action = $_POST["action"];
	$dep_customer_id = clearXSS(XSSfilter($_POST["dep_customer_id"]));
	$skn_customer_id = clearXSS(XSSfilter($_POST["skn_customer_id"]));
	$company = clearXSS(XSSfilter($_POST["company"]));
	$phone = clearXSS(XSSfilter($_POST["phone"]));
	$email = clearXSS(XSSfilter($_POST["email"]));
	$charge = clearXSS(XSSfilter($_POST["charge"]));
	$note = clearXSS(XSSfilter($_POST["note"]));

	if ($action == "edit") {
		$idx = clearXSS(XSSfilter($_GET["idx"]));

		$sql = "SELECT count(*) AS dep_customer_cnt FROM t_customer WHERE idx!=$idx AND dep_customer_id='$dep_customer_id'";
		$row = x_FETCH($sql, $cntDB);

		if ($row[dep_customer_cnt])
			$ret = array('result'=>'fail', 'error_msg'=>'Duplicated DEP customer ID. Please use another one.');
		else {
			$sql = "SELECT count(*) AS skn_customer_cnt FROM t_customer WHERE idx!=$idx AND skn_customer_id='$skn_customer_id'";
			$row = x_FETCH($sql, $cntDB);
			if ($row[skn_customer_cnt])
				$ret = array('result'=>'fail', 'error_msg'=>'Duplicated SKN customer ID. Please use another one.');
			else {
				$sql = "UPDATE 
							t_customer
						SET
							dep_customer_id = '$dep_customer_id', 
							skn_customer_id = '$skn_customer_id', 
							company = '$company', 
							phone = '$phone', 
							email = '$email', 
							charge = '$charge', 
							note = '$note'
						WHERE
							idx = $idx
				";
				$rs = x_SQL($sql, $cntDB);

				if ($rs)
					$ret = array('result'=>'success', 'result_msg'=>'Customer information was changed.');
				else
					$ret = array('result'=>'fail', 'error_msg'=>'Fail to add customer.');
			}
		}
	}
	else if ($action == "new") {
		$sql = "SELECT count(*) AS dep_customer_cnt FROM t_customer WHERE dep_customer_id='$dep_customer_id'";
		$row = x_FETCH($sql, $cntDB);

		if ($row[dep_customer_cnt])
			$ret = array('result'=>'fail', 'error_msg'=>'Duplicated DEP customer ID. Please use another one.');
		else {
			$sql = "SELECT count(*) AS skn_customer_cnt FROM t_customer WHERE skn_customer_id='$skn_customer_id'";
			$row = x_FETCH($sql, $cntDB);
			if ($row[skn_customer_cnt])
				$ret = array('result'=>'fail', 'error_msg'=>'Duplicated SKN customer ID. Please use another one.');
			else {
				$sql = "INSERT INTO t_customer (dep_customer_id, skn_customer_id, company, phone, email, charge, note) 
							VALUES ('$dep_customer_id', '$skn_customer_id', '$company', '$phone', '$email', '$charge', '$note')";
				$rsTemp = x_SQL($sql, $cntDB);
				$customerIdx = mysqli_insert_id($cntDB);
			
				if ($customerIdx)
					$ret = array('result'=>'success', 'result_msg'=>'Customer was added.');
				else
					$ret = array('result'=>'fail', 'error_msg'=>'Fail to add customer.');
			}
		}
	}


	header('Content-Type: application/json; charset=utf-8');
	echo json_encode( $ret );

	DBCLOSE_end($cntDB);
?>
