<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
<?php
	include "header/header.php";
	include "header/functionsY.php";
	include "header/_checkLogin.php";
	include "header/_checkMenuDUM.php";

	$cntDB = DBCONNECT_start();

	$idx = clearXSS(XSSfilter($_GET["idx"]));
	$sql = "SELECT * FROM t_user WHERE idx=$idx";
	$rowUser = x_FETCH($sql, $cntDB);
?>
</head>

<body class="smoothscroll enable-animation">
	<!-- wrapper -->
	<div id="wrapper">

		<!-----------------------------------contents section-------------------------------------------------->
		<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12 text-center">
				<div class="box-title margin-top-30">
					<h2 class="size-20">Permission Control<br/><font color='blue'><?=$rowUser[user_id]?></font></h2>
				</div>

				<div class="table-responsive">
					<form name='permissionForm' id='permissionForm' class="nomargin" action="" method="post" enctype="multipart/form-data">
						<input type='hidden' name='action' id='action' value='permission'>
						<table class="table table-bordered table-striped text-center">
							<thead>
								<tr class="info">
									<th class="text-center">OR</th>
									<th class="text-center">RE</th>
									<th class="text-center">OV</th>
									<th class="text-center">VO</th>
									<th class="text-center">LU</th>
									<th class="text-center">DCM</th>
									<th class="text-center">DUM</th>
									<th class="text-center">ADMIN</th>
								</tr>
							</thead>
							<tbody>

								<?php
								$menuOR = "";
								$menuRE = "";
								$menuOV = "";
								$menuVO = "";
								$menuLU = "";
								$menuDCM = "";
								$menuDUM = "";
								$menuADMIN = "";

								if ($rowUser[_ADMIN]) {
									$menuADMIN = "checked";
									$menuOR = "checked";
									$menuRE = "checked";
									$menuOV = "checked";
									$menuVO = "checked";
									$menuLU = "checked";
									$menuDCM = "checked";
									$menuDUM = "checked";
									$menuADMIN = "checked";
								}
								else {
									if ($rowUser[_OR])	$menuOR = "checked";
									if ($rowUser[_RE])	$menuRE = "checked";
									if ($rowUser[_OV])	$menuOV = "checked";
									if ($rowUser[_VO])	$menuVO = "checked";
									if ($rowUser[_LU])	$menuLU = "checked";
									if ($rowUser[_DCM])	$menuDCM = "checked";
									if ($rowUser[_DUM])	$menuDUM = "checked";
								}
								?>

								<tr bgcolor='#ffffff'>
									<td><input type='checkbox' name='menuOR' id='menuOR' <?=$menuOR?> value='1'></td>
									<td><input type='checkbox' name='menuRE' id='menuRE' <?=$menuRE?> value='1'></td>
									<td><input type='checkbox' name='menuOV' id='menuOV' <?=$menuOV?> value='1'></td>
									<td><input type='checkbox' name='menuVO' id='menuVO' <?=$menuVO?> value='1'></td>
									<td><input type='checkbox' name='menuLU' id='menuLU' <?=$menuLU?> value='1'></td>
									<td><input type='checkbox' name='menuDCM' id='menuDCM' <?=$menuDCM?> value='1'></td>
									<td><input type='checkbox' name='menuDUM' id='menuDUM' <?=$menuDUM?> value='1'></td>
									<td><input type='checkbox' name='menuADMIN' id='menuADMIN' <?=$menuADMIN?> value='1'></td>
								</tr>
							</tbody>
						</table>

						<a href="Javascript:cancel_permission()" class="margin-right-20">
						<button type="button" class="btn btn-sm btn-default">Cancel</button>
						</a>

						<a href="Javascript:update_permission()">
						<button type="button" class="btn btn-sm btn-primary">OK</button>
						</a>
					</form>
				</div>
			</div>
		</div>
		<!----------------------------------- end of contents-------------------------------------------------->
	</div>
	<!-- /wrapper -->

	<?php
	include "subHeader/footerJS.php";
	DBCLOSE_end($cntDB);
	?>

	<script type="text/javascript">
        function cancel_permission() {
			self.close();
		}

		function update_permission() {
			var params = new FormData();

			//Form data
			var form_data = $("#permissionForm").serializeArray();
			$.each(form_data, function (key, input) {
				params.append(input.name, input.value);
			});
			//console.log(form_data);

			$.ajax({
				url:'user_process.php?idx=<?=$idx?>',
				type:'POST',
				contentType: false,
				cache: false,             
				processData:false, 
				data:params,

				success:function(reponse){
					switch (reponse['result'])
					{
						case "fail":
							alert(reponse['error_msg']);
							break;
						case "success":
							alert(reponse['result_msg']);
							opener.location.reload();
							self.close();
							break;
						default:
							alert(reponse['result']);
							break;
					}
				},
				error:function(request,status,error){
					alert("status : " + status);
				}
			});
		}
    </script>
</body>
</html>
