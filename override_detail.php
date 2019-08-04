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

	$sql = "SELECT
				dep_order.*,
				customer.company AS company
			FROM 
				t_order AS dep_order
				LEFT JOIN t_customer AS customer ON customer.dep_customer_id = dep_order.dep_customer_id 
			WHERE 1
				AND dep_order.order_number = '$rowOrder[order_number]'
				AND dep_order.order_type='OR'
	";
	$rowBefore = x_FETCH($sql, $cntDB);
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
								<td class="info">Item</td>
								<td>Before</td>
								<td>After</td>
							</tr>
							<tr>
								<td class="info">DEP Customer ID</td>
								<td><?=$rowBefore[dep_customer_id]?></td>
								<td><?=$rowOrder[dep_customer_id]?></td>
							</tr>
							<tr>
								<td class="info">Order Date</td>
								<td><?=$rowBefore[order_date]?></td>
								<td><?=$rowOrder[order_date]?></td>
							</tr>
							<tr>
								<td class="info">Company</td>
								<td><?=$rowBefore[company]?></td>
								<td><?=$rowOrder[company]?></td>
							</tr>
							<tr>
								<td class="info">Deliveries</td>
								<td>
									<a href="order_detail_delivery.php?idx=<?=$rowBefore[idx]?>&ov=Before">
									<button type="button" class="btn btn-sm btn-default">View</button>
									</a>
								</td>
								<td>
									<a href="order_detail_delivery.php?idx=<?=$rowOrder[idx]?>&ov=After">
									<button type="button" class="btn btn-sm btn-default">View</button>
									</a>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row text-center">
					<a href="Javascript:close_window()">
					<button type="button" class="btn btn-sm btn-default margin-right-10">Cancel</button>
					</a>

					<a href="Javascript:do_order_action()">
					<button type="button" class="btn btn-sm btn-primary">Override</button>
					</a>
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

		function do_order_action() {
			var params = new FormData();

			//Form data
			var form_data = $("#orderForm").serializeArray();
			$.each(form_data, function (key, input) {
				params.append(input.name, input.value);
			});
			//console.log(form_data);

			$.ajax({
				url:'order_process.php',
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
							window.location.reload();
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
