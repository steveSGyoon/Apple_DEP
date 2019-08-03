<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head>
<?php
	include "header/header.php";
	include "header/functionsY.php";
	include "header/_class-phpass.php";

	$cntDB = DBCONNECT_start();

	$wp_hasher = new PasswordHash(8, TRUE);
	$ecrypt_password = $wp_hasher->HashPassword("dlshxkdlawkfskrk101102*");

	$sql = "insert into t_user (user_id, user_password, user_name, _ADMIN) values ('admin', '$ecrypt_password', '관리자', 1)";
	$rsTemp = x_SQL($sql, $cntDB);
	$userIdx = mysqli_insert_id($cntDB);

	echo "ecrypt_password=" . $ecrypt_password . "<br>";
	if ($userIdx)
		echo "userIdx=" . $userIdx;
	else
		echo "FAIL";

	DBCLOSE_end($cntDB);
?>
</head>

<body class="smoothscroll enable-animation">
</body>
</html>
