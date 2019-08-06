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
					<h2 class="size-20">Override</h2>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-striped text-center">
						<thead>
							<tr class="info">
								<th class="text-center">DEP Customer ID</th>
								<th class="text-center">SKN Customer ID</th>
								<th class="text-center">Company</th>
								<th class="text-center">Order No.</th>
								<th class="text-center">Detail</th>
							</tr>
						</thead>
						<tbody>

						<?php
						$sql = "SELECT
									dep_order.*,
									customer.skn_customer_id AS skn_customer_id,
									customer.company AS company
								FROM 
									t_order AS dep_order
									LEFT JOIN t_customer AS customer ON customer.dep_customer_id = dep_order.dep_customer_id 
								WHERE 1
									AND dep_order.order_type = 'OV'
									AND dep_order.status = 0
									AND dep_order.is_void = 0
									AND dep_order.is_valid = 1
								ORDER BY 
									dep_order.order_date DESC
						";
						$rs = x_SQL($sql, $cntDB);
						while ( $row = x_FETCH2($rs) ) {
							?>
							<tr bgcolor='#ffffff'>
								<td><?=$row[dep_customer_id]?></td>
								<td><?=$row[skn_customer_id]?></td>
								<td><?=$row[company]?></td>
								<td><?=$row[order_number]?></td>
								<td>
									<a href="override_detail.php?idx=<?=$row[idx]?>" onclick="popupOpen(event, this.href, 'orderView', 500, 450)">
									<button type="button" class="btn btn-sm btn-default">View</button>
									</a>
								</td>
							</tr>
							<?php
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
