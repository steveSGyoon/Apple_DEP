<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
<?php
	include "header/header.php";
	include "header/functionsY.php";
	include "header/_checkAdmin.php";

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
					<form name='xlsForm' id='xlsForm' class="nomargin sky-form" action="_xUp1_ps.php" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Excel File *</label>
									<input class='custom-file-upload' type='file' name='excelFile' id='excelFile' data-btn-text='Select a File' />
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Upload</button>
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
