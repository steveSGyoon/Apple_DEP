<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
<?php
	include "header/header.php";
	include "header/functionsY.php";
	include "header/_checkLogin.php";
	include "header/_checkOR.php";

	$cntDB = DBCONNECT_start();

	$order_idx = clearXSS(XSSfilter($_GET["order_idx"]));

	$paramMap = make_order_json_string_for_detail($order_idx, $cntDB);
	$response = doHttpPost($order_detail_url, $paramMap);
	$result = json_decode($response, true);

	$orders = $result['orders'];
	$orderNumber = $orders[0]['orderNumber'];
	$orderDate = $orders[0]['orderDate'];
	$orderType = $orders[0]['orderType'];
	$customerId = $orders[0]['customerId'];
	$deliveries = $orders[0]['deliveries'];
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
					<h2 class="size-20">View Deliveries <?=$ov?></h2>
				</div>

				<?php
				for ($i=0; $i<count($deliveries); $i++) {
					$devices = $deliveries[$i]['devices'];
					?>
					<div class="row">
						Delivery Number: <?=$deliveries[$i]['deliveryNumber']?><br />
						Ship Date:  <?=$deliveries[$i]['shipDate']?><br />

						<div class="table-responsive">
							<table class="table table-bordered table-striped text-center">
								<thead>
									<tr class="info">
										<th class="text-center">Device ID</th>
										<th class="text-center">Asset Tag</th>
									</tr>
								</thead>
								<tbody>

								<?php
								for ($j=0; $j<count($devices); $j++) {
									?>
									<tr bgcolor='#ffffff'>
										<td><?=$devices[$j]['deviceId']?></td>
										<td></td>
									</tr>
									<?php
								}
								?>
								</tbody>
							</table>
						</div>
					</div><br />
					<?php
				}
				?>

				<div class='row'>
					<div class="row text-center">
						<a href="Javascript:close_window()">
						<button type="button" class="btn btn-sm btn-primary">Close</button>
						</a>
					</div>
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
