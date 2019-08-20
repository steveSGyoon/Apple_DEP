<?php
	include "header/header_DB.php";
	include "header/functionsY.php";

	$cntDB0 = DBCONNECT_start();

	$ret = [];
	$ret['result'] = "success";
	$ret['result_msg'] = "result message = \n";

	$enroll_cnt = $_POST["enroll_cnt"];
	for ($i=0; $i<$enroll_cnt; $i++) {
		$enroll_name = "enroll_" . $i;
		$order_idx = $_POST[$enroll_name];

		// OV or VO case - make is_valid=0 for existing order 
		$old_order_idx = $_POST["old_order_idx"];
		$void_ok = $_POST["void_ok"];
		if ($old_order_idx) {
			$sql = "UPDATE t_order SET is_valid=0, edit_date=now() WHERE idx = $old_order_idx"; 
			$rs = x_SQL($sql, $cntDB0);

			$sql = "UPDATE t_order_device SET is_valid=0 WHERE t_order_idx = $old_order_idx"; 
			$rs = x_SQL($sql, $cntDB0);
		}
		else if ($void_ok) {
			$sql = "UPDATE t_order SET status=1, is_void=1, is_valid=0, edit_date=now() WHERE idx = $order_idx"; 
			$rs = x_SQL($sql, $cntDB0);
		}

		if (!$void_ok)
			$void_ok = 0;
		// OV or VO case - make is_valid=0 for existing order 

		$paramMap = make_order_json_string($order_idx, $void_ok, $cntDB0);
		$response = doHttpPost($sandbox_enroll_url, $paramMap);
		$result = json_decode($response, true);

		$status = $result['enrollDevicesResponse']['statusCode'];
		if ($status == "SUCCESS") {
			$deviceEnrollmentTransactionId = $result['deviceEnrollmentTransactionId'];

			// t_api_enroll_result
			$sql = "INSERT INTO 
						t_api_enroll_result
						( t_order_idx, is_success, deviceEnrollmentTransactionId, response)
					VALUES 
						( $order_idx, 1, '$deviceEnrollmentTransactionId', \"$response\")
			"; 
			$rs = x_SQL($sql, $cntDB0);

			// t_order - status change to 1
			$sql = "UPDATE t_order SET status=1, applied_date=now() WHERE idx = $order_idx"; 
			$rs = x_SQL($sql, $cntDB0);

			$ret['result_msg'] .= $order_idx . ":success\n";
		}
		else {
			$errorMessage = "";
			$errorCode = $result['errorCode'];
			$transactionId = $result['transactionId'];
			$errorMessage = $result['errorMessage'];		// . " " . $result['enrollDeviceErrorResponse']['statusCode'];
			if ($errorMessage == "")
				$errorMessage = "multiple error";

			// t_api_enroll_result
			$sql = "INSERT INTO 
						t_api_enroll_result
						( t_order_idx, is_success, errorCode, transactionId, errorMessage, response )
					VALUES 
						( $order_idx, 0, '$errorCode', '$transactionId', '$errorMessage', \"$response\" )
			"; 
			$rs = x_SQL($sql, $cntDB0);

			// t_order - make this order invalid
			$sql = "UPDATE t_order SET is_valid=0, edit_date=now() WHERE idx = $order_idx"; 
			$rs = x_SQL($sql, $cntDB0);

			$ret['result_msg'] .= $order_idx . ":param error\n";
		}
	}


	
/*
	$order_idx = 5;
	$paramMap = make_order_json_string($order_idx, $cntDB0);
	echo $paramMap;
	$response = doHttpPost($sandbox_enroll_url, $paramMap);
	echo $response;
	echo "<br><br><br>";
	echo json_decode($response);

	//success case
	$response = "
			{
				\"deviceEnrollmentTransactionId\":\"1467e89b-6a93-487b-abbc-d6aab66c8ee0_1564986930113\",
				\"enrollDevicesResponse\":{
					\"statusCode\":\"SUCCESS\",
					\"statusMessage\":\"Transaction posted successfully in DEP\"
				}
			}
	";
	$arr = json_decode($response, true);
	echo "array length=" . count($arr) . "<br>";;
	echo "deviceEnrollmentTransactionId=" . $arr['deviceEnrollmentTransactionId'] . "<br>";
	echo "statusCode=" . $arr['enrollDevicesResponse']['statusCode'] . "<br>";
	echo "statusMessage=" . $arr['enrollDevicesResponse']['statusMessage'] . "<br>";
	echo "<br><br><br>";


	//error case 1
	$response = "
			{
				\"errorCode\": \"GRX-50025\",
				\"errorMessage\": \"Ship-To entered is not valid. Please enter a valid Ship-To\",
				\"transactionId\": \"13836d68-7353-4acc-a813-0e217be8f04d-1424990915792\"
			}
	";
	$arr = json_decode($response, true);
	echo "array length=" . count($arr) . "<br>";;
	echo "errorCode=" . $arr['errorCode'] . "<br>";
	echo "errorMessage=" . $arr['errorMessage'] . "<br>";
	echo "transactionId=" . $arr['transactionId'] . "<br>";
	echo "statusCode=" . $arr['enrollDevicesResponse']['statusCode'] . "<br>";
	echo "<br><br><br>";
	

	//erroe case 2
	$response = "
			{
				\"enrollDeviceErrorResponse\": {
					\"errorCode\": \"GRX-1056\",
					\"errorMessage\": \"DEP Reseller ID missing. Enter a valid DEP Reseller ID and resubmit your request.\"
				}
			}	
	";
	$arr0 = json_decode($response, true);
	$arr = $arr0['enrollDeviceErrorResponse'];
	echo "array length=" . count($arr) . "<br>";;
	echo "errorCode=" . $arr['errorCode'] . "<br>";
	echo "errorMessage=" . $arr['errorMessage'] . "<br>";
	echo "statusCode=" . $arr['enrollDevicesResponse']['statusCode'] . "<br>";
	echo "<br><br><br>";
	

	//erroe case 3
	$response = "
			{
				\"enrollDeviceErrorResponse\": 
						[
							{
								\"errorCode\": \"\"GRX-1056\",
								\"errorMessage\": \"DEP Reseller ID missing. Enter a valid DEP Reseller ID and resubmit your request.\"
							},
							{
								\"errorCode\": \"DEP-ERR-3003\",
								\"errorMessage\": \"Order information missing. The transaction needs to have one or more valid orders. Enter valid orders and resubmit your request.\"
							},
							{
								\"errorCode\": \"DEP-ERR-3001\",
								\"errorMessage\": \"Transaction ID missing. Enter a valid transaction ID and resubmit your request.\"
							}
						]
			}
	";
	//echo $response . "<br>";

	$arr0 = json_decode($response, true);
	print_r($arr0);

	$arr = $arr0['enrollDeviceErrorResponse'];
	print_r($arr);

	echo "array length=" . count($arr) . "<br>";;
	echo "errorCode=" . $arr[0]['errorCode'] . "<br>";
	echo "errorMessage=" . $arr[0]['errorMessage'] . "<br>";
	echo "statusCode=" . $arr['enrollDevicesResponse']['statusCode'] . "<br>";
	echo "<br><br><br>";
*/
	
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode( $ret );

	DBCLOSE_end($cntDB0);
?>
