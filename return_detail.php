<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
<?php
	include "header/header.php";
	include "header/functionsY.php";
	include "header/_checkLogin.php";
	include "header/_checkMenuRE.php";

	$cntDB = DBCONNECT_start();

	$idx = clearXSS(XSSfilter($_GET["idx"]));
	$sql = "SELECT
				dep_order.*,
				customer.skn_customer_id AS skn_customer_id,
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
					Order Number : <?=$rowOrder[order_number]?><br />
					DEP Customer ID : <?=$rowOrder[dep_customer_id]?> (<?=$rowOrder[company]?>)<br />
					Order Date: <?=$rowOrder[order_date]?><br />

					<div class="table-responsive">
						<table class="table table-bordered table-striped text-center">
							<thead>
								<tr class="info">
									<th class="text-center">Device ID</th>
									<th class="text-center">Part Name</th>
									<th class="text-center">Part Desc.</th>
									<th class="text-center">Asset Tag</th>
								</tr>
							</thead>
							<tbody>

							<?php
							$sql = "SELECT
										*
									FROM 
										t_order_device 
									WHERE 1
										AND t_order_idx = $idx
							";
							$rs = x_SQL($sql, $cntDB);
							while ( $row = x_FETCH2($rs) ) {
								?>
								<tr bgcolor='#ffffff'>
									<td><?=$row[device_id]?></td>
									<td><?=$row[part_name]?></td>
									<td><?=$row[part_desc]?></td>
									<td><?=$row[asset_tag]?></td>
								</tr>
								<?php
							}
							?>
							</tbody>
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
