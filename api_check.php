<?php
	include "header/header_DB.php";
	include "header/functionsY.php";

	$cntDB = DBCONNECT_start();

	///////////////////////////////// for testing  //////////////////////
	$sql = "INSERT INTO t_test (insert_date) VALUES (now())";
	$rs = x_SQL($sql, $cntDB);
	///////////////////////////////// for testing  //////////////////////

	
	$sql = "SELECT
				deporder.*,
				enroll_result.deviceEnrollmentTransactionId AS deviceEnrollmentTransactionId
			FROM 
				t_order AS deporder
				LEFT JOIN t_api_enroll_result AS enroll_result ON enroll_result.t_order_idx = deporder.idx
			WHERE 1 
				AND enroll_result.is_success = 1
				AND enroll_result.is_valid = 1
				AND deporder.status = 1
				AND deporder.is_valid = 1
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

		$response = doHttpPost($check_url, $paramMap);
		$result = json_decode($response, true);

//echo $response;
/*
{ 
{ 
	"deviceEnrollmentTransactionID" : "fbf74c2f-dd4b-4bfe-b0db-b1e8f99c4031_1565084411614", 
	"checkTransactionErrorResponse" : [ 
		{ 
			"errorMessage" : "등록 요청 진행 중. 기기 등록 프로그램 거래 ID의 등록이 아직 진행 중입니다. 나중에 다시 확인하십시오.", 
			"errorCode" : "DEP-ERR-4003" 
		} 
	] 
}

{
	"deviceEnrollmentTransactionID":"a08974a5-afb1-4f29-9cdc-1e81599bf076_1565085227908",
	"statusCode":"COMPLETE",
	"orders":[
		{
			"orderNumber":"ORDER_900123",
			"orderPostStatus":"COMPLETE",
			"deliveries":[
				{
					"deliveryNumber":"D1.2",
					"deliveryPostStatus":"COMPLETE",
					"devices":[
						{
							"devicePostStatus":"COMPLETE",
							"deviceId":"33645004YAM"
						},
						{
							"devicePostStatus":"COMPLETE",
							"deviceId":"33645006YAM"
						}
					]
				}
			]
		}
	],
	"completedOn":"2019-08-06T10:10:27Z"
}
{
	"deviceEnrollmentTransactionID":"5f4275a8-b9ba-4986-b990-9ac85fe36901_1565085940759",
	"statusCode":"COMPLETE",
	"orders":[
		{
			"orderNumber":"ORDER_900123",
			"orderPostStatus":"DEP-ERR-OR-4113",
			"orderPostStatusMessage":"Void order invalid. The void (VD) order type is invalid because it contains delivery detail. Remove the deliveries and resubmit the request."
		}
	],
	"completedOn":"2019-08-06T10:10:28Z"
}

*/
		$status = $result['statusCode'];
		$deviceEnrollmentTransactionId = $result['deviceEnrollmentTransactionID'];
		$completed_on = $result['completedOn'];
		if ($status == "COMPLETE") {
			// t_api_check_result
			$response0 = str_replace( "\"","'", $response );
			$sql = "INSERT INTO 
						t_api_check_result
						( t_order_idx, is_success, send_data, deviceEnrollmentTransactionId, completed_on, response )
					VALUES 
						( $order_idx, 1, '$paramMap', '$deviceEnrollmentTransactionId', '$completed_on', \"$response0\" )
			"; 
			$rs = x_SQL($sql, $cntDB);

			// t_order - status change to 1
			if ($rowOrder['is_void'] == 1) {
				$sql = "UPDATE t_order SET status=2, completed_date=now(), is_valid=0 WHERE idx = $order_idx"; 
				$rs = x_SQL($sql, $cntDB);
			}
			else {
				$sql = "UPDATE t_order SET status=2, completed_date=now() WHERE idx = $order_idx"; 
				$rs = x_SQL($sql, $cntDB);
			}
		}
		else {
			// t_order - status change to 1
			$sql = "UPDATE t_order SET status=0, completed_date=now() WHERE idx = $order_idx"; 
			$rs = x_SQL($sql, $cntDB);

			$sql = "UPDATE t_api_enroll_result SET is_valid=0 WHERE t_order_idx = $order_idx"; 
			$rs = x_SQL($sql, $cntDB);

			if ($deviceEnrollmentTransactionId) {
				/*
				$error_result = $result['checkTransactionErrorResponse'];
				
				$errorCode = $error_result[0]['errorCode'];
				$errorMessage = $error_result[0]['errorMessage'];		// . " " . $result['enrollDeviceErrorResponse']['statusCode'];
				*/
				$rt_orders = $result['orders'];

				// t_api_check_result
				$response0 = str_replace( "\"","'", $response );
				for ($io=0; $io<count($rt_orders); $io++) {
					$errorCode = $rt_orders[$io]['orderPostStatus'];
					$errorMessage = $rt_orders[$io]['orderPostStatusMessage'];

					$sql = "INSERT INTO 
								t_api_check_result
								( t_order_idx, is_success, send_data, errorCode, deviceEnrollmentTransactionId, errorMessage, response )
							VALUES 
								( $order_idx, 0, '$paramMap', '$errorCode', '$deviceEnrollmentTransactionId', '$errorMessage', \"$response0\" )
					"; 
					$rs = x_SQL($sql, $cntDB);
				}
			}
			else {
				$errorCode = $result['errorCode'];
				if ($errorCode) {
					$transactionId = $result['transactionId'];
					$errorMessage = $result['errorMessage'];		// . " " . $result['enrollDeviceErrorResponse']['statusCode'];
	
					// t_api_check_result
					$response0 = str_replace( "\"","'", $response );
					$sql = "INSERT INTO 
								t_api_check_result
								( t_order_idx, is_success, send_data, errorCode, transactionId, errorMessage, response )
							VALUES 
								( $order_idx, 0, '$paramMap', '$errorCode', '$transactionId', '$errorMessage', \"$response0\" )
					"; 
					$rs = x_SQL($sql, $cntDB);
				}
			}
		}
	}

	DBCLOSE_end($cntDB);
?>
