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
					<h2 class="size-20">Change Password<br/><font color='blue'><?=$rowUser[user_id]?></font></h2>
				</div>

				<form name='accouontForm' id='accouontForm' class="nomargin sky-form" action="" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label>Name *</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-user'></i>
								<input type='text' class='form-control' required size=30 name='user_name' id='user_name' style="height:45px;" value='<?=$rowUser[user_name]?>'>
							</label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label>Password *</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-lock'></i>
								<input type='password' class='form-control' required size=30 name='user_password' id='user_password' style="height:45px;">
							</label>

							<label>Re-enter Password *</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-lock'></i>
								<input type='password' class='form-control' required size=30 name='user_passwordO' id='user_passwordO' style="height:45px;">
							</label>
						</div>
					</div>
					<div class="row text-center">
						<a href="Javascript:change_account('delete')">
						<button type="button" class="btn btn-sm btn-warning">Delete</button>
						</a>

						<a href="Javascript:cancel_permission()">
						<button type="button" class="btn btn-sm btn-default">Cancel</button>
						</a>

						<a href="Javascript:change_account('edit')">
						<button type="button" class="btn btn-sm btn-primary">OK</button>
						</a>
					</div>

				</form>
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

		function change_account(action) {
			var userName = $("#user_name").val();
			var password1 = $("#user_password").val();
			var password2 = $("#user_passwordO").val();
			
			if ( action!='delete' && (!userName || !password1 || !password2) )  {
				alert("Please input all items.");
				return;
			}

			if ( action!='delete' && (password1 != password2) ) {
				alert("Please confirm your password.");
				return;
			}
			else {
				var params = new FormData();

				//Form data
				var form_data = $("#accouontForm").serializeArray();
				$.each(form_data, function (key, input) {
					params.append(input.name, input.value);
				});
				params.append("action", action);
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
		}
    </script>
</body>
</html>
