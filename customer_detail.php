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

	$action = clearXSS(XSSfilter($_GET["action"]));

	if ($action=="view" || $action=="edit") {
		$idx = clearXSS(XSSfilter($_GET["idx"]));

		$sql = "SELECT * FROM t_customer WHERE idx=$idx";
		$rowCustomer = x_FETCH($sql, $cntDB);

		if ($action == "view")
			$readonlyStr = "readonly";
	}
?>
</head>

<body class="smoothscroll enable-animation">
	<!-- wrapper -->
	<div id="wrapper">

		<!-----------------------------------contents section-------------------------------------------------->
		<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12 text-center">
				<div class="box-title margin-top-30">
					<h2 class="size-20">New Customer</h2>
				</div>

				<form name='newCustomerForm' id='newCustomerForm' class="nomargin sky-form" action="" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label>DEP Customer ID *</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-user'></i>
								<input type='text' class='form-control' required name='dep_customer_id' id='dep_customer_id' <?=$readonlyStr?> value='<?=$rowCustomer[dep_customer_id]?>' style="height:45px;">
							</label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label>SKN Customer ID *</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-user'></i>
								<input type='text' class='form-control' required name='skn_customer_id' id='skn_customer_id' <?=$readonlyStr?> value='<?=$rowCustomer[skn_customer_id]?>' style="height:45px;">
							</label>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label>Company *</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-home'></i>
								<input type='text' class='form-control' required name='company' id='company' <?=$readonlyStr?> value='<?=$rowCustomer[company]?>' style="height:45px;">
							</label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label>Phone number</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-phone'></i>
								<input type='text' class='form-control' name='phone' id='phone' <?=$readonlyStr?> value='<?=$rowCustomer[phone]?>' style="height:45px;">
							</label>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label>Email</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-envelope'></i>
								<input type='email' class='form-control' name='email' id='email' <?=$readonlyStr?> value='<?=$rowCustomer[email]?>' style="height:45px;">
							</label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<label>Charge</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-info'></i>
								<input type='text' class='form-control' name='charge' id='charge' <?=$readonlyStr?> value='<?=$rowCustomer[charge]?>' style="height:45px;">
							</label>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<label>Note</label>
							<label class='input margin-bottom-6'>
								<i class='ico-append fa fa-file'></i>
								<input type='text' class='form-control' name='note' id='note' <?=$readonlyStr?> value='<?=$rowCustomer[note]?>' style="height:45px;">
							</label>
						</div>
					</div>
					<div class="row text-center">
						<?php
						if ($action == "view") {
							?>
							<a href="Javascript:cancel_act_customer()">
							<button type="button" class="btn btn-sm btn-primary">Close</button>
							</a>
							<?php
						}
						else {
							?>
							<a href="Javascript:cancel_act_customer()">
							<button type="button" class="btn btn-sm btn-default">Cancel</button>
							</a>
							<?php
							if ($action == "edit") {
								?>
								<a href="Javascript:act_customer('edit')">
								<button type="button" class="btn btn-sm btn-primary">OK</button>
								</a>
								<?php
							}
							else {
								?>
								<a href="Javascript:act_customer('add')">
								<button type="button" class="btn btn-sm btn-primary">OK</button>
								</a>
								<?php
							}
						}
						?>
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
        function cancel_act_customer() {
			self.close();
		}

		function act_customer(action) {
			var dep_customer_id = $("#dep_customer_id").val();
			var skn_customer_id = $("#skn_customer_id").val();
			var company = $("#company").val();
			
			if ( !dep_customer_id || !skn_customer_id || !company )  {
				alert("Please input all of 3 essential items.");
				return;
			}

			var params = new FormData();

			//Form data
			var form_data = $("#newCustomerForm").serializeArray();
			$.each(form_data, function (key, input) {
				params.append(input.name, input.value);
			});
			params.append("action", action);
			console.log(form_data);

			$.ajax({
				url:'customer_process.php?idx=<?=$idx?>',
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
