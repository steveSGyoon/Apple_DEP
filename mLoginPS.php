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

	$user_id = clearXSS(XSSfilter($_POST["user_id"]));
	$user_password = clearXSS(XSSfilter($_POST["user_password"]));

	$sql = "SELECT count(*) AS member_cnt FROM t_user WHERE user_id='$user_id' AND is_valid=1";
	$rowCnt = x_FETCH($sql, $cntDB);

	$is_member = true;
	if ($rowCnt[member_cnt] == 1) {
		$sql = "SELECT * FROM t_user WHERE user_id='$user_id' AND is_valid=1";
		$row = x_FETCH($sql, $cntDB);

		$wp_hasher = new PasswordHash(8, TRUE);
		$check = $wp_hasher->CheckPassword($user_password, $row[user_password]);

		if ($check) {
			session_start();
			$_SESSION['dep_userno'] = $row[idx];
			$_SESSION['dep_userid'] = $row[user_id];
			$_SESSION['dep_OR'] = $row[_OR];
			$_SESSION['dep_RE'] = $row[_RE];
			$_SESSION['dep_VO'] = $row[_VO];
			$_SESSION['dep_OV'] = $row[_OV];
			$_SESSION['dep_LU'] = $row[_LU];
			$_SESSION['dep_DCM'] = $row[_DCM];
			$_SESSION['dep_DUM'] = $row[_DUM];
			$_SESSION['dep_ADMIN'] = $row[_ADMIN];
		}
		else
			$is_member = false;
	}
	else
		$is_member = false;


	// so final decision here~~
	if ($is_member) {
		?>
		<script language="JavaScript">
		<!--
			//alert ("W E LC O M E !!!.");
			self.document.location.href	= "indexMain.php";
		//-->
		</script>
		<?php
	}
	else {
		?>
		<script>
			alert ("ID or password is not correct.\nPlease try again.");
			self.document.location.href	= "index.php";
		</script>
		<?php
	}

	DBCLOSE_end($cntDB);
?>
</HEAD>

<BODY LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 >
</BODY>
</HTML>
