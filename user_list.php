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
		<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="box-title margin-top-30">
					<h2 class="size-20">DUM (DEP User Management)</h2>
				</div>

				<a href="user_new.php" onclick="popupOpen(event, this.href, 'permission', 800, 400)">
				<button type="button" class="btn btn-sm btn-primary">New User</button>
				</a><br /><br />

				<div class="table-responsive">
					<table class="table table-bordered table-striped text-center">
						<thead>
							<tr class="info">
								<th class="text-center">ID</th>
								<th class="text-center">Name</th>
								<th class="text-center">OR</th>
								<th class="text-center">RE</th>
								<th class="text-center">OV</th>
								<th class="text-center">VO</th>
								<th class="text-center">LU</th>
								<th class="text-center">DCM</th>
								<th class="text-center">DUM</th>
								<th class="text-center">ADMIN</th>
								<th class="text-center">Permission</th>
								<th class="text-center">Account</th>
							</tr>
						</thead>
						<tbody>

						<?php
						$sql = "SELECT * FROM t_user WHERE is_valid=1 ORDER BY user_name ASC";
						$rsUser = x_SQL($sql, $cntDB);
						while ( $rowUser = x_FETCH2($rsUser) ) {
							$menuOR = "";
							$menuRE = "";
							$menuOV = "";
							$menuVO = "";
							$menuLU = "";
							$menuDCM = "";
							$menuDUM = "";
							$menuADMIN = "";

							if ($rowUser[_ADMIN]) {
								$menuADMIN = "O";
								$menuOR = "O";
								$menuRE = "O";
								$menuOV = "O";
								$menuVO = "O";
								$menuLU = "O";
								$menuDCM = "O";
								$menuDUM = "O";
								$menuADMIN = "O";
							}
							else {
								if ($rowUser[_OR])	$menuOR = "O";
								if ($rowUser[_RE])	$menuRE = "O";
								if ($rowUser[_OV])	$menuOV = "O";
								if ($rowUser[_VO])	$menuVO = "O";
								if ($rowUser[_LU])	$menuLU = "O";
								if ($rowUser[_DCM])	$menuDCM = "O";
								if ($rowUser[_DUM])	$menuDUM = "O";
							}
						
							?>
							<tr bgcolor='#ffffff'>
								<td><?=$rowUser[user_id]?></td>
								<td><?=$rowUser[user_name]?></td>
								<td><?=$menuOR?></td>
								<td><?=$menuRE?></td>
								<td><?=$menuOV?></td>
								<td><?=$menuVO?></td>
								<td><?=$menuLU?></td>
								<td><?=$menuDCM?></td>
								<td><?=$menuDUM?></td>
								<td><?=$menuADMIN?></td>
								<td>
									<a href="user_edit_permission.php?idx=<?=$rowUser[idx]?>" onclick="popupOpen(event, this.href, 'permission', 800, 400)">
									<button type="button" class="btn btn-sm btn-default">Edit</button>
									</a>
								</td>
								<td>
									<a href="user_edit_account.php?idx=<?=$rowUser[idx]?>" onclick="popupOpen(event, this.href, 'account', 800, 400)">
									<button type="button" class="btn btn-sm btn-default">Edit</button>
									</a>
								</td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table><br /><br />
				</div>
			</div>
		</div>
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
