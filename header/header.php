<?php
	$assetFolder = "assets";
?>
	<meta charset="utf-8" />
    <title>Apple DEP</title>
	<meta name="keywords" content="Apple, DEP" />
	<meta name="description" content="Apple DEP" />
	<meta property="og:type" content="website">
	<meta property="og:description" content="Apple DEP">

	<!-- mobile settings -->
	<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

	<!-- WEB FONTS : use %7C instead of | (pipe) -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400%7CRaleway:300,400,500,600,700%7CLato:300,400,400italic,600,700" rel="stylesheet" type="text/css" />

	<!-- CORE CSS -->
	<link href="<?=$assetFolder?>/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

	<!-- REVOLUTION SLIDER -->
	<link href="<?=$assetFolder?>/plugins/slider.revolution/css/extralayers.css" rel="stylesheet" type="text/css" />
	<link href="<?=$assetFolder?>/plugins/slider.revolution/css/settings.css" rel="stylesheet" type="text/css" />

	<!-- THEME CSS -->
	<link href="<?=$assetFolder?>/css/essentials.css" rel="stylesheet" type="text/css" />
	<link href="<?=$assetFolder?>/css/layout.css" rel="stylesheet" type="text/css" />

	<!-- PAGE LEVEL SCRIPTS -->
	<link href="<?=$assetFolder?>/css/header-1.css" rel="stylesheet" type="text/css" />
	<link href="<?=$assetFolder?>/css/layout-shop.css" rel="stylesheet" type="text/css" />
	<link href="<?=$assetFolder?>/css/color_scheme/orange.css" rel="stylesheet" type="text/css" id="color_scheme" />
    <link rel="shortcut icon" href="images/favicon.png">

	<script type="text/javascript" src="<?=$assetFolder?>/plugins/jquery/jquery-2.2.3.min.js"></script>
	<script type="text/javascript" src="header/common.js"></script>

<?php
	include "header/header_DB.php";

	$_isLogin = false;
	$_isOR = false;
	$_isRE = false;
	$_isVO = false;
	$_isOV = false;
	$_isLU = false;
	$_isDCM = false;
	$_isDUM = false;
	$_isAdmin = false;

	session_start();
	$dep_userno = $_SESSION["dep_userno"];
	$dep_userid = $_SESSION["dep_userid"];
	$dep_OR = $_SESSION["dep_OR"];
	$dep_RE = $_SESSION["dep_RE"];
	$dep_VO = $_SESSION["dep_VO"];
	$dep_OV = $_SESSION["dep_OV"];
	$dep_LU = $_SESSION["dep_LU"];
	$dep_DCM = $_SESSION["dep_DCM"];
	$dep_DUM = $_SESSION["dep_DUM"];
	$dep_ADMIN = $_SESSION["dep_ADMIN"];

	if ($dep_userno && $dep_userid)
		$_isLogin = true;

	if ($dep_ADMIN) {
		$_isAdmin = true;
		$_isOR = true;
		$_isRE = true;
		$_isVO = true;
		$_isOV = true;
		$_isLU = true;
		$_isDCM = true;
		$_isDUM = true;
	}
	else {
		if ($dep_OR)	$_isOR = true;
		if ($dep_RE)	$_isRE = true;
		if ($dep_VO)	$_isVO = true;
		if ($dep_OV)	$_isOV = true;
		if ($dep_LU)	$_isLU = true;
		if ($dep_DCM)	$_isDCM = true;
		if ($dep_DUM)	$_isDUM = true;
	}
?>
