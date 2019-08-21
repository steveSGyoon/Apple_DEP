<?php
	$DBSERVER = "localhost";
	$DBUSER = "usr_dep";
	$DBPASSWORD = "dovmfeldlvl1*";
	$DBNAME = "_apple_DEP";

	$sandbox_enroll_url = "https://acc-ipt.apple.com/enroll-service/1.0/bulk-enroll-devices";
	$sandbox_check_url = "https://acc-ipt.apple.com/enroll-service/1.0/check-transaction-status";
	$sandbox_order_detail_url = "https://acc-ipt.apple.com/enroll-service/1.0/show-order-details";

	$uat_enroll_url = "https://api-applecareconnect-ept.apple.com/enroll-service/1.0/bulk-enroll-devices";
	$uat_check_url = "https://api-applecareconnect-ept.apple.com/enroll-service/1.0/check-transaction-status";
	$uat_order_detail_url = "https://api-applecareconnect-ept.apple.com/enroll-service/1.0/show-order-details";

	$enroll_url = $uat_enroll_url;
	$check_url = $uat_check_url;
	$order_detail_url = $uat_order_detail_url;
?>