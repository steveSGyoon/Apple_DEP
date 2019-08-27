<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
<?php
	include "header/header.php";
	include "header/functionsY.php";
	include "header/_checkLogin.php";

	$cntDB = DBCONNECT_start();

	$errorCode = "";
	$order_idx = clearXSS(XSSfilter($_GET["idx"]));

	$paramMap = make_order_json_string_for_detail($order_idx, $cntDB);
	$response = doHttpPost($order_detail_url, $paramMap);
	$result = json_decode($response, true);

	$statusCode = $result['statusCode'];
	if ($statusCode == "COMPLETE") {
		$respondedOn = $result['respondedOn'];
		$response0 = str_replace( "\"","'", $response );
		$sql = "INSERT INTO 
					t_api_detail_result
					( t_order_idx, is_success, send_data, response, completed_on)
				VALUES 
					( $order_idx, 1, '$paramMap', \"$response0\", '$respondedOn')
		"; 
		$rs = x_SQL($sql, $cntDB0);

		$orderNumber = $result['orderNumber'];
		$orderDate = $result['orderDate'];
		$orderType = $result['orderType'];
		$customerId = $result['customerId'];
	}
	else {
		$errorCode = $result['errorCode'];
		if ($errorCode) {
			$errorMessage = $result['errorMessage'];
			$transactionId = $result['transactionId'];

			$sql = "INSERT INTO 
						t_api_detail_result
						( t_order_idx, is_success, send_data, response, transactionId, errorCode, errorMessage)
					VALUES 
						( $order_idx, 0, '$paramMap', \"$response0\", '$transactionId', '$errorCode', \"$errorMessage\")
			"; 
			$rs = x_SQL($sql, $cntDB0);
		}
		else {
			$showOrderErrorResponse = $result['showOrderErrorResponse'];
			$errorCode = $showOrderErrorResponse['errorCode'];
			$errorMessage = $showOrderErrorResponse['errorMessage'];
			if ($errorCode) {
				$sql = "INSERT INTO 
							t_api_detail_result
							( t_order_idx, is_success, send_data, response, errorCode, errorMessage)
						VALUES 
							( $order_idx, 0, '$paramMap', \"$response0\", '$errorCode', \"$errorMessage\")
				"; 
				$rs = x_SQL($sql, $cntDB0);
			}
			else {
				$errorCode = "unknown";
				$errorMessage = "multiple error";
				$sql = "INSERT INTO 
							t_api_detail_result
							( t_order_idx, is_success, send_data, response, errorCode, errorMessage)
						VALUES 
							( $order_idx, 0, '$paramMap', \"$response0\", '$errorCode', '$errorMessage')
				"; 
				$rs = x_SQL($sql, $cntDB0);
			}
		}
	}

/*	success
	{
		"orders":
		[
			{
				"orderNumber":"ORDER-T2",
				"orderDate":"2017-01-03T10:10:00Z",
				"orderType":"OR",
				"customerId":"10000",
				"deliveries":[
					{
						"deliveryNumber":"SELF",
						"shipDate":"2017-01-04T10:00:00Z",
						"devices":[
							{"deviceId":"C01W103MHG7F"},
							{"deviceId":"C01W103NHG7F"}
							]
					}
				]
			}
		],
		"statusCode":"COMPLETE",
		"respondedOn":"2019-08-27T07:08:57Z"
	}

	ship error
	{
		"errorCode": "GRX-50025",
		"errorMessage": "Ship-To entered is not valid. Please enter a valid Ship-To",
		"transactionId": "13836d68-7353-4acc-a813-0e217be8f04d-1424990915792"
	}

	Transaction Single Error
	{
		"showOrderErrorResponse": [{
			"errorMessage": "Access denied. You do not have permission to act on behalf of the Device Enrollment Program reseller ID.",
			"errorCode": "GRX-90004"
		}]
	}
*/
?>
</head>

<body class="smoothscroll enable-animation">
	<!-- wrapper -->
	<div id="wrapper">

		<!-----------------------------------contents section-------------------------------------------------->
		<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12 text-center">
				<div class="box-title margin-top-30">
					<h2 class="size-20">View Details</h2>
				</div>

				<div class="row">
					<div class="table-responsive">
						<table class="table table-bordered table-striped text-center">
						<?php
							if ($errorCode == "") {
								?>
								<tr>
									<td class="info">Ship To</td>
									<td><font color='blue'><?=$rowOrder[ship_to]?></font></td>
								</tr>
								<tr>
									<td class="info">Transaction ID</td>
									<td><font color='blue'><?=$rowOrder[transaction_id]?></font></td>
								</tr>
								<tr>
									<td class="info">Order Number</td>
									<td><?=$orderNumber?></td>
								</tr>
								<tr>
									<td class="info">Order Date</td>
									<td><?=$orderDate?></td>
								</tr>
								<tr>
									<td class="info">DEP Customer ID</td>
									<td><?=$customerId?></td>
								</tr>
								<tr>
									<td class="info">Type</td>
									<td><?=$orderType?></td>
								</tr>
								<tr>
									<td class="info">PO Number</td>
									<td> - </td>
								</tr>
								<tr>
									<td class="info">Deliveries</td>
									<td>
										<a href="order_detail_delivery.php?idx=<?=$rowOrder[idx]?>">
										<button type="button" class="btn btn-sm btn-default">View</button>
										</a>
									</td>
								</tr>
								<?php
							}
							else {
								?>
								<tr>
									<td class="info">Error Code</td>
									<td><font color='red'><?=$errorCode?></font></td>
								</tr>
								<tr>
									<td class="info">Error Message</td>
									<td><font color='red'><?=$errorMessage?></font></td>
								</tr>
								<?php
							}
						?>
						</table>
					</div>
				</div>
				<div class="row text-center">
					<a href="Javascript:close_window()">
					<button type="button" class="btn btn-sm btn-default margin-right-10">Close</button>
					</a>

					<a href="lookup_save.php?idx=<?=$rowOrder[idx]?>" onclick="popupOpen(event, this.href, 'saveView', 600, 500)">
					<button type="button" class="btn btn-sm btn-primary">Save</button>
					</a>
				</div>
			</div>
		</div>
		<!----------------------------------- end of contents-------------------------------------------------->
	</div>
	<!-- /wrapper -->

	<?php
	include "subHeader/footerJS.php";
	DBCLOSE_end($cntDB);
	?>

	<script type="text/javascript">
        function close_window() {
			self.close();
		}
	</script>
</body>
</html>
