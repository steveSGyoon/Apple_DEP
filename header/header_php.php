<?php
	$DBSERVER = "localhost";
	$DBUSER = "usr_dep";
	$DBPASSWORD = "dovmfeldlvl1*";
	$DBNAME = "_apple_DEP";

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