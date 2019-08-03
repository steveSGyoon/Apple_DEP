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
/*             FORM making - text relatings, file, selection and so on...                      */
/*                                                                                             */
/***********************************************************************************************/
function formMakeInput($columnSet, $labelText, $inputType, $icoAppend, $inputName, $toolTip, $requiredSet, $crtValue)
{
	$columnDivStart = "";
	$columnDivEnd = "";
	if ($columnSet) {
		$columnDivStart = "<div class='col-md-$columnSet col-sm-$columnSet'>";
		$columnDivEnd = "</div>";
	}

	$classForm = " class='form-control' ";
	if ($inputType == "password")
		$classForm = " class='err' ";

	$icoAppendForm = "";
	if ($icoAppend)
		$icoAppendForm = "<i class='ico-append fa $icoAppend'></i>";

	$returnString = "
		$columnDivStart
			<label>$labelText</label>
			<label class='input margin-bottom-10'>
				$icoAppendForm
				<input type='$inputType' name='$inputName' id='$inputName' $requiredSet $classForm value='$crtValue'>
				<b class='tooltip tooltip-bottom-right'>$toolTip</b>
			</label>
		$columnDivEnd";

	return $returnString;
}

function formMakeInputAddress($columnSet, $crtValue0, $crtValue1, $crtValue2)
{
	$columnDivStart = "<div class='col-md-$columnSet col-sm-$columnSet'>";
	$columnDivEnd = "</div>";
	$returnString = "
		$columnDivStart
			<label>주소 *</label>
			<input type='text' name='rcvPostcode' id='rcvPostcode' value='$crtValue0' required placeholder='우편번호' readonly> -
			<input type='button' onclick='execDaumPostcode()' value='우편번호 찾기'><br />
			<input type='text' name='rcvAddr1' id='rcvAddr1' class='form-control margin-top-10' value='$crtValue1' required placeholder='주소'>
			<input type='text' name='rcvAddr2' id='rcvAddr2' class='form-control margin-top-10' value='$crtValue2' required placeholder='상세주소'>
		$columnDivEnd";

	return $returnString;
}
function formMakeInputAddressReadOnly($columnSet, $crtValue0, $crtValue1, $crtValue2)
{
	$columnDivStart = "<div class='col-md-$columnSet col-sm-$columnSet'>";
	$columnDivEnd = "</div>";
	$returnString = "
		$columnDivStart
			<label>주소 *</label>
			<input type='text' name='rcvPostcode' id='rcvPostcode' class='form-control' value='우편번호 : $crtValue0' readonly>
			<input type='text' name='rcvAddr1' id='rcvAddr1' class='form-control margin-top-10' value='$crtValue1' readonly>
			<input type='text' name='rcvAddr2' id='rcvAddr2' class='form-control margin-top-10' value='$crtValue2' readonly>
		$columnDivEnd";

	return $returnString;
}

function formMakeInputMasked($columnSet, $labelText, $icoAppend, $inputName, $toolTip, $requiredSet, $dataFormat, $placeHolder, $crtValue)
{
	$columnDivStart = "";
	$columnDivEnd = "";
	if ($columnSet) {
		$columnDivStart = "<div class='col-md-$columnSet col-sm-$columnSet'>";
		$columnDivEnd = "</div>";
	}

	///(^[0-9\-_]+$)/;
	$onKeyEventString = "onkeyup=\"if (event.keyCode!=8 && event.keyCode!=46 && event.keyCode!=37 && event.keyCode!=39) this.value=this.value.replace(/[^0-9]/g,'')\"";
	if ( strpos($dataFormat, "-") )
		$onKeyEventString = "onkeyup=\"if (event.keyCode!=8 && event.keyCode!=46 && event.keyCode!=37 && event.keyCode!=39) this.value=this.value.replace(/[^0-9\-]/g,'')\"";

	$returnString = "
		$columnDivStart
			<label>$labelText</label>
			<label class='input margin-bottom-10'>
				<i class='ico-append fa $icoAppend'></i>
				<input type='$inputType' name='$inputName' id='$inputName' $requiredSet $classForm value='$crtValue' $onKeyEventString>
				<b class='tooltip tooltip-bottom-right'>$toolTip</b>
			</label>
		$columnDivEnd";

	/*
	$returnString = "
		$columnDivStart
			<label>$labelText</label>
			<label class='input margin-bottom-10'>
				<i class='ico-append fa $icoAppend'></i>
				<input type='text' class='form-control masked' name='$inputName' id='$inputName' data-format='$dataFormat' data-placeholder='0' placeholder='$placeHolder' $requiredSet value='$crtValue'>
				<b class='tooltip tooltip-bottom-right'>$toolTip</b>
			</label>
		$columnDivEnd";
	*/

	return $returnString;
}

