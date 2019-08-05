<?php
	include "header/header_DB.php";
	include "header/functionsY.php";
	include "class/classHttpClientUtil.php";

	$cntDB = DBCONNECT_start();

	$api_post = new HttpClientUtil();

	$sql = "SELECT * FROM t_order WHERE 1 AND status = 1";
	$rsOrder = x_SQL($sql, $cntDB);
	while ( $rowOrder = x_FETCH2($rsOrder) ) {
		$post_data = [];
		
		$post_data['requestContext']['shipTo'] = $rowOrder[ship_to];
		$post_data['requestContext']['timeZone'] = "-540";
		$post_data['requestContext']['langCode'] = "ko";
		
		$post_data['depResellerId'] = $rowOrder[dep_reseller_id];
		$post_data['deviceEnrollmentTransactionId'] = $rowOrder[transaction_id];
		
		$paramMap = json_encode($post_data);

		$reponse = $api_post->doPost($sandbox_check_url, $paramMap);
	}

	DBCLOSE_end($cntDB);

/*
	{
		"requestContext": {
			"shipTo": "0000052010",
			"timeZone": "420",
			"langCode": "en"
		},
		"depResellerId": "16FCE4A0",
		"deviceEnrollmentTransactionId": "9acc1cf5-e41d-44d4-a066-78162a389da2_1413529391461"
	}
*/

?>
