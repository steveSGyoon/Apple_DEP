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

	$apple_apply = clearXSS(XSSfilter($_POST["apple_apply"]));
	$search_type = clearXSS(XSSfilter($_POST["search_type"]));
	$search_val = clearXSS(XSSfilter($_POST["search_val"]));

	$search_text = " - Search Result for '$search_val' ";
	if ($search_type == "order_number"){
		$search_text .= " of Order Number ";
		$whereQry = "AND dep_order.order_number LIKE '%$search_val%' ";
	}
	else if ($search_type == "dep_customer_id") {
		$search_text .= " of DEP Customer ID ";
		$whereQry = "AND dep_order.dep_customer_id LIKE '%$search_val%' ";
	}
	else if ($search_type == "transaction_id") {
		$search_text .= " of Transaction ID ";
		$whereQry = "AND dep_order.transaction_id LIKE '%$search_val%' ";
	}
	else if ($search_type == "order_date") {
		$search_text .= " of Order Date ";
		$whereQry = "AND dep_order.order_date LIKE '%$search_val%' ";
	}
	else if ($search_type == "ship_date") {
		$search_text .= " of Ship Date ";
		$whereQry = "AND dep_order.order_number IN (SELECT DISTINCT (order_number) FROM  t_order_device WHERE ship_date LIKE '%$search_val%')";
	}
	else if ($search_type == "device_id") {
		$search_text .= " of Device ID ";
		$whereQry = "AND dep_order.order_number IN (SELECT DISTINCT (order_number) FROM  t_order_device WHERE device_id LIKE '%$search_val%')";
	}
?>
</head>

<body class="smoothscroll enable-animation">
	<!-- wrapper -->
	<div id="wrapper">
		<?php
		//<!-- Top Menu -->
		include "header/menuTop.php";
		?>

		<!-----------------------------------contents section-------------------------------------------------->
		<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="box-title margin-top-30">
					<h2 class="size-20">Lookup <?=$search_text?></h2>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-striped text-center">
						<thead>
							<tr class="info">
								<th class="text-center">DEP Customer ID</th>
								<th class="text-center">SKN Customer ID</th>
								<th class="text-center">Order No.</th>
								<th class="text-center">Order Date</th>
								<th class="text-center">Type</th>
								<th class="text-center">Detail</th>
							</tr>
						</thead>
						<tbody>

						<?php
						$enroll_idx = 0;
						$sql = "SELECT
									dep_order.*,
									customer.skn_customer_id AS skn_customer_id,
									customer.company AS company
								FROM 
									t_order AS dep_order
									LEFT JOIN t_customer AS customer ON customer.dep_customer_id = dep_order.dep_customer_id 
								WHERE 1
									AND dep_order.is_valid = 1
									$whereQry
								ORDER BY 
									dep_order.order_number DESC,
									dep_order.order_date DESC
						";
						$rs = x_SQL($sql, $cntDB);
						while ( $row = x_FETCH2($rs) ) {
							$view_url = "lookup_detail.php?idx=" . $row[idx];
							if ($apple_apply == "yes") {
								$view_url = "lookup_detail_apple.php?idx=" . $row[idx];
							}
							?>
							
							<tr bgcolor='#ffffff'>
								<td><?=$row[dep_customer_id]?></td>
								<td><?=$row[skn_customer_id]?></td>
								<td><?=$row[order_number]?></td>
								<td><?=$row[order_date]?></td>
								<td><?=$row[order_type]?></td>
								<td>
									<a href="<?=$view_url?>" onclick="popupOpen(event, this.href, 'orderView', 500, 450)">
									<button type="button" class="btn btn-sm btn-default">View</button>
									</a>
								</td>
							</tr>

							<?php
							$enroll_idx++;
						}
						?>
						</tbody>
					</table><br />
				</div>
			</div>
		</div>
		<!----------------------------------- end of contents-------------------------------------------------->

		<!-- footer -->
		<footer id="footer">
			<?php
			include "header/footer.php";
			?>
		</footer>

	</div>
	<!-- /wrapper -->

	<?php
	include "subHeader/footerJS.php";
	DBCLOSE_end($cntDB);
	?>
</body>
</html>
