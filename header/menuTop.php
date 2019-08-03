<?php
	if (!$_isLogin) {
		?>
		<script type="text/javascript">
			document.location.href	= "index.php";
		</script>
		<?php
	}

	$menuOR = "";
	if ($_isOR)
		$menuOR = "<li><a href='list.php?action=OR'>Order</a></li>";

	$menuRE = "";
	if ($_isRE)
		$menuRE = "<li><a href='list.php?action=OR'>Return</a></li>";

	$menuOV = "";
	if ($_isOV)
		$menuOV = "<li><a href='list.php?action=OR'>Override</a></li>";

	$menuVO = "";
	if ($_isVO)
		$menuVO = "<li><a href='list.php?action=OR'>Void</a></li>";

	$menuLU = "";
	if ($_isLU)
		$menuLU = "<li><a href='lookup.php'>Lookup</a></li>";

	$menuDCM = "";
	if ($_isDCM)
		$menuDCM = "<li><a href='customer_list.php'>DCM</a></li>";

	$menuDUM = "";
	if ($_isDUM)
		$menuDUM = "<li><a href='user_list.php'>DUM</a></li>";
?>

	<div id="header" class="sticky clearfix header-md">
		<header id="topNav">
			<div class="container">
				<!-- Mobile Menu Button -->
				<button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
					<i class="fa fa-bars"></i>
				</button>
				<!-- /BUTTONS -->

				<!-- Logo -->
				<a class="logo pull-left" href="indexMain.php">
					<!-- <img src="images/skedumallLogoN.png" border=0 alt="SK Nedushop Logo" title="SK Nedushop Logo" /> -->
				</a>

				<div class="navbar-collapse pull-right nav-main-collapse collapse">
					<nav class="nav-main">
						<ul id="topMain" class="nav nav-pills nav-main">
							<li><a href="indexMain.php">HOME</a></li>
							<?php
							echo $menuOR;
							echo $menuRE;
							echo $menuOV;
							echo $menuVO;
							echo $menuLU;
							echo $menuDCM;
							echo $menuDUM;

							if ($_isLogin)
								echo "<li><a href='mLogOut.php'><i class='glyphicon glyphicon-log-out'></i> 로그아웃</a></li>";
							else
								echo "<li><a href='mLogOut.php'><i class='glyphicon glyphicon-log-out'></i> 로그인</a></li>";
							?>
						</ul>
					</nav>
				</div>

			</div>
		</header>
	</div>
