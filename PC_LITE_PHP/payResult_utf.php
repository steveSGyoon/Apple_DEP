<?php
header("Content-Type:text/html; charset=utf-8;"); 
require("./lib/NicepayLite.php");
/*
*******************************************************
* <결제 결과 설정>
* 사용전 결과 옵션을 사용자 환경에 맞도록 변경하세요.
* 로그 디렉토리는 꼭 변경하세요.
*******************************************************
*/ 
$nicepay                  = new NicepayLite;
$MerchantKey              = "EYzu8jGGMfqaDEp76gSckuvnaHHu+bC4opsSN6lHv3b2lurNYkVXrZ7Z1AoqQnXI3eLuaUFyoRNC6FkrzVjceg=="; // 상점키
$nicepay->m_NicepayHome   = "/home/manager/dep/_nicepayLog";               // 로그 디렉토리 설정
$nicepay->m_ActionType    = "PYO";                  // ActionType
$nicepay->m_charSet       = "UTF8";                 // 인코딩
$nicepay->m_ssl           = "true";                 // 보안접속 여부
$nicepay->m_Price         = $Amt;                   // 금액
$nicepay->m_NetCancelAmt  = $Amt;                   // 취소 금액
$nicepay->m_NetCancelPW   = "123456";               // 결제 취소 패스워드 설정   

/*
*******************************************************
* <결제 결과 필드>
*******************************************************
*/
$nicepay->m_BuyerName     = $BuyerName;             // 구매자명
$nicepay->m_BuyerEmail    = $BuyerEmail;            // 구매자이메일
$nicepay->m_BuyerTel      = $BuyerTel;              // 구매자연락처
//$nicepay->m_EncryptedData = $EncryptedData;         // 해쉬값
$nicepay->m_GoodsName     = $GoodsName;             // 상품명
$nicepay->m_GoodsCnt      = $m_GoodsCnt;            // 상품개수
$nicepay->m_GoodsCl       = $GoodsCl;               // 실물 or 컨텐츠
$nicepay->m_Moid          = $Moid;                  // 주문번호
$nicepay->m_MallUserID    = $MallUserID;            // 회원사ID
$nicepay->m_MID           = $MID;                   // MID
$nicepay->m_MallIP        = $MallIP;                // Mall IP
$nicepay->m_MerchantKey   = $MerchantKey;           // 상점키
$nicepay->m_LicenseKey    = $MerchantKey;           // 상점키
$nicepay->m_TransType     = $TransType;             // 일반 or 에스크로
$nicepay->m_TrKey         = $TrKey;                 // 거래키
$nicepay->m_PayMethod     = $PayMethod;             // 결제수단
$nicepay->startAction();
	
/*
*******************************************************
* <결제 성공 여부 확인>
*******************************************************
*/	
$resultCode = $nicepay->m_ResultData["ResultCode"];
$payMethod  = $nicepay->m_ResultData["PayMethod"];

$paySuccess = false;
if($PayMethod == "CARD"){
    if($resultCode == "3001") $paySuccess = true;   // 신용카드(정상 결과코드:3001)
}else if($PayMethod == "BANK"){
    if($resultCode == "4000") $paySuccess = true;   // 계좌이체(정상 결과코드:4000)
}else if($PayMethod == "CELLPHONE"){
    if($resultCode == "A000") $paySuccess = true;   // 휴대폰(정상 결과코드:A000)
}else if($PayMethod == "VBANK"){
    if($resultCode == "4100") $paySuccess = true;   // 가상계좌(정상 결과코드:4100)
}else if($payMethod == "SSG_BANK"){
    if($resultCode == "0000") $paySuccess = true;	  // SSG은행계좌(정상 결과코드:0000)
}

?>	
<!DOCTYPE html>
<html>
<head>
<title>NICEPAY PAY RESULT(UTF-8)</title>
<meta charset="euc-kr">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi" />
<link rel="stylesheet" type="text/css" href="./css/import.css"/>
</head>
<body> 
  <div class="payfin_area">
    <div class="top">NICEPAY PAY RESULT(UTF-8)</div>
    <div class="conwrap">
      <div class="con">
        <div class="tabletypea">
          <table>
            <colgroup><col width="30%"/><col width="*"/></colgroup>            
              <tr>
                <th><span>결과 내용</span></th>
                <td>[<?=$nicepay->m_ResultData["ResultCode"]?>] <?=$nicepay->m_ResultData["ResultMsg"]?></td>
              </tr>
              <tr>
                <th><span>결제 수단</span></th>
                <td><?=$payMethod ?></td>
              </tr>
              <tr>
                <th><span>상품명</span></th>
                <td><?=$nicepay->m_ResultData["GoodsName"]?></td>
              </tr>
              <tr>
                <th><span>금액</span></th>
                <td><?=$nicepay->m_ResultData["Amt"]?>원</td>
              </tr>
              <tr>
                <th><span>거래아이디</span></th>
                <td><?=$nicepay->m_ResultData["TID"]?></td>
              </tr>               
            <?php if($payMethod=="CARD"){?>
              <tr>
                <th><span>카드사명</span></th>
                <td><?=$nicepay->m_ResultData["CardName"]?></td>
              </tr>
              <tr>
                <th><span>할부개월</span></th>
                <td><?=$nicepay->m_ResultData["CardQuota"]?></td>
              </tr>
            <?php }else if($payMethod=="BANK"){?>
              <tr>
                <th><span>은행</span></th>
                <td><?=$nicepay->m_ResultData["BankName"]?></td>
              </tr>
              <tr>
                <th><span>현금영수증 타입(0:발행안함,1:소득공제,2:지출증빙)</span></th>
                <td><?=$nicepay->m_ResultData["RcptType"]?></td>
              </tr>
            <?php }else if($payMethod=="CELLPHONE"){?>
              <tr>
                <th><span>이통사 구분</span></th>
                <td><?=$nicepay->m_ResultData["Carrier"]?></td>
              </tr>
              <tr>
                <th><span>휴대폰 번호</span></th>
                <td><?=$nicepay->m_ResultData["DstAddr"]?></td>
              </tr>
            <?php }else if($payMethod=="VBANK"){?>
              <tr>
                <th><span>입금 은행</span></th>
                <td><?=$nicepay->m_ResultData["VbankBankName"]?></td>
              </tr>
              <tr>
                <th><span>입금 계좌</span></th>
                <td><?=$nicepay->m_ResultData["VbankNum"]?></td>
              </tr>
              <tr>
                <th><span>입금 기한</span></th>
                <td><?=$nicepay->m_ResultData["VbankExpDate"]?></td>
              </tr>
            <?php }else if($payMethod=="SSG_BANK"){?>
              <tr>
                <th><span>은행</span></th>
                <td><?=$nicepay->m_ResultData["BankName"]?></td>
              </tr>
              <tr>
                <th><span>현금영수증 타입 (0:발행안함,1:소득공제,2:지출증빙)</span></th>
                <td><?=$nicepay->m_ResultData["RcptType"]?></td>
              </tr>				  
            <?php }?>
          </table>
        </div>
      </div>
      <p>*테스트 아이디인경우 당일 오후 11시 30분에 취소됩니다.</p>
    </div>
  </div>
</body>
</html>