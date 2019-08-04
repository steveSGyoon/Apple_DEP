<?php
	include "header/header_php.php";
	include "header/functionsY.php";
	include "header/_class-phpass.php";
	include "header/_checkLogin.php";

	$cntDB = DBCONNECT_start();

	$ret = [];

	$idx = clearXSS(XSSfilter($_GET["idx"]));
	$action = $_POST["action"];

	if ($action == "edit") {
		$user_name = clearXSS(XSSfilter($_POST["user_name"]));
		$user_password = clearXSS(XSSfilter($_POST["user_password"]));
	
		$wp_hasher = new PasswordHash(8, TRUE);
		$ecrypt_password = $wp_hasher->HashPassword($user_password);

		$sql = "UPDATE 
					t_user
				SET
					user_name = '$user_name',
					user_password = '$ecrypt_password',
					edit_info_date = now()
				WHERE 
					idx = $idx
		";
		$rs = x_SQL($sql, $cntDB);
	
		if ($rs)
			$ret = array('result'=>'success', 'result_msg'=>'Password and name was changed.');
		else
			$ret = array('result'=>'fail', 'error_msg'=>'Fail to change data.');
	}
	else if ($action == "delete") {
		$sql = "UPDATE 
					t_user
				SET
					is_valid = 0
				WHERE 
					idx = $idx
		";
		$rs = x_SQL($sql, $cntDB);
	
		if ($rs)
			$ret = array('result'=>'success', 'result_msg'=>'User was deleted.');
		else
			$ret = array('result'=>'fail', 'error_msg'=>'Fail to delete data.');
	}
	else if ($action == "permission") {
		$menuOR = 0;
		$menuRE = 0;
		$menuOV = 0;
		$menuVO = 0;
		$menuLU = 0;
		$menuDCM = 0;
		$menuDUM = 0;
		$menuADMIN = 0;
	
		if ($_POST["menuADMIN"]){
			$menuOR = 1;
			$menuRE = 1;
			$menuOV = 1;
			$menuVO = 1;
			$menuLU = 1;
			$menuDCM = 1;
			$menuDUM = 1;
			$menuADMIN = 1;
		}
		else {
			if ($_POST["menuOR"])		$menuOR = 1;
			if ($_POST["menuRE"])		$menuRE = 1;
			if ($_POST["menuOV"])		$menuOV = 1;
			if ($_POST["menuVO"])		$menuVO = 1;
			if ($_POST["menuLU"])		$menuLU = 1;
			if ($_POST["menuDCM"])		$menuDCM = 1;
			if ($_POST["menuDUM"])		$menuDUM = 1;
		}
	
		$sql = "UPDATE 
					t_user
				SET
					_OR = $menuOR,
					_RE = $menuRE,
					_OV = $menuOV,
					_VO = $menuVO,
					_LU = $menuLU,
					_DCM = $menuDCM,
					_DUM = $menuDUM,
					_ADMIN = $menuADMIN,
					edit_permission_date = now()
				WHERE 
					idx = $idx
		";
		$rs = x_SQL($sql, $cntDB);
	
		if ($rs)
			$ret = array('result'=>'success', 'result_msg'=>'Permission was changed.');
		else
			$ret = array('result'=>'fail', 'error_msg'=>'Fail to change data.');
	}
	else if ($action == "new") {
		$user_id = clearXSS(XSSfilter($_POST["user_id"]));
		$user_name = clearXSS(XSSfilter($_POST["user_name"]));
		$user_password = clearXSS(XSSfilter($_POST["user_password"]));

		$sql = "SELECT count(*) AS user_cnt FROM t_user WHERE user_id='$user_id'";
		$row = x_FETCH($sql, $cntDB);

		if ($row[user_cnt])
			$ret = array('result'=>'fail', 'error_msg'=>'Duplicated ID. Please use another one.');
		else {
			$wp_hasher = new PasswordHash(8, TRUE);
			$ecrypt_password = $wp_hasher->HashPassword($user_password);
		
			$sql = "INSERT INTO t_user (user_id, user_password, user_name) VALUES ('$user_id', '$ecrypt_password', '$user_name')";
			$rsTemp = x_SQL($sql, $cntDB);
			$userIdx = mysqli_insert_id($cntDB);
		
			if ($userIdx)
				$ret = array('result'=>'success', 'result_msg'=>'User was added. Please set permission.', 'idx'=>$userIdx);
			else
				$ret = array('result'=>'fail', 'error_msg'=>'Fail to add user.');
		}
	}


	header('Content-Type: application/json; charset=utf-8');
	echo json_encode( $ret );

	DBCLOSE_end($cntDB);
?>
