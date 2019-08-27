<?php

/***********************************************************************************************/
/*                                                                                             */
/*                                 MySQL DB Functions                                          */
/*                                                                                             */
/***********************************************************************************************/
function DBCONNECT_start()
{
	global	$DBSERVER, $DBUSER, $DBPASSWORD, $DBNAME;

	$connect = mysqli_connect($DBSERVER, $DBUSER, $DBPASSWORD, $DBNAME) or die("Unable to connect to SQL server");
	return ($connect);
}

// Close Connection
function DBCLOSE_end($cnt)
{
	mysqli_close($cnt);
}

// Execute SQL statement
function x_SQL($sqlst, $cnt)
{
	$rs = mysqli_query($cnt, $sqlst);
	return ($rs);
}

// Seek and Fetch Row
function x_SEEKFETCH($rs, $seek)
{
	mysqli_data_seek($rs, $seek);
	$row = mysqli_fetch_array($rs);
	return ($row);
}

// Free mem from Seek and Fetch Row
function x_FREEFETCH($rs)
{
	mysqli_free_result($rs);
}

// Fetch Row
function x_FETCH($sql, $cnt)
{
	$rs = mysqli_query($cnt, $sql);
	$row = mysqli_fetch_array($rs);
	mysqli_free_result($rs);
	return ($row);
}

// Fetch Row
function x_FETCH2($rs)
{
	$row = mysqli_fetch_array($rs);
	return ($row);
}

/***********************************************************************************************/
/*                                                                                             */
/*                                 PHP Date Functions                                          */
/*                                                                                             */
/***********************************************************************************************/
function crtDateDiff($checkDate, $targetDate)
{
	//$datetime1 = strtotime(date("Y-m-d"));
	$datetime1 = strtotime($targetDate);
	$datetime2 = strtotime($checkDate);
	$interval = ($datetime1 - $datetime2) / (24 * 60 * 60);
	
	return ($interval);
}

function addDate($sourceDate, $addAmount, $YMWD)
{
	switch ($YMWD) {
		case "Year":	$addString=" +" . $addAmount . " year";		break;
		case "Month":	$addString=" +" . $addAmount . " month";	break;
		case "Week":	$addString=" +" . $addAmount . " week";		break;
		case "Day":		$addString=" +" . $addAmount . " days";		break;
	}
	$date = strtotime(date("Y-m-d", strtotime($sourceDate)) . $addString);
	$date = date("Y-m-d",$date);

	return ($date);
}

/***********************************************************************************************/
/*                                                                                             */
/*                             FIle Upload DB Functions                                        */
/*                                                                                             */
/***********************************************************************************************/
function UpFileCheck($ID, $UpFiles, $Ufolder)
{
	$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);

	if ($UpFiles["error"] > 0){ // 에러가 있는지 검사하는 구문
		return($_FILES["file"]["error"]);
		//echo "Return Code: " . $_FILES["file"]["error"] . "<br>"; // 에러가 있으면 어떤 에러인지 출력함
	}
	else { // 에러가 없다면
		//echo "Upload: " . $UpFiles["name"] . "<br>"; // 전송된 파일의 실제 이름 값
		//echo "Type: " . $UpFiles["type"] . "<br>"; // 전송된 파일의 형식(type)
		//echo "Size: " . ($UpFiles["size"]) . " Byte<br>"; // 전송된 파일의 용량(기본 btye 값)
		//echo "Temp file: " . $UpFiles["tmp_name"] . "<br>"; //서버에 저장된 임시 복사본의 이름

//		$uFileName = $Ufolder . "/" . $ID . "_" . date("Ymd_His") . "_" . $UpFiles["name"];
		$uFileName = $ID . "_" . date("Ymd_His") . "_" . $UpFiles["name"];
//		if (file_exists($uFileName)) { // 같은 이름의 파일이 존재하는지 체크를 함
//			return("-2");
			//echo $UpFiles["name"] . " 동일한 파일이 있습니다. "; // 같은 파일이 있다면 "동일한 파일이 있습니다"를 출력
//		}
//		else { // 동일한 파일이 없다면
			$detectedType = exif_imagetype($UpFiles["tmp_name"]);
			$error = !in_array($detectedType, $allowedTypes);
			//echo $uFileName . "<br>";

			if ($error) {
				return("-3");
			}
			else {
				//move_uploaded_file($UpFiles["tmp_name"], $uFileName);
				return($uFileName);
				// Upload 폴더에 파일을 저장시킴
				//echo "Stored in: " . "Upload/" . $UpFiles["name"]; // Upload 폴더에 저장된 파일의 내용
			}
//		}
	}
}

