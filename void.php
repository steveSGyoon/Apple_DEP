<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
<?php
	include "header/header.php";
	include "header/functionsY.php";
	include "header/_checkLogin.php";
	include "header/_checkMenuVO.php";

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
					<h2 class="size-20">Void</h2>
				</div>

				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 text-center">

						<form name='searchForm2' id='searchForm2' action="void_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='search_type' id='search_type' value="dep_customer_id">
							<input type='text' size=50 required name='search_val' id='search_val' placeholder="DEP Customer ID" style="height:35px;">
							<button type='submit' class="btn btn-warning btn-sm size-15">Search</button>
						</form>

						<form name='searchForm3' id='searchForm3' action="void_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='search_type' id='search_type' value="device_id">
							<input type='text' size=50 required name='search_val' id='search_val' placeholder="Device ID" style="height:35px;">
							<button type='submit' class="btn btn-warning btn-sm size-15">Search</button>
						</form>

						<form name='searchForm1' id='searchForm1' action="void_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='search_type' id='search_type' value="order_number">
							<input type='text' size=50 required name='search_val' id='search_val' placeholder="Order Number" style="height:35px;">
							<button type='submit' class="btn btn-warning btn-sm size-15">Search</button>
						</form>

					</div>
				</div>
			</div>
		</div><br /><br />
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
