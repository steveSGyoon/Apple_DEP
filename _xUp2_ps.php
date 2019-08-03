<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
<?php
	include "header/header.php";
	include "header/functionsY.php";
	include "excelUpload/PHPExcel.php";
	include "excelUpload/PHPExcel/IOFactory.php";
	include "header/_checkAdmin.php";

	$excelFileName = $_POST["excelFileName"];

	try {
		$objPHPExcel = PHPExcel_IOFactory::load($excelFileName);
		$sheetsCount = $objPHPExcel->getSheetCount();
	} catch(Exception $e) {
		?>
		<script>
			alert ("Error in the middle of excel file reading.");
			document.location.href	= "Javascript:history.go(-1)";
		</script>
		<?php
		//die('Error loading file "'.pathinfo($excelFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}

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
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="box-title margin-top-30">
					<h2 class="size-20">Excel Upload</h2>
				</div>

				<div class="box-static box-border-top padding-30">
					<?php
					for($i = 0; $i < $sheetsCount; $i++)
					{
						$objPHPExcel->setActiveSheetIndex($i);
						$worksheet = $objPHPExcel->getActiveSheet();
						//$worksheet = $objPHPExcel->getActiveSheet();

						$worksheetTitle     = $worksheet->getTitle();
						$highestRow         = $worksheet->getHighestRow(); // e.g. 10
						$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
						$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
						$nrColumns = ord($highestColumn) - 64;
						//echo "highestColumnIndex=" . $highestColumnIndex . "<br>";

						for ($row = 1; $row <= $highestRow; ++ $row) {
							for ($col = 0; $col < $highestColumnIndex; ++ $col) {
								$cell = $worksheet->getCellByColumnAndRow($col, $row);
								$val = $cell->getValue();
								$excelValue[$row][$col] = $val;
							}
						}
						//echo "highestRow=" . $highestRow . "<br>";
						//echo "highestColumnIndex=" . $highestColumnIndex . "<br>";

						$HPindex = 0;
						for ($row=2; $row<=$highestRow; ++ $row) {
							for ($col = 0; $col < $highestColumnIndex; ++ $col) {
								if ($col == 0)
									$ship_to[$HPindex] = $excelValue[$row][$col];
								else if ($col == 1)
									$dep_reseller_id[$HPindex] = $excelValue[$row][$col];
								else if ($col == 2)
									$order_number[$HPindex] = $excelValue[$row][$col];
								else if ($col == 3)
									$order_date[$HPindex] = $excelValue[$row][$col];
								else if ($col == 4)
									$order_type[$HPindex] = $excelValue[$row][$col];
								else if ($col == 5)
									$dep_customer_id[$HPindex] = $excelValue[$row][$col];
								else if ($col == 6)
									$po_number[$HPindex] = $excelValue[$row][$col];
								else if ($col == 7)		// t_order_device
									$delivery_number[$HPindex] = $excelValue[$row][$col];	// O
								else if ($col == 8)
									$ship_date[$HPindex] = $excelValue[$row][$col];
								else if ($col == 9)		// t_order_device
									$device_id[$HPindex] = $excelValue[$row][$col];
								else if ($col == 10)	// t_order_device
									$asset_tag[$HPindex] = $excelValue[$row][$col];
							}
							$HPindex++;
						}
						//echo "HPindex=" . $HPindex . "<br>";
					}

					// 구성된 어레이로 디비 데이터 구성
					for ($iy=0; $iy<$HPindex; $iy++) {
						// check point 1 = customer id exist
						$chkSQL = "SELECT count(*) AS customer_cnt FROM t_customer WHERE dep_customer_id = '$dep_customer_id[$iy]'";
						$rowCheck1 = x_FETCH($chkSQL, $cntDB);
						if ($rowCheck1[customer_cnt] == 0) {
							echo "<font color='red'>[$iy] Error !! - $dep_customer_id[$iy] : no dep_customer_id. </font><br />";
						}
						else {
							$chkSQL = "SELECT
											count(*) AS row_cnt 
										FROM 
											t_order 
										WHERE 1
											AND order_number = '$order_number[$iy]'
											AND order_type = '$order_type[$iy]'
											AND dep_customer_id = '$dep_customer_id[$iy]'
											AND dep_reseller_id = '$dep_reseller_id[$iy]'
											AND ship_to = '$ship_to[$iy]'
											AND po_number = '$po_number[$iy]'
											AND order_date = '$order_date[$iy]'
											AND ship_date = '$ship_date[$iy]'
											AND delivery_number = '$delivery_number[$iy]'
											AND device_id = '$device_id[$iy]'
											AND asset_tag = '$asset_tag[$iy]'
							";
							//echo $chkSQL . "<br>";
							$rowCheck2 = x_FETCH($chkSQL, $cntDB);
							if ($rowCheck2[row_cnt] != 0) {
								echo "<font color='red'>[$iy] Error !! - $dep_customer_id[$iy] : this row ia already exist. </font><br />";
							}
							else {
								if ( $order_type[$iy]!="OR" && $order_type[$iy]!="RE" && $order_type[$iy]!="OV" ) {
									echo "<font color='red'>[$iy] Error !! - $order_type[$iy] : unknown prder_type. </font><br />";
								}
								else {
									$sql = "INSERT INTO 
												t_order
												(order_number, order_type, dep_customer_id, dep_reseller_id, 
												ship_to, po_number, order_date, ship_date,
												delivery_number, device_id, asset_tag) 
											VALUES 
												('$order_number[$iy]', '$order_type[$iy]', '$dep_customer_id[$iy]', '$dep_reseller_id[$iy]', 
												'$ship_to[$iy]', '$po_number[$iy]', '$order_date[$iy]', '$ship_date[$iy]', 
												'$delivery_number[$iy]', '$device_id[$iy]', '$asset_tag[$iy]')
									"; 
									$rs = x_SQL($sql, $cntDB);
									$order_idx = mysqli_insert_id($cntDB);
	
									if ($order_idx)
										echo "[$iy] Success !! - $dep_customer_id[$iy] <br />";
									else
										echo "<font color='red'>[$iy] order_device Insert Error !! - $dep_customer_id[$iy] </font><br />";
								}
							}
						}
					}

					?>
				</div>
			</div>
		</div><br />
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
