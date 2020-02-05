<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
<?php
	include "header/header.php";
	include "header/functionsY.php";

	if ($_isLogin)
		header('Location: /indexMain.php');

	$cntDB = DBCONNECT_start();
?>
</head>

<body class="smoothscroll enable-animation">
	<!-- wrapper -->
	<div id="wrapper">
		<!-----------------------------------contents section-------------------------------------------------->
		<section>
			<div class="container">
				<div class='row'>					
					<!-- LOGIN -->
					<div class="col-md-2 col-sm-2"></div>
					<div class="col-md-8 col-sm-8 text-center">
						<div class="box-static box-border-top padding-30">
							<div class="box-title margin-bottom-30">
								<h2 class="size-30">SKN DEP System<br /><br />
							</div>

							<form name='memberLoginForm' class="nomargin sky-form" method="post" action="mLoginPS.php" autocomplete="off">
								<div class="row">
									<div class="col-md-12">
										<label>이메일 주소 *</label>
										<label class='input margin-bottom-6'>
											<i class='ico-append fa fa-user'></i>
											<input type='text' class='form-control' required size=30 name='user_id' id='user_id' style="height:45px;">
										</label>
									</div>
									<div class="col-md-12">
										<label>비밀번호 *</label>
										<label class='input margin-bottom-6'>
											<i class='ico-append fa fa-lock'></i>
											<input type='password' class='form-control' required size=30 name='user_password' id='user_password' style="height:45px;">
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 text-right">
										<button type='submit' class="btn btn-warning btn-lg size-15"><i class="fa fa-mail-forward"></i> 로그인</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="col-md-2 col-sm-2"></div>
					<!-- /LOGIN -->
				</div>
			</div>
		</section><br />
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
	include "header/footerJS.php";
	DBCLOSE_end($cntDB);
	?>
</body>
</html>
