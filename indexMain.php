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
		<section>
			<div class="container">
				<div class='row'>					
					<div class="box-title margin-bottom-50 text-center">
						<h2 class="size-35">Please select action from top menu.
					</div>
				</div>
			</div>
		</section>
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
