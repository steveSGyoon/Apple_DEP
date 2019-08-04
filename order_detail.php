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
				dep_order.*,
				customer.company AS company
			FROM 
				t_order AS dep_order
				LEFT JOIN t_customer AS customer ON customer.dep_customer_id = dep_order.dep_customer_id 
			WHERE 1
				AND dep_order.idx = $idx
	";
	$rowOrder = x_FETCH($sql, $cntDB);
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
							<tr>
								<td class="info">Ship To</td>
								<td><?=$rowOrder[ship_to]?></td>
							</tr>
							<tr>
								<td class="info">Transaction ID</td>
								<td><?=$rowOrder[transaction_id]?></td>
							</tr>
							<tr>
								<td class="info">Order Number</td>
								<td><?=$rowOrder[order_number]?></td>
							</tr>
							<tr>
								<td class="info">Order Date</td>
								<td><?=$rowOrder[order_date]?></td>
							</tr>
							<tr>
								<td class="info">DEP Customer ID</td>
								<td><?=$rowOrder[dep_customer_id]?> (<?=$rowOrder[company]?>)</td>
							</tr>
							<tr>
								<td class="info">PO Number</td>
								<td><?=$rowOrder[po_number]?></td>
							</tr>
							<tr>
								<td class="info">Deliveries</td>
								<td>
									<a href="order_detail_delivery.php?idx=<?=$rowOrder[idx]?>">
									<button type="button" class="btn btn-sm btn-default">View</button>
									</a>
								</td>
							</tr>
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
