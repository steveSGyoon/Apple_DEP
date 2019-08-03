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
					<h2 class="size-20">DCM (DEP Customer Management)</h2>
				</div>

				<a href="customer_detail.php" onclick="popupOpen(event, this.href, 'customerNew', 800, 600)">
				<button type="button" class="btn btn-sm btn-primary">New Customer</button>
				</a><br /><br />

				<div class="table-responsive">
					<table class="table table-bordered table-striped text-center">
						<thead>
							<tr class="info">
								<th class="text-center">DEP Customer ID</th>
								<th class="text-center">Company</th>
								<th class="text-center">SKN Customer ID</th>
								<th class="text-center">Note</th>
								<th class="text-center">View</th>
								<th class="text-center">Edit</th>
							</tr>
						</thead>
						<tbody>

						<?php
						$sql = "SELECT * FROM t_customer WHERE is_valid=1 ORDER BY company ASC";
						$rsCustomer = x_SQL($sql, $cntDB);
						while ( $rowCustomer = x_FETCH2($rsCustomer) ) {
							?>
							<tr bgcolor='#ffffff'>
								<td><?=$rowCustomer[dep_customer_id]?></td>
								<td><?=$rowCustomer[company]?></td>
								<td><?=$rowCustomer[skn_customer_id]?></td>
								<td><?=$rowCustomer[note]?></td>
								<td>
									<a href="customer_detail.php?idx=<?=$rowCustomer[idx]?>&action=view" onclick="popupOpen(event, this.href, 'customerView', 800, 600)">
									<button type="button" class="btn btn-sm btn-default">View</button>
									</a>
								</td>
								<td>
									<a href="customer_detail.php?idx=<?=$rowCustomer[idx]?>&action=edit" onclick="popupOpen(event, this.href, 'customerEdit', 800, 600)">
									<button type="button" class="btn btn-sm btn-default">Edit</button>
									</a>
								</td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table><br /><br />
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
