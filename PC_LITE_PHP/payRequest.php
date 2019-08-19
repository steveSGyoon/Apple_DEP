<?php
header("Content-Type:text/html; charset=euc-kr;"); 
require("./lib/NicepayLite.php");
/*
*******************************************************
* <������û �Ķ����>
* ������ Form �� ������ ������û �Ķ�����Դϴ�.
* ���������������� �⺻(�ʼ�) �Ķ���͸� ���õǾ� ������, 
* �߰� ������ �ɼ� �Ķ���ʹ� �����޴����� �����ϼ���.
*******************************************************
*/  
$nicepay = new NicepayLite;

$nicepay->m_MerchantKey = "EYzu8jGGMfqaDEp76gSckuvnaHHu+bC4opsSN6lHv3b2lurNYkVXrZ7Z1AoqQnXI3eLuaUFyoRNC6FkrzVjceg=="; // ����Ű
$nicepay->m_MID         = "nicepay00m";                         // �������̵�
$nicepay->m_Price       = "1004";                               // ������ǰ�ݾ�
$nicepay->m_BuyerEmail  = "happy@day.co.kr";                    // �����ڸ����ּ�
$nicepay->m_GoodsName   = "���̽�����";                         // ������ǰ��
$nicepay->m_BuyerName   = "���̽�";                             // �����ڸ� 
$nicepay->m_BuyerTel    = "01000000000";                        // �����ڿ���ó           
$nicepay->m_Moid        = "mnoid1234567890";                    // ��ǰ�ֹ���ȣ                     
$nicepay->m_EdiDate     = date("YmdHis");                       // �ŷ� ��¥
$goodsCnt               = "1";                                  // ������ǰ����
$nicepay->requestProcess();

/*
*******************************************************
* <�ؽ���ȣȭ> (�������� ������)
* SHA-256 �ؽ���ȣȭ�� �ŷ� �������� �������� ����Դϴ�. 
*******************************************************
*/ 
$ediDate = date("YmdHis");
$hashString = bin2hex(hash('sha256', $nicepay->m_EdiDate.$nicepay->m_MID.$nicepay->m_Price.$nicepay->m_MerchantKey, true));

?>
<!DOCTYPE html>
<html>
<head>
<title>NICEPAY PAY REQUEST(EUC-KR)</title>
<meta charset="euc-kr">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi" />
<link rel="stylesheet" type="text/css" href="./css/import.css"/>
<script src="https://web.nicepay.co.kr/v3/webstd/js/nicepay-2.0.js" type="text/javascript"></script>
<script type="text/javascript">
//����â ���� ��û�� ����˴ϴ�.
function nicepayStart(){
    goPay(document.payForm);
}

//���� ���� ��û�� ����˴ϴ�. <<'nicepaySubmit()' �̸� ���� �Ұ���>>
function nicepaySubmit(){
    document.payForm.submit();
}

//����â ���� �Լ� <<'nicepayClose()' �̸� ���� �Ұ���>>
function nicepayClose(){
    alert("������ ��� �Ǿ����ϴ�");
}
</script>
</head>
<body>
<form name="payForm" method="post" action="payResult.php">
    <div class="payfin_area">
      <div class="top">NICEPAY PAY REQUEST(EUC-KR)</div>
      <div class="conwrap">
        <div class="con">
          <div class="tabletypea">
            <table>
              <colgroup><col width="30%" /><col width="*" /></colgroup>
              <tr>
                <th><span>���� ����</span></th>
                <td>
                  <select name="PayMethod">
                    <option value="CARD">�ſ�ī��</option>
                    <option value="BANK">������ü</option>
                    <option value="CELLPHONE">�޴�������</option>
                    <option value="VBANK">�������</option>
                  </select>
                </td>
              </tr>            
              <tr>
                <th><span>���� ��ǰ��</span></th>
                <td><input type="text" name="GoodsName" value="<?php echo($nicepay->m_GoodsName);?>"></td>
              </tr>			  
              <tr>
                <th><span>���� ��ǰ����</span></th>
                <td><input type="text" name="GoodsCnt" value="<?=$goodsCnt?>"></td>
              </tr>
              <tr>
                <th><span>���� ��ǰ�ݾ�</span></th>
                <td><input type="text" name="Amt" value="<?php echo($nicepay->m_Price);?>"></td>
              </tr>	  
              <tr>
                <th><span>�����ڸ�</span></th>
                <td><input type="text" name="BuyerName" value="<?php echo($nicepay->m_BuyerName);?>"></td>
              </tr>	  
              <tr>
                <th><span>������ ����ó</span></th>
                <td><input type="text" name="BuyerTel" value="<?php echo($nicepay->m_BuyerTel);?>"></td>
              </tr>    
              <tr>
                <th><span>��ǰ �ֹ���ȣ</span></th>
                <td><input type="text" name="Moid" value="<?php echo($nicepay->m_Moid);?>"></td>
              </tr>
              <tr>
                <th><span>���� ���̵�</span></th>
                <td><input type="text" name="MID" value="<?php echo($nicepay->m_MID);?>"></td>
              </tr>              
              
              <!-- IP -->
              <input type="hidden" name="UserIP" value="<?php echo($nicepay->m_UserIp);?>"/>                    <!-- ȸ�����IP -->
              
              <!-- �ɼ� -->
              <input type="hidden" name="VbankExpDate" value="<?php echo($nicepay->m_VBankExpDate); ?>"/>       <!-- ��������Աݸ����� -->
              <input type="hidden" name="BuyerEmail" value="<?php echo($nicepay->m_BuyerEmail); ?>"/>           <!-- ������ �̸��� -->
              <input type="hidden" name="TransType" value="0"/>                                                 <!-- �Ϲ�(0)/����ũ��(1) ���� �Ķ���� --> 
              <input type="hidden" name="GoodsCl" value="1"/>                                                   <!--�ǹ�(1) ������(0) -->
             
              <!-- ���� �Ұ��� -->
              <input type="hidden" name="EdiDate" value="<?php echo($nicepay->m_EdiDate); ?>"/>                 <!-- ���� �����Ͻ� -->
              <input type="hidden" name="EncryptData" value="<?php echo($hashString); ?>"/>                     <!-- �ؽ���	-->
              <input type="hidden" name="TrKey" value=""/>                                                      <!-- �ʵ常 �ʿ� -->
            </table>
          </div>
        </div>
        <div class="btngroup">
          <a href="#" class="btn_blue" onClick="nicepayStart();">�� û</a>
        </div>
      </div>
    </div>
</form>
</body>
</html>