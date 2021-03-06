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

	$idx = clearXSS(XSSfilter($_GET["idx"]));
	$sql = "SELECT
				dep_order.*,
				customer.company AS company
			FROM 
				t_order AS dep_order
				LEFT JOIN t_customer AS customer ON customer.dep_customer_id = dep_order.dep_customer_id 
			WHERE 1
				AND dep_order.idx = $idx
	";
	$rowOrder = x_FETCH($sql, $cntDB);
?>
</head>

<body class="smoothscroll enable-animation">
	<!-- wrapper -->
	<div id="wrapper">

		<!-----------------------------------contents section-------------------------------------------------->
		<div class="container">
			<div class="col-lg-12 col-md-12 col-sm-12 text-center">
				<div class="box-title margin-top-30">
					<h2 class="size-20">View Details</h2>
				</div>

				<div class="row">
					<div class="table-responsive">
						<table class="table table-bordered table-striped text-center">
							<tr>
								<td class="info">Ship To</td>
								<td><?=$rowOrder[ship_to]?></td>
							</tr>
							<tr>
								<td class="info">Transaction ID</td>
								<td><?=$rowOrder[transaction_id]?></td>
							</tr>
							<tr>
								<td class="info">Order Number</td>
								<td><?=$rowOrder[order_number]?></td>
							</tr>
							<tr>
								<td class="info">Order Date</td>
								<td><?=$rowOrder[order_date]?></td>
							</tr>
							<tr>
								<td class="info">DEP Customer ID</td>
								<td><?=$rowOrder[dep_customer_id]?> (<?=$rowOrder[company]?>)</td>
							</tr>
							<tr>
								<td class="info">Type</td>
								<td><?=$rowOrder[order_type]?></td>
							</tr>
							<tr>
								<td class="info">Deliveries</td>
								<td>
									<a href="order_detail_delivery.php?idx=<?=$rowOrder[idx]?>">
									<button type="button" class="btn btn-sm btn-default">View</button>
									</a>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row text-center">
					<form name='orderForm' id='orderForm' action="#" method="post" enctype="multipart/form-data">
						<a href="Javascript:close_window()">
						<button type="button" class="btn btn-sm btn-default margin-right-10">Cancel</button>
						</a>

						<input type='hidden' name='enroll_cnt' id='enroll_cnt' value='1'>
						<input type='hidden' name='enroll_0' id='enroll_0' value='<?=$idx?>'>
						<input type='hidden' name='void_ok' id='void_ok' value='1'>
						<button type="button" class="btn btn-sm btn-primary on-action-enroll start-enroll" id="enroll_btn">Void</button>
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
        function close_window() {
			self.close();
		}

		function btn_ui_set_enabled(btn){
			return btn.removeClass("btn-default").addClass("btn-primary");
		}

		function btn_ui_set_disabled(btn){
			return btn.removeClass("btn-primary").addClass("btn-default");
		}

		$(document).ready(function(){
			bind_buttons();
		});

		function bind_buttons(){
			$(".on-action-enroll").on('click', function(e) {
				if (confirm("Order Type : " + "<?=$rowOrder[order_type]?>" + "\n\nDo you really want to run Void?\nThe full order would be canceled!!")) {
					var enroll_btn = document.getElementById('enroll_btn');
					enroll_btn.disabled = true;

					e.preventDefault();
					btn_ui_set_disabled($(".start-enroll"));

					var params = new FormData();
					var form_data = $("#orderForm").serializeArray();
					$.each(form_data, function (key, input) {
						params.append(input.name, input.value);
					});
					//console.log(form_data);

					$.ajax({
						url:'api_process.php',
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
							enroll_btn.disabled = false;
							btn_ui_set_enabled($(".start-enroll"));
						},
						error:function(request,status,error){
							alert("status : " + status);
						}
					});
				}
			});
		}	
	</script>
</body>
</html>
