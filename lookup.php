<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
<?php
	include "header/header.php";
	include "header/functionsY.php";
	include "header/_checkLogin.php";
	include "header/_checkMenuLU.php";

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
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-8">
						<div class="box-title margin-top-30">
							<h2 class="size-20">Lookup</h2>
						</div>

						<form name='searchForm1' id='searchForm1' action="lookup_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='search_type' id='search_type' value="order_number">
							<input type='text' size=50 required name='search_val' id='search_val' placeholder="Order Number" style="height:35px;">
							<button type='submit' class="btn btn-warning btn-sm size-15">Search</button>
						</form>

						<form name='searchForm2' id='searchForm2' action="lookup_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='search_type' id='search_type' value="dep_customer_id">
							<input type='text' size=50 required name='search_val' id='search_val' placeholder="DEP Customer ID" style="height:35px;">
							<button type='submit' class="btn btn-warning btn-sm size-15">Search</button>
						</form>

						<form name='searchForm3' id='searchForm3' action="lookup_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='search_type' id='search_type' value="transaction_id">
							<input type='text' size=50 required name='search_val' id='search_val' placeholder="Transaction ID" style="height:35px;">
							<button type='submit' class="btn btn-warning btn-sm size-15">Search</button>
						</form>

						<form name='searchForm4' id='searchForm4' action="lookup_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='search_type' id='search_type' value="order_date">
							<input type='text' size=50 required name='search_val' id='search_val' placeholder="Order Date : 2019-05-26" style="height:35px;">
							<button type='submit' class="btn btn-warning btn-sm size-15">Search</button>
						</form>

						<form name='searchForm5' id='searchForm5' action="lookup_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='search_type' id='search_type' value="delivery_number">
							<input type='text' size=50 required name='search_val' id='search_val' placeholder="Delivery Number" style="height:35px;">
							<button type='submit' class="btn btn-warning btn-sm size-15">Search</button>
						</form>

						<form name='searchForm6' id='searchForm6' action="lookup_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='search_type' id='search_type' value="ship_date">
							<input type='text' size=50 required name='search_val' id='search_val' placeholder="Ship Date : 2019-05-26" style="height:35px;">
							<button type='submit' class="btn btn-warning btn-sm size-15">Search</button>
						</form>

						<form name='searchForm7' id='searchForm7' action="lookup_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='search_type' id='search_type' value="device_id">
							<input type='text' size=50 required name='search_val' id='search_val' placeholder="Device ID" style="height:35px;">
							<button type='submit' class="btn btn-warning btn-sm size-15">Search</button>
						</form>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4">
						<div class="box-title margin-top-30">
							<h2 class="size-20">Show Order Details</h2>
						</div>
						<form name='searchForm1' id='searchForm1' action="lookup_search.php" method="post" enctype="multipart/form-data">
							<input type='hidden' name='apple_apply' id='apple_apply' value="yes">
							<input type='hidden' name='search_type' id='search_type' value="order_number">
							<input type='text' size=30 required name='search_val' id='search_val' placeholder="Order Number" style="height:35px;">
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
