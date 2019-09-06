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

	$idx = clearXSS(XSSfilter($_GET["idx"]));
	$sql = "SELECT
				response
			FROM 
				t_api_check_result
			WHERE 1
				AND t_order_idx = $idx
	";
	$rowError = x_FETCH($sql, $cntDB);
	$response0 = str_replace( "'","\"", $rowError['response'] );
	$result = json_decode($response0, true);

	$deviceEnrollmentTransactionId = $result['deviceEnrollmentTransactionID'];
	$transactionId = $result['transactionId'];
	$completedOn = $result['completedOn'];
	$orders = $result['orders'];

/*
	{
		'deviceEnrollmentTransactionID':'3fafc351-d646-4194-be25-90456378bc7e_1567646658758',
		'transactionId':'20190905-1567646651-3962',
		'completedOn':'2019-09-05T01:25:33Z',
		'orders':
			[
				{
					'orderNumber':'ORDER-T7', 
					'orderPostStatus':'ERROR',
					'deliveries':
						[
							{
								'deliveryNumber':'SELF',
								'deliveryPostStatus':'DEP-ERR-DL-4203',
								'deliveryPostStatusMessage':'배송 날짜가 주문 날짜와 겹칩니다. 올바른 배송 날짜를 입력하고 요청을 다시 제출하십시오.'
							}
						]
				}
			],
		'statusCode':'ERROR'
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
					<h2 class="size-20">Error Details</h2>
				</div>

				<div class="row">
					<div class="table-responsive">
						<table class="table table-bordered table-striped text-center">
							<?php
								for ($i=0; $i<count($orders); $i++) {
									$orderNumber = $orders[$i]['orderNumber'];
									$orderPostStatus = $orders[$i]['orderPostStatus'];

									echo "
										<tr>
											<td class=info>Order Number</td>
											<td> $orderNumber </td>
										</tr>
										<tr>
											<td class=info>Order Poststatus</td>
											<td> $orderPostStatus </td>
										</tr>
										<tr>
											<td class=info>Deliveries</td>
											<td>
									";

									$deliveries = $orders[$i]['deliveries'];
									for ($j=0; $j<count($deliveries); $j++) {
										$deliveryPostStatus = $deliveries[$j]['deliveryPostStatus'];
										$deliveryPostStatusMessage = $deliveries[$j]['deliveryPostStatusMessage'];

										echo "
										$deliveryPostStatus <br />
										$deliveryPostStatusMessage <br />
										";
									}

									echo "
											</td>
										</tr>
									";
								}
							?>
						</table>
					</div>
				</div>
				<div class="row text-center">
					<a href="Javascript:close_window()">
					<button type="button" class="btn btn-sm btn-primary">Close</button>
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
