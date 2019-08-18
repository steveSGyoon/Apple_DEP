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
			$sql = "INSERT INTO 
						t_api_check_result
						( t_order_idx, is_success, deviceEnrollmentTransactionId, completed_on )
					VALUES 
						( $order_idx, 1, '$deviceEnrollmentTransactionId', '$completed_on' )
			"; 
			$rs = x_SQL($sql, $cntDB);

			// t_order - status change to 1
			$sql = "UPDATE t_order SET status=2, completed_date=now() WHERE idx = $order_idx"; 
			$rs = x_SQL($sql, $cntDB);
		}
		else {
			if ($deviceEnrollmentTransactionId) {
				$error_result = $result['checkTransactionErrorResponse'];
				
				$errorCode = $error_result[0]['errorCode'];
				$errorMessage = $error_result[0]['errorMessage'];		// . " " . $result['enrollDeviceErrorResponse']['statusCode'];
	
				// t_api_check_result
				$sql = "INSERT INTO 
							t_api_check_result
							( t_order_idx, is_success, errorCode, deviceEnrollmentTransactionId, errorMessage )
						VALUES 
							( $order_idx, 0, '$errorCode', '$deviceEnrollmentTransactionId', '$errorMessage' )
				"; 
				$rs = x_SQL($sql, $cntDB);
			}
			else {
				$errorCode = $result['errorCode'];
				if ($errorCode) {
					$transactionId = $result['transactionId'];
					$errorMessage = $result['errorMessage'];		// . " " . $result['enrollDeviceErrorResponse']['statusCode'];
	
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
		}
	}

	DBCLOSE_end($cntDB);
?>
