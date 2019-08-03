<?php
	if (!$_isAdmin) {
		?>
		<script type="text/javascript">
			document.location.href	= "index.php";
		</script>
		<?php
	}
?>
