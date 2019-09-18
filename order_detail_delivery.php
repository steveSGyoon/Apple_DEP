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
	$ov = clearXSS(XSSfilter($_GET["ov"]));
	if ($ov)
		$ov = " (" . $ov . ")";

	$delivery_number = [];

	$sql = "SELECT DISTINCT(delivery_number) FROM t_order_device WHERE t_order_idx = $idx";
	$rs = x_SQL($sql, $cntDB);
	while ( $row = x_FETCH2($rs) ) {
		$delivery_number[] = $row[delivery_number];
	}

	$sql = "SELECT ship_date FROM t_order WHERE idx=$idx";
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
					<h2 class="size-20">View Deliveries <?=$ov?></h2>
				</div>

				<?php
				for ($i=0; $i<count($delivery_number); $i++) {
					?>
					<div class="row">
						Delivery Number: <?=$delivery_number[$i]?><br />
						Ship Date:  <?=$rowOrder[ship_date]?><br />

						<div class="table-responsive">
							<table class="table table-bordered table-striped text-center">
								<thead>
									<tr class="info">
										<th class="text-center">Device ID</th>
										<th class="text-center">Asset Tag</th>
										<th class="text-center">Posst Status</th>
									</tr>
								</thead>
								<tbody>

								<?php
								$sql = "SELECT
											*
										FROM 
											t_order_device 
										WHERE 1
											AND delivery_number = '$delivery_number[$i]'
											AND t_order_idx = $idx
								";
								$rs = x_SQL($sql, $cntDB);
								while ( $row = x_FETCH2($rs) ) {
									$postInfo = "";
									if ($row[devicePostStatus])
										$postInfo .= $row[devicePostStatus];
									if ($row[devicePostStatus])
										$postInfo .= "<br />" . $row[devicePostStatusMessage];
									?>
									<tr bgcolor='#ffffff'>
										<td><?=$row[device_id]?></td>
										<td><?=$row[asset_tag]?></td>
										<td>
											<?=$postInfo?>
										</td>
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
