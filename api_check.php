<?php
	include "header/header_DB.php";
	include "header/functionsY.php";

	$cntDB = DBCONNECT_start();

	$sql = "SELECT
				deporder.*,
				enroll_result.deviceEnrollmentTransactionId AS deviceEnrollmentTransactionId
			FROM 
				t_order AS deporder
				LEFT JOIN t_api_enroll_result AS enroll_result ON enroll_result.t_order_idx = deporder.idx
			WHERE 1 
				AND deporder.status = 1
	";
	$rsOrder = x_SQL($sql, $cntDB);
	while ( $rowOrder = x_FETCH2($rsOrder) ) {
		$order_idx = $rowOrder[idx];

		$post_data = [];
		$post_data['requestContext']['shipTo'] = $rowOrder[ship_to];
		$post_data['requestContext']['timeZone'] = "-540";
		$post_data['requestContext']['langCode'] = "ko";
		$post_data['depResellerId'] = $rowOrder[dep_reseller_id];
		$post_data['deviceEnrollmentTransactionId'] = $rowOrder[deviceEnrollmentTransactionId];
		$paramMap = json_encode($post_data);

		$response = doHttpPost($sandbox_check_url, $paramMap);
		$result = json_decode($response, true);

		$status = $result['statusCode'];
		if ($status == "COMPLETE") {
			$deviceEnrollmentTransactionId = $result['deviceEnrollmentTransactionId'];

			// t_api_check_result
			$sql = "INSERT INTO 
						t_api_check_result
						( t_order_idx, is_success, deviceEnrollmentTransactionId )
					VALUES 
						( $order_idx, 1, '$deviceEnrollmentTransactionId' )
			"; 
			$rs = x_SQL($sql, $cntDB);

			// t_order - status change to 1
			$sql = "UPDATE t_order SET status=2, completed_date=now() WHERE idx = $order_idx"; 
			$rs = x_SQL($sql, $cntDB);
		}
		else {
			$errorMessage = "";
			$errorCode = $result['errorCode'];
			$transactionId = $result['transactionId'];
			$errorMessage = $result['errorMessage'];		// . " " . $result['enrollDeviceErrorResponse']['statusCode'];
			if ($errorMessage == "")
				$errorMessage = "multiple error";

			// t_api_check_result
			$sql = "INSERT INTO 
						t_api_check_result
						( t_order_idx, is_success, errorCode, transactionId, errorMessage )
					VALUES 
						( $order_idx, 0, '$errorCode', '$transactionId', '$errorMessage' )
			"; 
			$rs = x_SQL($sql, $cntDB);
		}
	}

	DBCLOSE_end($cntDB);
?>
