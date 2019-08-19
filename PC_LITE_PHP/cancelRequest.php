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
                <th><span>�ŷ����̵�</span></th>
                <td><input type="text" name="TID" value=""></td>
              </tr>	              
              <tr>
                <th><span>��� �ݾ�</span></th>
                <td><input type="text" name="CancelAmt" value=""></td>
              </tr>
              <tr>
                <th><span>��� ����</span></th>
                <td><input type="text" name="CancelMsg" value="�� ��û"></td>
              </tr>           
              <tr>
                <th><span>��� �н�����</span></th>
                <td><input type="password" name="CancelPwd" value="123456"></td>
              </tr> 
              <tr>
                <th><span>�κ���� ����</span></th>
                <td>
                  <select name="PartialCancelCode">
                    <option value="0">��ü ���</option>
                    <option value="1">�κ� ���</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <p>* ��� ��û�� ����� ��� ���� �Է� �ϼ���.</p>
        <p>* �ſ�ī�����, ������ü, ������¸� �κ����/�κ�ȯ���� �����մϴ�.</p>
        <div class="btngroup">
          <a href="#" class="btn_blue" onClick="goCancel();">�� û</a>
        </div>
      </div>
    </div>
</form>
</body>
</html>
