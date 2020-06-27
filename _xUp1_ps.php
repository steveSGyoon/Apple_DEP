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

	if ( !($_FILES["excelFile"]["name"]) ) {
		?>
		<script>
			alert ("No excel file.");
			document.location.href	= "Javascript:history.go(-1)";
		</script>
		<?php
	}
	else {
		$tmp_name = $_FILES['excelFile']['tmp_name'];
		$temp_var= explode(".", rawurldecode($_FILES['excelFile']['name']));
		$excelFileName = "_upLoad/" . date('Y-m-d') . "_" . str_replace("\0", "", time().'-'.rand(0,100).'.'.$temp_var[1]);
		UpFileExecute($excelFileName, $_FILES["excelFile"]);
	}

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

	if (!$dep_userno)
		$dep_userno = 0;
	$order_id = "o" . date("mdHis") . "_" . sprintf("%03d", $dep_userno);
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
					<form name='xlsForm' id='xlsForm' class="nomargin sky-form" action="_xUp2_ps.php" method="post" enctype="multipart/form-data">

						<table class="table table-hover table-bordered">
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
							        echo "<tr class='size-11'>";
							        for ($col = 0; $col < $highestColumnIndex; ++ $col) {
							            $cell = $worksheet->getCellByColumnAndRow($col, $row);
							            $val = $cell->getValue();

							            if ($row == 1)
							            	echo '<td style="background:black; color:#fff;">' . $val . '</td>';
							            else
							                echo '<td style="background:white;">' . $val . '</td>';

							        }
							        echo '</tr>';
							    }
							}
							?>
						</table>
					
						<div class="row">
							<div class="col-md-12 text-right">
								<input type='hidden' name='excelFileName' id='excelFileName' value='<?=$excelFileName?>'>
								<input type='hidden' name='order_id' id='order_id' value='<?=$order_id?>'>
								<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Upload Confirm</button>
							</div>
						</div>
					</form>
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
