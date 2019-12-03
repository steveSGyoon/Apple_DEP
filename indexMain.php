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
			<div class='row'>					
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="box-title margin-top-30">
						<h2 class="size-20">ORDER Status</h2>
					</div>

					<!-- TABLE 1 -->
					<table class="table table-bordered table-striped text-center">
						<thead>
							<tr class="info">
								<th class="text-center">DEP Customer ID</th>
								<th class="text-center">SKN Order No.</th>
								<th class="text-center">Date</th>
								<th class="text-center">Status</th>
							</tr>
						</thead>
						<tbody>

						<?php
						$sql = "SELECT * FROM t_order WHERE order_type='OR' AND status!=0 AND is_void=0 AND is_valid=1 ORDER BY order_date DESC";
						$rs = x_SQL($sql, $cntDB);
						while ( $row = x_FETCH2($rs) ) {
							if ($row[is_valid] == 0) {
								$status = "
									<a href='api_result.php?idx=$row[idx]' onclick='popupOpen(event, this.href, \"failView\", 500, 450)'>
									<button type='button' class='btn btn-sm btn-default'>Fail</button>
									</a>";
							}
							else {
								if ($row[status] == 1)
									$status = "In Progress";
								else if ($row[status] == 2)
									$status = "Success";
								else
									$status = "Unknown";
							}
							?>
							<tr bgcolor='#ffffff'>
								<td><?=$row[dep_customer_id]?></td>
								<td><?=$row[order_number]?></td>
								<td><?=$row[completed_date]?></td>
								<td><?=$status?></td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table><br />
					<!-- TABLE 1 -->
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="box-title margin-top-30">
						<h2 class="size-20">RETURN Status</h2>
					</div>

					<!-- TABLE 2 -->
					<table class="table table-bordered table-striped text-center">
						<thead>
							<tr class="info">
								<th class="text-center">DEP Customer ID</th>
								<th class="text-center">SKN Order No.</th>
								<th class="text-center">Date</th>
								<th class="text-center">Status</th>
							</tr>
						</thead>
						<tbody>

						<?php
						$sql = "SELECT * FROM t_order WHERE order_type='RE' AND status!=0 AND is_void=0 AND is_valid=1 ORDER BY order_date DESC";
						$rs = x_SQL($sql, $cntDB);
						while ( $row = x_FETCH2($rs) ) {
							if ($row[is_valid] == 0) {
								$status = "
									<a href='api_result.php?idx=$row[idx]' onclick='popupOpen(event, this.href, \"failView\", 500, 450)'>
									<button type='button' class='btn btn-sm btn-default'>Fail</button>
									</a>";
							}
							else {
								if ($row[status] == 1)
									$status = "In Progress";
								else if ($row[status] == 2)
									$status = "Success";
								else
									$status = "Unknown";
							}
							?>
							<tr bgcolor='#ffffff'>
								<td><?=$row[dep_customer_id]?></td>
								<td><?=$row[order_number]?></td>
								<td><?=$row[completed_date]?></td>
								<td><?=$status?></td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table><br />
					<!-- TABLE 2 -->
				</div>
			</div>
			<div class='row'>					
			<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="box-title margin-top-30">
						<h2 class="size-20">OVERRIDE Status</h2>
					</div>

					<!-- TABLE 4 -->
					<table class="table table-bordered table-striped text-center">
						<thead>
							<tr class="info">
								<th class="text-center">DEP Customer ID</th>
								<th class="text-center">SKN Order No.</th>
								<th class="text-center">Date</th>
								<th class="text-center">Status</th>
							</tr>
						</thead>
						<tbody>

						<?php
						$sql = "SELECT * FROM t_order WHERE order_type='OV' AND status!=0 AND is_void=0 AND is_valid=1 ORDER BY order_date DESC";
						$rs = x_SQL($sql, $cntDB);
						while ( $row = x_FETCH2($rs) ) {
							if ($row[is_valid] == 0) {
								$status = "
									<a href='api_result.php?idx=$row[idx]' onclick='popupOpen(event, this.href, \"failView\", 500, 450)'>
									<button type='button' class='btn btn-sm btn-default'>Fail</button>
									</a>";
							}
							else {
								if ($row[status] == 1)
									$status = "In Progress";
								else if ($row[status] == 2)
									$status = "Success";
								else
									$status = "Unknown";
							}
							?>
							<tr bgcolor='#ffffff'>
								<td><?=$row[dep_customer_id]?></td>
								<td><?=$row[order_number]?></td>
								<td><?=$row[completed_date]?></td>
								<td><?=$status?></td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table><br />
					<!-- TABLE 4 -->
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="box-title margin-top-30">
						<h2 class="size-20">VOID Status</h2>
					</div>

					<!-- TABLE 3 -->
					<table class="table table-bordered table-striped text-center">
						<thead>
							<tr class="info">
								<th class="text-center">DEP Customer ID</th>
								<th class="text-center">SKN Order No.</th>
								<th class="text-center">Date</th>
								<th class="text-center">Status</th>
							</tr>
						</thead>
						<tbody>

						<?php
						$sql = "SELECT * FROM t_order WHERE status!=0 AND is_void=1 AND is_valid=0 ORDER BY order_date DESC";
						$rs = x_SQL($sql, $cntDB);
						while ( $row = x_FETCH2($rs) ) {
							if ($row[status] == 1)
								$status = "In Progress";
							else if ($row[status] == 2)
								$status = "Success";
							else
								$status = "Unknown";
							?>
							<tr bgcolor='#ffffff'>
								<td><?=$row[dep_customer_id]?></td>
								<td><?=$row[order_number]?></td>
								<td><?=$row[completed_date]?></td>
								<td><?=$status?></td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table><br />
					<!-- TABLE 3 -->
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
