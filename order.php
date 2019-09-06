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
					<h2 class="size-20">Order</h2>
				</div>

				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<a href="#">
						<button type="button" class="btn btn-sm btn-primary">Reload</button>
						</a>
					</div>
				</div><br />
				<form name='orderForm' id='orderForm' action="#" method="post" enctype="multipart/form-data">
					<div class="table-responsive">
						<table class="table table-bordered table-striped text-center">
							<thead>
								<tr class="info">
									<th class="text-center">Selection</th>
									<th class="text-center">DEP Customer ID</th>
									<th class="text-center">SKN Customer ID</th>
									<th class="text-center">Company</th>
									<th class="text-center">Order No.</th>
									<th class="text-center">Detail</th>
								</tr>
							</thead>
							<tbody>

							<?php
							$enroll_idx = 0;
							$sql = "SELECT
										dep_order.*,
										customer.skn_customer_id AS skn_customer_id,
										customer.company AS company
									FROM 
										t_order AS dep_order
										LEFT JOIN t_customer AS customer ON customer.dep_customer_id = dep_order.dep_customer_id 
									WHERE 1
										AND dep_order.order_type = 'OR'
										AND dep_order.status = 0
										AND dep_order.is_void = 0
										AND dep_order.is_valid = 1
									ORDER BY 
										dep_order.order_date DESC
							";
							$rs = x_SQL($sql, $cntDB);
							while ( $row = x_FETCH2($rs) ) {
								$enroll_name = "enroll_" . $enroll_idx;

								$error_view = "";
								$sql = "SELECT count(*) FROM t_api_check_result WHERE t_order_idx = $row[idx] AND errorCode != '' AND errorCode is not null";
								//echo $sql . "<br>";
								$rowError = x_FETCH($sql, $cntDB);
								if ($rowError[0] != 0) {
									$error_view = "<a href='error_view.php?idx=$row[idx]' onclick='popupOpen(event, this.href, \"errorView\", 500, 450)'>";
									$error_view .= "<button type='button' class='btn btn-xs btn-danger'>error</button>";
									$error_view .= "</a>";
								}

								?>
								<tr bgcolor='#ffffff'>
									<td>
										<input type='checkbox' name='<?=$enroll_name?>' id='<?=$enroll_name?>' value='<?=$row[idx]?>'>
									</td>
									<td><?=$row[dep_customer_id]?></td>
									<td><?=$row[skn_customer_id]?></td>
									<td><?=$row[company]?></td>
									<td><?=$row[order_number]?></td>
									<td>
										<a href="order_detail.php?idx=<?=$row[idx]?>" onclick="popupOpen(event, this.href, 'orderView', 500, 450)">
										<button type="button" class="btn btn-sm btn-default">View</button>
										</a>
										<?=$error_view?>
									</td>
								</tr>
								<?php
								$enroll_idx++;
							}
							?>
							</tbody>
						</table><br />
						<div class="col-lg-12 col-md-12 col-sm-12 text-center">
							<input type='hidden' name='enroll_cnt' id='enroll_cnt' value='<?=$enroll_idx?>'>

                            <button type="button" class="btn btn-sm btn-primary on-action-enroll start-enroll" id="enroll_btn">Enroll Devices</button>
						</div>
					</div>
				</form>
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

	<script type="text/javascript">
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
				var is_checked = false;
				var enroll_cnt = document.getElementById('enroll_cnt');
				for (id_enroll=0; id_enroll<enroll_cnt.value; id_enroll++) {
					enroll_name = "enroll_" + id_enroll;
					var orders = document.getElementById(enroll_name);
					if ( orders.checked ) {
						is_checked = true;
						break;
					}
				}

				if (is_checked) {
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
									window.location.reload();
									break;
								case "success":
									alert(reponse['result_msg']);
									window.location.reload();
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
				else {
					alert("Please check at least one order.")
				}
			});
		}
    </script>
</body>
</html>
