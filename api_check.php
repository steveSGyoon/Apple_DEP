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
//{ "deviceEnrollmentTransactionID" : "21719cf9-c7a1-42ee-aa56-890d47339628_1565082925632", "checkTransactionErrorResponse" : [ { "errorMessage" : "등록 요청 진행 중. 기기 등록 프로그램 거래 ID의 등록이 아직 진행 중입니다. 나중에 다시 확인하십시오.", "errorCode" : "DEP-ERR-4003" } ] }
/*
{ 
	"deviceEnrollmentTransactionID" : "fbf74c2f-dd4b-4bfe-b0db-b1e8f99c4031_1565084411614", 
	"checkTransactionErrorResponse" : [ 
		{ 
			"errorMessage" : "등록 요청 진행 중. 기기 등록 프로그램 거래 ID의 등록이 아직 진행 중입니다. 나중에 다시 확인하십시오.", 
			"errorCode" : "DEP-ERR-4003" 
		} 
	] 
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
