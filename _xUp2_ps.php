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
	$order_id = $_POST["order_id"];

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
									$ApplePartName[$HPindex] = $excelValue[$row][$col];
								else if ($col == 10)		// t_order_device
									$ApplePartDesc[$HPindex] = $excelValue[$row][$col];
								else if ($col == 11)		// t_order_device
									$device_id[$HPindex] = $excelValue[$row][$col];
								else if ($col == 12)	// t_order_device
									$asset_tag[$HPindex] = $excelValue[$row][$col];
							}
							$HPindex++;
						}
						//echo "HPindex=" . $HPindex . "<br>";
					}

					// 구성된 어레이로 디비 데이터 구성
					for ($iy=0; $iy<$HPindex; $iy++) {
						$error_msg = "";

						// check point 1 = customer id exist
						$chkSQL = "SELECT count(*) AS customer_cnt FROM t_customer WHERE dep_customer_id = '$dep_customer_id[$iy]'";
						$rowCheck1 = x_FETCH($chkSQL, $cntDB);
						if ($rowCheck1[customer_cnt] == 0) {
							$error_msg = "<font color='red'>[$iy] Error !! - $dep_customer_id[$iy] : no dep_customer_id. </font><br />";
						}
						else {
							//check point 2 = order type : OR / RE / OV
							if ( $order_type[$iy]!="OR" && $order_type[$iy]!="RE" && $order_type[$iy]!="OV" && $order_type[$iy]!="VD" ) {
								$error_msg = "<font color='red'>[$iy] Error !! - $order_type[$iy] : unknown order_type. </font><br />";
							}
							else {
								if ( $order_type[$iy]=="RE" || $order_type[$iy]=="OV" ) {
									//check point 3 = RE / OV case  -> order_number must exist
									$chkSQL = "SELECT
												count(*) AS order_cnt 
											FROM 
												t_order 
											WHERE 1
												AND order_type = 'OR'
												-- AND is_return = 0
												-- AND is_override = 0
												AND status = 2
												AND order_number = '$order_number[$iy]'
									";
									$rowCheck2 = x_FETCH($chkSQL, $cntDB);
									if ($rowCheck2[order_cnt] == 0) {
										$error_msg = "<font color='red'>[$iy] Error !! - $order_type[$iy] : no existing order number. </font><br />";
									}
									else {
										if ( $order_type[$iy] == "RE" ) {
											//주문번호를 원래주문번호-RE로 수정한다
											//$order_number[$iy] = $order_number[$iy] . "-RE" . date("YmdHis");
											$order_number[$iy] = $order_number[$iy] . "-R" . date("mdHis");
										}
									}
								}
							}

							// check point 2 = order_number exist for RE & OV
							if ($error_msg == "") {
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
								";
								$rowCheck3 = x_FETCH($chkSQL, $cntDB);

								if ($rowCheck3[row_cnt] == 0) {
									$transaction_id = date('Ymd') . "-" . str_replace("\0", "", time().'-'.rand(0,10000));
									$sql = "INSERT INTO 
												t_order
												(order_id, order_number, transaction_id, order_type, dep_customer_id, dep_reseller_id, 
												ship_to, po_number, order_date, ship_date)
											VALUES 
												('$order_id', '$order_number[$iy]', '$transaction_id', '$order_type[$iy]', '$dep_customer_id[$iy]', '$dep_reseller_id[$iy]', 
												'$ship_to[$iy]', '$po_number[$iy]', '$order_date[$iy]', '$ship_date[$iy]')
									"; 
									$rs = x_SQL($sql, $cntDB);
									$order_idx = mysqli_insert_id($cntDB);
								}
								else {
									$chkSQL = "SELECT
													idx,
													order_id
												FROM 
													t_order 
												WHERE 1
													AND order_number = '$order_number[$iy]'
													AND order_type = '$order_type[$iy]'
													AND dep_customer_id = '$dep_customer_id[$iy]'
													AND dep_reseller_id = '$dep_reseller_id[$iy]'
													AND ship_to = '$ship_to[$iy]'
													AND po_number = '$po_number[$iy]'
												ORDER BY
													insert_date DESC
												LIMIT
													0, 1
									";
									$rowCheck4 = x_FETCH($chkSQL, $cntDB);
									$order_idx = $rowCheck4[idx];

									if ( ($order_type[$iy] == "OV") && ($rowCheck4[order_id] != $order_id) ) {
										$sql = "UPDATE t_order SET status=0, is_valid=0, edit_date=now() WHERE idx = $order_idx"; 
										$rs1 = x_SQL($sql, $cntDB0);

										$sql = "UPDATE t_order_device SET is_valid=0 WHERE t_order_idx = $order_idx"; 
										$rs2 = x_SQL($sql, $cntDB0);

										$transaction_id = date('Ymd') . "-" . str_replace("\0", "", time().'-'.rand(0,10000));
										$sql = "INSERT INTO 
													t_order
													(order_id, order_number, transaction_id, order_type, dep_customer_id, dep_reseller_id, 
													ship_to, po_number, order_date, ship_date)
												VALUES 
													('$order_id', '$order_number[$iy]', '$transaction_id', '$order_type[$iy]', '$dep_customer_id[$iy]', '$dep_reseller_id[$iy]', 
													'$ship_to[$iy]', '$po_number[$iy]', '$order_date[$iy]', '$ship_date[$iy]')
										"; 
										$rs = x_SQL($sql, $cntDB);
										$order_idx = mysqli_insert_id($cntDB);
									}
								}

								if ($order_idx) {
									$sql = "INSERT INTO 
												t_order_device
												(t_order_idx, delivery_number, part_name, part_desc, device_id, asset_tag) 
											VALUES 
												($order_idx, '$delivery_number[$iy]', '$ApplePartName[$iy]', '$ApplePartDesc[$iy]', '$device_id[$iy]', '$asset_tag[$iy]')
									"; 
									$rs = x_SQL($sql, $cntDB);
									$error_msg =  "[$iy] Success !! - $dep_customer_id[$iy] <br />";
								}
								else {
									$error_msg =  "<font color='red'>[$iy] order_device Insert Error !! - $dep_customer_id[$iy] </font><br />";
								}
							}
						}
						echo $error_msg;
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
