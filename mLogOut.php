<?php
	$mytime = microtime();

	session_start();
	session_destroy();

	/*
	setcookie("ocUSERNO",  "", 0, "/");
	setcookie("ocUSERNAME",    "", 0, "/");
	setcookie("ocUSEREMAIL",    "", 0, "/");
	setcookie("ocUSERTYPE", "", 0, "/");
	*/
?>

<script language="JavaScript">
	document.location.href	= "index.php";
</script>