function UpFileExecute($UpFileName, $UpFiles)
{
	$returnVal = move_uploaded_file($UpFiles["tmp_name"], $UpFileName);
	return($returnVal);
}


/***********************************************************************************************/
/*                                                                                             */
/*             XSS Keyword 필터링                                                              */
/*                                                                                             */
/***********************************************************************************************/
function XSSfilter($str)
{
	return $str; 

	//echo "str=" . $str . "<br>";
	//$filstr = "<script>, ja%0Av%0Aa%0As%0Ac%0Aript, %3Cscript, ScRiPt%20%0a%0d, %3Ealert, JaVaScRiPt, ScRiPt%20%0a%0d, JaVaScRiPt, javascript, vbscript, expression, applet, meta, xml, blink, link, style, script, embed, object, iframe, frame, frameset, ilayer, layer, bgsound, title, base, eval, innerHTML, charset, document, string, create, append, binding, alert, msgbox, refresh, embed, ilayer, applet, cookie, void, href, nabort, @import, +ADw, +AD4, aim:, %0da=eval, allowscriptaccess, xmlns:html, <html xmlns, xmlns:html=, http-equiv=refresh, http-equiv=refresh, x-scriptlet, echo(, 0%0d%0a%00, moz-binding, res://, #exec, background=, &#x, %u0, string.fromcharcode, firefoxurl, <br size=, list-style-image, acunetix_wvs, wvs-xss, lowsrc, dynsrc, behavior, activexobject, microsoft.xmlhttp, clsid:cafeefac-dec7-0000-0000-abcdeffedcba, application/npruntime-scriptable-plugin;deploymenttoolkit, onactivae, onafterprint, onafterupdate, onbefore, onbeforeactivate, oncopy, onbeforecopy, oncut, onbeforecut, onbeforedeactivate, onbeforeeditfocus, onbeforepaste, onbeforeprint, onbeforeunload, onbeforeupdate, onblur,  onbounce, oncellchange, onchange, onclick, oncontextmenu, oncontrolselect, ondataavailable, ondatasetchanged, ondatasetcomplete, ondblclick, ondeactivate, ondrag, ondragend, ondragenter, ondragleave, ondragover, ondragstart, ondrop, onerror,  onerrorupdate, onfilterchange, onfinish, onfocus, onfocusin, onfocusout,onhelp, onkeydown, onkeypress, onkeyup, onlayoutcomplete, onload, onlosecapture, onmousedown, onmouseenter, onmouseleave, onmousemove, onmouseout, onmouseover, onmouseup, onmousewheel, onmove, onmoveend, onmovestart, onpaste, onpropertychange, onreadystatechange, onreset, onscroll, onresize, onresizeend, onresizestart, onrowenter, onrowexit, onrowsdelete, onrowsinserted, onselect, onselectionchange, onselectstart, onstart,onstop, onsubmit, onunload "; //필터링 할 문자열 
	$filstr = "<script>, ja%0Av%0Aa%0As%0Ac%0Aript, %3Cscript, ScRiPt%20%0a%0d, %3Ealert, JaVaScRiPt, ScRiPt%20%0a%0d, JaVaScRiPt, javascript, vbscript, expression, applet, meta, xml, blink, link, style, script, embed, object, iframe, frame, frameset, ilayer, layer, bgsound, title, base, eval, innerHTML, charset, document, string, create, append, binding, alert, msgbox, refresh, embed, ilayer, applet, cookie, void, href, nabort, @import, +ADw, +AD4, aim:, %0da=eval, allowscriptaccess, xmlns:html, <html xmlns, xmlns:html=, http-equiv=refresh, http-equiv=refresh, x-scriptlet, 0%0d%0a%00, moz-binding, res://, #exec, background=, &#x, %u0, string.fromcharcode, firefoxurl, <br size=, list-style-image, acunetix_wvs, wvs-xss, lowsrc, dynsrc, behavior, activexobject, microsoft.xmlhttp, clsid:cafeefac-dec7-0000-0000-abcdeffedcba, application/npruntime-scriptable-plugin;deploymenttoolkit, onactivae, onafterprint, onafterupdate, onbefore, onbeforeactivate, oncopy, onbeforecopy, oncut, onbeforecut, onbeforedeactivate, onbeforeeditfocus, onbeforepaste, onbeforeprint, onbeforeunload, onbeforeupdate, onblur,  onbounce, oncellchange, onchange, onclick, oncontextmenu, oncontrolselect, ondataavailable, ondatasetchanged, ondatasetcomplete, ondblclick, ondeactivate, ondrag, ondragend, ondragenter, ondragleave, ondragover, ondragstart, ondrop, onerror,  onerrorupdate, onfilterchange, onfinish, onfocus, onfocusin, onfocusout,onhelp, onkeydown, onkeypress, onkeyup, onlayoutcomplete, onload, onlosecapture, onmousedown, onmouseenter, onmouseleave, onmousemove, onmouseout, onmouseover, onmouseup, onmousewheel, onmove, onmoveend, onmovestart, onpaste, onpropertychange, onreadystatechange, onreset, onscroll, onresize, onresizeend, onresizestart, onrowenter, onrowexit, onrowsdelete, onrowsinserted, onselect, onselectionchange, onselectstart, onstart,onstop, onsubmit, onunload "; //필터링 할 문자열 
	if ($filstr != "") {
		$otag = explode (",", $filstr); 
		for ($i=0; $i < count($otag); $i++) { 
			$str = eregi_replace($otag[$i], "_".$otag[$i]."_", $str); 
			//echo "For i str=" . $str . " : " . $i . "<br>";
			//if (!$str)
				//echo "otag = " . $otag[$i] . "<br>";
		}
	}
	//echo "str=" . $str . "<br>";

	return $str; 
}


