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

	$is_member = 1;
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
			$is_member = 0;
	}
	else
		$is_member = -1;


	// so final decision here~~
	if ($is_member == -1) {
		?>
		<script>
			alert ("user id is not correct.\nPlease try again.");
			self.document.location.href	= "index.php";
		</script>
		<?php
	}
	else if ($is_member == 1) {
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
		$sql = "insert into t_login_try (user_id) values ('$user_id')";
		$rs = x_SQL($sql, $cntDB);

		$sql = "select count(*) from t_login_try where user_id='$user_id' and is_valid=1";
		$rowTry = x_FETCH($sql, $cntDB);
		if ($rowTry[0] >= 5) {
			if ($rowTry[0] == 5) {
				$randPassword = getNewPassword();
				$wp_hasher = new PasswordHash(8, TRUE);
				$ecrypt_password = $wp_hasher->HashPassword($randPassword);
	
				$sql = "update t_user set user_password=\"$ecrypt_password\" where user_id='$user_id'";
				$rsTemp = x_SQL($sql, $cntDB);
	
				//복구 이메일링
				$emailHeadLine = "비밀번호 설정";
				$emailContent = "안녕하세요.<br /><br />회원님이 입력하신 사용자정보가 5회 이상 틀리게 이용되어 임시 비밀번호로 초기화 되었습니다..<br /><br />";
				$emailContent .= "임시 비밀번호 : " . $randPassword . "<br /><br />";
				$emailContent .= "임시 비밀번호를 이용하여 로그인 하신 후 [내정보 > 나의정보] 메뉴에서 비밀번호를 다시 설정해 주세요.";
				$targetEmail =  $user_id;
				include "email_send.php";
				?>
				<script>
					alert ("5회 이상 사용자정보를 잘못 입력 하셔서 계정 사용이 정지되었습니다.\n계정 복구 이메일을 발송 하였으니 이메일 내용을 확인 하시어 계정을 복구하세요.");
					self.document.location.href	= "index.php";
				</script>
				<?php
			}
			else {
				?>
				<script>
					alert ("5회 이상 사용자정보를 잘못 입력 하셔서 계정 사용이 정지되었습니다.\n계정 복구 이메일 내용을 확인 하시어 계정을 복구하세요.");
					self.document.location.href	= "index.php";
				</script>
				<?php
			}
		}
		else {
			?>
			<script>
				alert ("사용자정보를 <?=$rowTry[0]?>회 잘못 입력 하셨습니다.\n5회 이상 사용자정보를 잘못 입력 하시면 계정 사용이 정지 됩니다.\n다시 시도해 주시기 바랍니다.");
				self.document.location.href	= "index.php";
			</script>
			<?php
		}
	}

	DBCLOSE_end($cntDB);
?>
</HEAD>

<BODY LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 >
</BODY>
</HTML>
