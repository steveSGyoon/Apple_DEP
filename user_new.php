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

		<!-----------------------------------contents section-------------------------------------------------->
		<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12 text-center">
				<div class="box-title margin-top-30">
					<h2 class="size-20">New User</h2>
				</div>

				<form name='newUserForm' id='newUserForm' class="nomargin sky-form" action="" method="post" enctype="multipart/form-data">
					<input type='hidden' name='action' id='action' value='new'>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label>ID *</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-user'></i>
								<input type='text' class='form-control' required size=30 name='user_id' id='user_id' style="height:45px;">
							</label>
							<label>Name *</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-user'></i>
								<input type='text' class='form-control' required size=30 name='user_name' id='user_name' style="height:45px;">
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
						<a href="Javascript:cancel_permission()">
						<button type="button" class="btn btn-sm btn-default">Cancel</button>
						</a>

						<a href="Javascript:add_account()">
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

		function add_account() {
			var user_id = $("#user_id").val();
			var userName = $("#user_name").val();
			var password1 = $("#user_password").val();
			var password2 = $("#user_passwordO").val();
			
			if ( !user_id || !userName || !password1 || !password2 )  {
				alert("Please input all items.");
				return;
			}

			if (password1.length < 8) {
				alert("비밀번호는 영문/숫자/특수문자 혼합 8자리 이상이어야 합니다");
				return;
			}

			if (!password1.match(/[a-zA-Z0-9]*[^a-zA-Z0-9\n]+[a-zA-Z0-9]*$/)) {
				alert("비밀번호는 영문/숫자/특수문자 혼합 8자리 이상이어야 합니다");
				return;
			}

			if ( password1 != password2 ) {
				alert("Please confirm your password.");
				return;
			}
			else {
				var params = new FormData();

				//Form data
				var form_data = $("#newUserForm").serializeArray();
				$.each(form_data, function (key, input) {
					params.append(input.name, input.value);
				});
				//console.log(form_data);

				$.ajax({
					url:'user_process.php',
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
								document.location.href = "user_edit_permission.php?idx=" + reponse['idx'];
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
