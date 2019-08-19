<?php
header("Content-Type:text/html; charset=euc-kr;");
require("./lib/NicepayLite.php");
/*
*******************************************************
* <취소 결과 설정>
* 사용전 결과 옵션을 사용자 환경에 맞도록 변경하세요.
* 로그 디렉토리는 꼭 변경하세요.
*******************************************************
*/
$nicepay = new NicepayLite;
$nicepay->m_NicepayHome       = "c:\log";               // 로그 디렉토리 설정
$nicepay->m_ActionType        = "CLO";                  // 취소 요청 선언
$nicepay->m_CancelAmt         = $CancelAmt;             // 취소 금액 설정
$nicepay->m_TID               = $TID;                   // 취소 TID 설정
$nicepay->m_CancelMsg         = $CancelMsg;             // 취소 사유
$nicepay->m_PartialCancelCode = $PartialCancelCode;     // 전체 취소, 부분 취소 여부 설정
$nicepay->m_CancelPwd         = "123456";               // 취소 비밀번호 설정
$nicepay->m_ssl               = "true";                 // 보안접속 여부
$nicepay->startAction();
?>
<!DOCTYPE html>
<html>
<head>
<title>NICEPAY CANCEL RESULT(EUC-KR)</title>
<meta charset="euc-kr">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi" />
<link rel="stylesheet" type="text/css" href="./css/import.css"/>
</head>
<body>
  <div class="payfin_area">
    <div class="top">NICEPAY CANCEL RESULT(EUC-KR)</div>
    <div class="conwrap">
      <div class="con">
        <div class="tabletypea">
          <table>
            <tr>
              <th><span>거래 아이디</span></th>
              <td><?php echo($nicepay->m_ResultData["TID"]);?></td>
            </tr>
            <tr>
              <th><span>결제 수단</span></th>
              <td><?php echo($nicepay->m_ResultData["PayMethod"]);?></td>
            </tr>         
            <tr>
              <th><span>결과 내용</span></th> <!-- (취소성공: 2001, 취소성공(LGU 계좌이체):2211) -->
              <td><?php echo('['.$nicepay->m_ResultData["ResultCode"].']'.$nicepay->m_ResultData["ResultMsg"]);?></td>
            </tr>
            <tr>
              <th><span>취소 금액</span></th>
              <td><?php echo($nicepay->m_ResultData["CancelAmt"]);?></td>
            </tr>
            <tr>
              <th><span>취소일</span></th>
              <td><?php echo($nicepay->m_ResultData["CancelDate"]);?></td>
            </tr>
            <tr>
              <th><span>취소시간</span></th>
              <td><?php echo($nicepay->m_ResultData["CancelTime"]);?></td>
            </tr>
          </table>
        </div>
      </div>
      <p>* 취소가 성공한 경우에는 다시 승인상태로 복구 할 수 없습니다.</p>
    </div>
  </div>
</body>
</html>