//XSS 특수 문자(태그) 필터링
function clearXSS($str) {
	return $str; 

	$avatag = "p,br"; //허용할 태그 리스트(화이트 리스트)
	$str = eregi_replace("<", "&lt;", $str);
	$str = eregi_replace(">", "&gt;", $str);
	//$str = eregi_replace("\0", "", $str);

	//허용할 태그를 지정한 경우 
	if ($avatag != "") {
		$otag = explode (",", $avatag);

		//허용할 태그 원상태로 변환 
		for ($i = 0;$i < count($otag);$i++) { 
			$str = eregi_replace("&lt;".$otag[$i]." ", "<".$otag[$i]." ", $str); 
			$str = eregi_replace("&lt;".$otag[$i]."&gt;", "<".$otag[$i].">", $str); 
			$str = eregi_replace(" "+$otag[$i]."&gt;", " ".$otag[$i].">", $str); 
			$str = eregi_replace($otag[$i]."/&gt;", $otag[$i]."/>", $str); 
			$str = eregi_replace("&lt;/".$otag[$i], "</".$otag[$i], $str); 
		}
		return $str; 
	}
}


/***********************************************************************************************/
/*                                                                                             */
/*             XSS Keyword 필터링                                                              */
/*                                                                                             */
/***********************************************************************************************/
function doHttpPost($url = null, $postData = null) {
	if(!$url||!$postData){
		return NULL;
	}
	
	// curl -v —cert /etc/ssl/certs/GRX-0001192168.ACC1914.Test.AppleCare.chain.pem —key /home/sknb2b/dep_test_key/privatekey.key 
	// https://api-applecareconnect-ept.apple.com/enroll-service/1.0/check-transaction-status -d "{
	// 	"requestContext": {
	// 		"shipTo": "0000052010",
	// 		"timeZone": "420",
	// 		"langCode": "en"
	// 	},
	// 	"depResellerId": "16FCE4A0",
	// 	"deviceEnrollmentTransactionId": "9acc1cf5-e41d-44d4-a066-78162a389da2_1413529391461"
	// }"

	$certFile = "/etc/ssl/certs/GRX-0001192168.ACC1914.Test.AppleCare.chain.pem";
	$keyFile = "/home/sknb2b/dep_test_key/privatekey.key";
	// $actualUrl = "https://api-applecareconnect-ept.apple.com/enroll-service/1.0/check-transaction-status";
	// $requestXml = "";
	$caFile = "/etc/ssl/certs/ca-certificates.crt";

	// $ch = curl_init($actualUrl);
	// curl_setopt($ch, CURLOPT_URL, $actualUrl);
	// curl_setopt($ch, CURLOPT_SSLKEY, $keyFile);
	// curl_setopt($ch, CURLOPT_CAINFO, $caFile);
	// curl_setopt($ch, CURLOPT_SSLCERT, $certFile);
	// $ret = curl_exec($ch);


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_SSLKEY, $keyFile);
	curl_setopt($ch, CURLOPT_CAINFO, $caFile);
	curl_setopt($ch, CURLOPT_SSLCERT, $certFile);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response  = curl_exec($ch);
	curl_close($ch);

	return $response;
}