function formMakeInputDate($columnSet, $labelText, $inputName, $crtValue)
{
	$columnDivStart = "";
	$columnDivEnd = "";
	if ($columnSet) {
		$columnDivStart = "<div class='col-md-$columnSet col-sm-$columnSet'>";
		$columnDivEnd = "</div>";
	}

	$returnString = "
		$columnDivStart
			<label>$labelText</label>
			<input type='text' name='$inputName' id='$inputName' class='form-control datepicker' data-format='yyyy-mm-dd' data-lang='en' data-RTL='false' value='$crtValue'>
		$columnDivEnd";

	return $returnString;
}


function formMakeInputFile($columnSet, $labelText, $inputName, $imageYes)
{
	$columnDivStart = "";
	$columnDivEnd = "";
	if ($columnSet) {
		$columnDivStart = "<div class='col-md-$columnSet col-sm-$columnSet'>";
		$columnDivEnd = "</div>";
	}

	if ($imageYes)	$imageYes = "<small class='text-muted block'>Max file size: 3Mb (jpg/gif/png)</small>";
	else			$imageYes = "<small class='text-muted block'>Max file size: 3Mb</small>";
	$returnString = "
		$columnDivStart
			<label>$labelText</label>
			<input class='custom-file-upload' type='file' name='$inputName' id='$inputName' data-btn-text='Select a File' />
			$imageYes
		$columnDivEnd";

	return $returnString;
}

function formMakeStepper($columnSet, $labelText, $inputName, $onChange)
{
	$columnDivStart = "";
	$columnDivEnd = "";
	if ($columnSet) {
		$columnDivStart = "<div class='col-md-$columnSet col-sm-$columnSet'>";
		$columnDivEnd = "</div>";
	}

	if ($onChange) {
		$onChangeStr1 = "onChange=\"" . $onChange . "(this.value)\"";
		$onChangeStr2 = "onClick=\"" . $onChange . "(this.value)\"";
	}

	$returnString = "
		$columnDivStart
			<label>$labelText</label>
			<label class='input margin-bottom-10'>
				<input type='text' name='$inputName' id='$inputName' value='1' min='1' max='1000' class='form-control stepper' $onChangeStr1, $onChangeStr2>
			</label>
		$columnDivEnd";

	return $returnString;
}

function formMakeInputDummy($columnSet, $labelText, $inputName, $crtValue)
{
	$columnDivStart = "";
	$columnDivEnd = "";
	if ($columnSet) {
		$columnDivStart = "<div class='col-md-$columnSet col-sm-$columnSet'>";
		$columnDivEnd = "</div>";
	}

	$classForm = " class='form-control' ";
	$returnString = "
		$columnDivStart
			<label>$labelText</label>
			<label class='input margin-bottom-10'>
				<i class='ico-append fa-money'></i>
				<input type='text' name='$inputName' id='$inputName' $classForm value='$crtValue' readonly>
			</label>
		$columnDivEnd";

	return $returnString;
}

function formMakeTextArea($columnSet, $labelText, $placeHolder, $rowS, $textAreaName, $requiredSet, $crtValue)
{
	$columnDivStart = "";
	$columnDivEnd = "";
	if ($columnSet) {
		$columnDivStart = "<div class='col-md-$columnSet col-sm-$columnSet'>";
		$columnDivEnd = "</div>";
	}

	$returnString = "
		$columnDivStart
			<label>$labelText</label>
			<div class='fancy-form'>
				<textarea $requiredSet placeholder='$placeHolder' name='$textAreaName' id='$textAreaName' rows='$rowS' class='form-control word-count' data-maxlength='10000' data-info='textarea-words-info'>$crtValue</textarea>
				<i class='fa fa-comments''><!-- icon --></i>
				<span class='fancy-hint size-11 text-muted'>
					<strong>Hint:</strong> 10000 words allowed!
					<span class='pull-right'>
						<span id='textarea-words-info'>0/10000</span> Words
					</span>
				</span>
			</div>
		$columnDivEnd";

	return $returnString;
}

function formMakeSelect($columnSet, $labelText, $selectName, $selectValue, $selectDisplay, $crtSelection, $required, $onChange)
{
	$columnDivStart = "";
	$columnDivEnd = "";
	if ($columnSet) {
		$columnDivStart = "<div class='col-md-$columnSet col-sm-$columnSet'>";
		$columnDivEnd = "</div>";
	}

	if ($onChange)
		$onChangeStr = "onChange=\"" . $onChange . "(this.value)\"";

	$returnString = "
		$columnDivStart
			<label>$labelText</label>
			<div class='fancy-form fancy-form-select'>
				<select $required class='form-control select' name='$selectName' id='$selectName' $onChangeStr>";
					for ($i=0; $i<count($selectValue); $i++) {
						if ($crtSelection == $selectValue[$i])
							$returnString .= "<option value='$selectValue[$i]' selected> $selectDisplay[$i] </option>";
						else
							$returnString .= "<option value='$selectValue[$i]'> $selectDisplay[$i] </option>";
					}
					$returnString .= "
				</select>
				<i class='fancy-arrow'></i>
			</div>
		$columnDivEnd";

	return $returnString;
}





// XSS Keyword 필터링
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

?>

