<?php header("Content-Type:text/html; charset=euc-kr;"); ?>
<!DOCTYPE html>
<html>
<head>
<title>NICEPAY CANCEL REQUEST(EUC-KR)</title>
<meta charset="euc-kr">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi" />
<link rel="stylesheet" type="text/css" href="./css/import.css"/>
<script type="text/javascript">
<!--
function goCancel() {		
	document.tranMgr.submit();
}
-->
</script>
</head>
<body>
<form name="tranMgr" method="post" action="cancelResult.php">
    <div class="payfin_area">
      <div class="top">NICEPAY CANCEL REQUEST(EUC-KR)</div>
      <div class="conwrap">
        <div class="con">
          <div class="tabletypea">
            <table>
              <colgroup><col width="30%" /><col width="*" /></colgroup>
              <tr>
                <th><span>MID</span></th>
                <td><input type="text" name="MID" value="nicepay00m"></td>
              </tr>	
              <tr>
                <th><span>거래아이디</span></th>
                <td><input type="text" name="TID" value=""></td>
              </tr>	              
              <tr>
                <th><span>취소 금액</span></th>
                <td><input type="text" name="CancelAmt" value=""></td>
              </tr>
              <tr>
                <th><span>취소 사유</span></th>
                <td><input type="text" name="CancelMsg" value="고객 요청"></td>
              </tr>           
              <tr>
                <th><span>취소 패스워드</span></th>
                <td><input type="password" name="CancelPwd" value="123456"></td>
              </tr> 
              <tr>
                <th><span>부분취소 여부</span></th>
                <td>
                  <select name="PartialCancelCode">
                    <option value="0">전체 취소</option>
                    <option value="1">부분 취소</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <p>* 취소 요청시 상단의 모든 값을 입력 하세요.</p>
        <p>* 신용카드결제, 계좌이체, 가상계좌만 부분취소/부분환불이 가능합니다.</p>
        <div class="btngroup">
          <a href="#" class="btn_blue" onClick="goCancel();">요 청</a>
        </div>
      </div>
    </div>
</form>
</body>
</html>
