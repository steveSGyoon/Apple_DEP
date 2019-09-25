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

	$xcelFileName = "_upLoad/save_file" . "_" . date("Ymd") . ".csv";

	$headerString = "View Details\n";
	$bodyString = "";

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

	$bodyString .= "Ship To,";
	$bodyString .= $rowOrder[ship_to] . "\n";
	$bodyString .= "Ship Date,";
	$bodyString .= $rowOrder[ship_date] . "\n";
	$bodyString .= "Transaction ID	,";
	$bodyString .= $rowOrder[transaction_id] . "\n";
	$bodyString .= "Order Number,";
	$bodyString .= $rowOrder[order_number] . "\n";
	$bodyString .= "Order Date,";
	$bodyString .= $rowOrder[order_date] . "\n";
	$bodyString .= "DEP Customer ID,";
	$bodyString .= $rowOrder[dep_customer_id] . "(" . $rowOrder[company] . ")\n";
	$bodyString .= "Type,";
	$bodyString .= $rowOrder[order_type] . "\n";
	$bodyString .= "PO Number,";
	$bodyString .= $rowOrder[po_number] . "\n\n";

	$sql = "SELECT
				*
			FROM 
				t_order_device
			WHERE 1
				AND t_order_idx = $idx
	";
	$rsDevice = x_SQL($sql, $cntDB);
	while ( $rowDevice = x_FETCH2($rsDevice) ) {
		$bodyString .= "Delivery Number,";
		$bodyString .= $rowDevice[delivery_number] . "\n";
		$bodyString .= "Device ID,";
		$bodyString .= $rowDevice[device_id] . "\n";
		$bodyString .= "Asset Tag,";
		$bodyString .= $rowDevice[asset_tag] . "\n";

		if ($rowDevice[devicePostStatus]) {
			$bodyString .= "Post Status,";
			$bodyString .= $rowDevice[devicePostStatus] . "\n";
		}
		if ($rowDevice[devicePostStatusMessage]) {
			$bodyString .= "Post Status Message,";
			$bodyString .= $rowDevice[devicePostStatusMessage] . "\n";
		}
	}

	
	$headerStringO = iconv("UTF-8", "EUC-KR", $headerString);
	$bodyStringO = iconv("UTF-8", "EUC-KR", $bodyString);

	$csvFile = fopen($xcelFileName, "w");
	fwrite($csvFile, $headerStringO);
	fwrite($csvFile, $bodyStringO);
	fclose($csvFile);
?>
</head>

<body class="smoothscroll enable-animation">
	<!-- wrapper -->
	<div id="wrapper">
		<section class="page-header page-header-xs parallax parallax-3" style="background-color:#666666">
			<div class="overlay dark-1"><!-- dark overlay [1 to 9 opacity] --></div>
			<div class="container">
				<h1>Save as file</h1>
			</div>
		</section>
		<!-- /PAGE HEADER -->

		<!-----------------------------------contents section-------------------------------------------------->
		<!-- INFO BAR -->
		<section>
			<div class="container">
				<h2>Please click "Download" button.</h2>
				<div class="col-md-12 col-sm-12 text-right"><br />
					<a href='<?=$xcelFileName?>'><button type='button' class='btn btn-primary'> Download </button></a>
				</div>
			</div>
		</section>
		<!-- /INFO BAR -->

		<div class="col-md-12 col-sm-12 text-right"><br />
			<a href='Javascript:self.close();'><button type='button' class='btn btn-xs btn-default'> Close </button></a>
		</div>
	</div>
	<!-- /wrapper -->

	<?php
	include "subHeader/footerJS.php";
	DBCLOSE_end($cntDB);
	?>
<br /><br />
</body>
</html>