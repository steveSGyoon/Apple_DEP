<?php
header("Content-Type:text/html; charset=euc-kr;");
require("./lib/NicepayLite.php");
/*
*******************************************************
* <��� ��� ����>
* ����� ��� �ɼ��� ����� ȯ�濡 �µ��� �����ϼ���.
* �α� ���丮�� �� �����ϼ���.
*******************************************************
*/
$nicepay = new NicepayLite;
$nicepay->m_NicepayHome       = "c:\log";               // �α� ���丮 ����
$nicepay->m_ActionType        = "CLO";                  // ��� ��û ����
$nicepay->m_CancelAmt         = $CancelAmt;             // ��� �ݾ� ����
$nicepay->m_TID               = $TID;                   // ��� TID ����
$nicepay->m_CancelMsg         = $CancelMsg;             // ��� ����
$nicepay->m_PartialCancelCode = $PartialCancelCode;     // ��ü ���, �κ� ��� ���� ����
$nicepay->m_CancelPwd         = "123456";               // ��� ��й�ȣ ����
$nicepay->m_ssl               = "true";                 // �������� ����
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
              <th><span>�ŷ� ���̵�</span></th>
              <td><?php echo($nicepay->m_ResultData["TID"]);?></td>
            </tr>
            <tr>
              <th><span>���� ����</span></th>
              <td><?php echo($nicepay->m_ResultData["PayMethod"]);?></td>
            </tr>         
            <tr>
              <th><span>��� ����</span></th> <!-- (��Ҽ���: 2001, ��Ҽ���(LGU ������ü):2211) -->
              <td><?php echo('['.$nicepay->m_ResultData["ResultCode"].']'.$nicepay->m_ResultData["ResultMsg"]);?></td>
            </tr>
            <tr>
              <th><span>��� �ݾ�</span></th>
              <td><?php echo($nicepay->m_ResultData["CancelAmt"]);?></td>
            </tr>
            <tr>
              <th><span>�����</span></th>
              <td><?php echo($nicepay->m_ResultData["CancelDate"]);?></td>
            </tr>
            <tr>
              <th><span>��ҽð�</span></th>
              <td><?php echo($nicepay->m_ResultData["CancelTime"]);?></td>
            </tr>
          </table>
        </div>
      </div>
      <p>* ��Ұ� ������ ��쿡�� �ٽ� ���λ��·� ���� �� �� �����ϴ�.</p>
    </div>
  </div>
</body>
</html>