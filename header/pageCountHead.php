<?php
	$start = clearXSS(XSSfilter($_GET["start"]));

	if ($scale_SET)
		$scale = $scale_SET;
	else
		$scale = 15;
	$spages = 10;
	if (!$start)
		$start = 0;

	$row = x_Fetch($DBNAME, $sql, $cntDB);
	$total = $row[0];

	if ($start+$scale > $total)
		$readnum = $total - $start;
	else
		$readnum = $scale;
	
	$cnum = $total - $start + 1;
	$total_pages = (int) ($total / $scale);
	if ($total % $scale)
		$total_pages++;
	$crt_page = $start / $scale + 1;

	if ($total)
		$last_page = $total_pages * $scale - $scale;
	else
		$last_page = 0;

	// setup start page and end page for each section
	$disp_pg = $start / $scale;
	$disp_pg = (int) ($disp_pg / $spages);

	$disp_st = $spages * $disp_pg + 1;
	$disp_end = $spages * ($disp_pg + 1);

	if ($disp_end > $total_pages)
		$disp_end = $total_pages;
?>