function make_order_json_string($order_idx, $void_ok, $cntDB) {
	$sql = "SELECT * FROM t_order WHERE 1 AND idx = $order_idx";
	$rowOrder = x_FETCH($sql, $cntDB);

	$order_date = substr($rowOrder[order_date], 0, 10) . "T" . substr($rowOrder[order_date], 11, 8) . "Z";
	$ship_date = substr($rowOrder[ship_date], 0, 10) . "T" . substr($rowOrder[ship_date], 11, 8) . "Z";

	$order_type = $rowOrder[order_type];
	if ($void_ok == 1)
		$order_type = "VD";

	$post_data = [];
	$orders = [];
	$devices = [];
	$deliveries = [];
	
	//$sql = "SELECT * FROM t_order WHERE 1 AND idx = $order_idx";
	//$rowOrder = x_FETCH($sql, $cntDB);
	
	$post_data['requestContext']['shipTo'] = $rowOrder[ship_to];
	$post_data['requestContext']['timeZone'] = "-540";
	$post_data['requestContext']['langCode'] = "ko";
	
	$post_data['transactionId'] = $rowOrder[transaction_id];
	$post_data['depResellerId'] = $rowOrder[dep_reseller_id];
	
	$orders[0]['orderNumber'] = $rowOrder[order_number];
	$orders[0]['orderDate'] = $order_date;
	$orders[0]['orderType'] = $order_type;
	$orders[0]['customerId'] = $rowOrder[dep_customer_id];
	$orders[0]['poNumber'] = $rowOrder[po_number];
	
	$id = 0;
	$sql = "SELECT DISTINCT(delivery_number) FROM t_order_device WHERE t_order_idx = $order_idx";
	$rs = x_SQL($sql, $cntDB);
	while ( $row = x_FETCH2($rs) ) {
		$device_ix = 0;
		$sql = "SELECT * FROM t_order_device WHERE 1 AND t_order_idx = $order_idx AND delivery_number = '$row[delivery_number]'";
		$rsDevice = x_SQL($sql, $cntDB);
		while ( $rowDevice = x_FETCH2($rsDevice) ) {
			$devices[$device_ix]['deviceId'] = $rowDevice[device_id];
			$devices[$device_ix]['assetTag'] = $rowDevice[asset_tag];
	
			$deliveries[$id]['deliveryNumber'] = $rowDevice[delivery_number];
			$deliveries[$id]['shipDate'] = $ship_date;
			$deliveries[$id]['devices'] = $devices;
	
			$device_ix++;
		}
		$orders[0]['deliveries'] = $deliveries;
		$id++;
	}
	$post_data['orders'] = $orders;
	
	return json_encode($post_data);
}


function make_order_json_string_for_detail($order_idx, $cntDB) {
	$sql = "SELECT * FROM t_order WHERE 1 AND idx = $order_idx";
	$rowOrder = x_FETCH($sql, $cntDB);

	$post_data = [];
	$post_data['requestContext']['shipTo'] = $rowOrder[ship_to];
	$post_data['requestContext']['timeZone'] = "-540";
	$post_data['requestContext']['langCode'] = "ko";
	$post_data['depResellerId'] = $rowOrder[dep_reseller_id];
	$post_data['orderNumbers'][0] = $rowOrder[order_number];
	
	return json_encode($post_data);
}

?>

