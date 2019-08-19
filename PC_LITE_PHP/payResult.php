<?php
header("Content-Type:text/html; charset=euc-kr;"); 
require("./lib/NicepayLite.php");
/*
*******************************************************
* <���� ��� ����>
* ����� ��� �ɼ��� ����� ȯ�濡 �µ��� �����ϼ���.
* �α� ���丮�� �� �����ϼ���.
*******************************************************
*/   
$nicepay                  = new NicepayLite;
$MerchantKey              = "EYzu8jGGMfqaDEp76gSckuvnaHHu+bC4opsSN6lHv3b2lurNYkVXrZ7Z1AoqQnXI3eLuaUFyoRNC6FkrzVjceg=="; // ����Ű
$nicepay->m_NicepayHome   = "c:\log";               // �α� ���丮 ����
$nicepay->m_ActionType    = "PYO";                  // ActionType
$nicepay->m_ssl           = "true";                 // �������� ����
$nicepay->m_Price         = $Amt;                   // �ݾ�
$nicepay->m_NetCancelAmt  = $Amt;                   // ��ұݾ� ����
$nicepay->m_NetCancelPW   = "123456";	            // ���� ��� �н����� ����

/*
*******************************************************
* <���� ��� �ʵ�>
*******************************************************
*/
$nicepay->m_BuyerName     = $BuyerName;             // �����ڸ�
$nicepay->m_BuyerEmail    = $BuyerEmail;            // �������̸���
$nicepay->m_BuyerTel      = $BuyerTel;              // �����ڿ���ó
//$nicepay->m_EncryptedData = $EncryptedData;         // �ؽ���
$nicepay->m_GoodsName     = $GoodsName;             // ��ǰ��
$nicepay->m_GoodsCnt      = $m_GoodsCnt;            // ��ǰ����
$nicepay->m_GoodsCl       = $GoodsCl;               // �ǹ� or ������
$nicepay->m_PayMethod     = $PayMethod;             // ��������
$nicepay->m_Moid          = $Moid;                  // �ֹ���ȣ
$nicepay->m_MallUserID    = $MallUserID;            // ȸ����ID
$nicepay->m_MID           = $MID;                   // MID
$nicepay->m_MallIP        = $MallIP;                // Mall IP
$nicepay->m_MerchantKey   = $MerchantKey;           // ����Ű
$nicepay->m_LicenseKey    = $MerchantKey;           // ����Ű
$nicepay->m_TrKey         = $TrKey;                 // �ŷ�Ű
$nicepay->m_TransType     = $TransType;             // �Ϲ� or ����ũ��
$nicepay->startAction();
	
/*
*******************************************************
* <���� ���� ���� Ȯ��>
*******************************************************
*/	
$resultCode = $nicepay->m_ResultData["ResultCode"];
$payMethod  = $nicepay->m_ResultData["PayMethod"];

$paySuccess = false;
if($PayMethod == "CARD"){
    if($resultCode == "3001") $paySuccess = true;   // �ſ�ī��(���� ����ڵ�:3001)
}else if($PayMethod == "BANK"){
    if($resultCode == "4000") $paySuccess = true;   // ������ü(���� ����ڵ�:4000)
}else if($PayMethod == "CELLPHONE"){
    if($resultCode == "A000") $paySuccess = true;   // �޴���(���� ����ڵ�:A000)
}else if($PayMethod == "VBANK"){
    if($resultCode == "4100") $paySuccess = true;   // �������(���� ����ڵ�:4100)
}else if($payMethod == "SSG_BANK"){
    if($resultCode == "0000") $paySuccess = true;	  // SSG�������(���� ����ڵ�:0000)
}

?>	
<!DOCTYPE html>
<html>
<head>
<title>NICEPAY PAY RESULT(EUC-KR)</title>
<meta charset="euc-kr">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi" />
<link rel="stylesheet" type="text/css" href="./css/import.css"/>
</head>
<body> 
  <div class="payfin_area">
    <div class="top">NICEPAY PAY RESULT(EUC-KR)</div>
    <div class="conwrap">
      <div class="con">
        <div class="tabletypea">
          <table>
            <colgroup><col width="30%"/><col width="*"/></colgroup>          
              <tr>
                <th><span>��� ����</span></th>
                <td>[<?=$nicepay->m_ResultData["ResultCode"]?>] <?=$nicepay->m_ResultData["ResultMsg"]?></td>
              </tr>
              <tr>
                <th><span>���� ����</span></th>
                <td><?=$payMethod ?></td>
              </tr>
              <tr>
                <th><span>��ǰ��</span></th>
                <td><?=$nicepay->m_ResultData["GoodsName"]?></td>
              </tr>
              <tr>
                <th><span>�ݾ�</span></th>
                <td><?=$nicepay->m_ResultData["Amt"]?>��</td>
              </tr>
              <tr>
                <th><span>�ŷ����̵�</span></th>
                <td><?=$nicepay->m_ResultData["TID"]?></td>
              </tr>               
            <?php if($payMethod=="CARD"){?>
              <tr>
                <th><span>ī����</span></th>
                <td><?=$nicepay->m_ResultData["CardName"]?></td>
              </tr>
              <tr>
                <th><span>�Һΰ���</span></th>
                <td><?=$nicepay->m_ResultData["CardQuota"]?></td>
              </tr>
            <?php }else if($payMethod=="BANK"){?>
              <tr>
                <th><span>����</span></th>
                <td><?=$nicepay->m_ResultData["BankName"]?></td>
              </tr>
              <tr>
                <th><span>���ݿ����� Ÿ��(0:�������,1:�ҵ����,2:��������)</span></th>
                <td><?=$nicepay->m_ResultData["RcptType"]?></td>
              </tr>
            <?php }else if($payMethod=="CELLPHONE"){?>
              <tr>
                <th><span>����� ����</span></th>
                <td><?=$nicepay->m_ResultData["Carrier"]?></td>
              </tr>
              <tr>
                <th><span>�޴��� ��ȣ</span></th>
                <td><?=$nicepay->m_ResultData["DstAddr"]?></td>
              </tr>
            <?php }else if($payMethod=="VBANK"){?>
              <tr>
                <th><span>�Ա� ����</span></th>
                <td><?=$nicepay->m_ResultData["VbankBankName"]?></td>
              </tr>
              <tr>
                <th><span>�Ա� ����</span></th>
                <td><?=$nicepay->m_ResultData["VbankNum"]?></td>
              </tr>
              <tr>
                <th><span>�Ա� ����</span></th>
                <td><?=$nicepay->m_ResultData["VbankExpDate"]?></td>
              </tr>
            <?php }else if($payMethod=="SSG_BANK"){?>
              <tr>
                <th><span>����</span></th>
                <td><?=$nicepay->m_ResultData["BankName"]?></td>
              </tr>
              <tr>
                <th><span>���ݿ����� Ÿ�� (0:�������,1:�ҵ����,2:��������)</span></th>
                <td><?=$nicepay->m_ResultData["RcptType"]?></td>
              </tr>				  
            <?php }?>
          </table>
        </div>
      </div>
      <p>*�׽�Ʈ ���̵��ΰ�� ���� ���� 11�� 30�п� ��ҵ˴ϴ�.</p>
    </div>
  </div>
</body>
</html>