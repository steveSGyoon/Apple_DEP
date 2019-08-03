<?php
	$linkREFstr = "bType=$bType&joinDateRange=$joinDateRange&memberName=$memberName";
	$linkREFstr .= "&repCompanyNO=$repCompanyNO&helpCompanyNO=$helpCompanyNO&gateID=$gateID&userName=$userName";
	$linkREFstr .= "&firstCat=$firstCat&secondCat=$secondCat";

	if ($disp_st != 1) {
		$newstart = ($disp_st - 11) * $scale;
		$linkREFstr .= "&start=$newstart";

		echo "<a href='$PHPSELF?" . $linkREFstr . "'>";
		echo "<button type='button' class='btn btn-default'> < </button></a>";
	}

	for ($i=$disp_st; $i<=$disp_end; $i++) {
		$newstart = $i * $scale - $scale;
		$linkREFstr .= "&start=$newstart";

		if ($crt_page == $i)
			echo "<button type='button' class='btn btn-primary'> $i </button></a>";
		else {
			echo "<a href='$PHPSELF?" . $linkREFstr . "'>";
			echo "<button type='button' class='btn btn-default'> $i </button></a>";
		}
	}

	if ($disp_end < $total_pages) {
		$newstart = $disp_end * $scale;
		$linkREFstr .= "&start=$newstart";

		echo "<a href='$PHPSELF?" . $linkREFstr . "'>";
		echo "<button type='button' class='btn btn-default'> > </button></a>";
	}
?>
