<?php
	$mailheaders = "From: support@skndep.com\r\n";
	$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: text/html; charset=UTF-8\r\n";
	$copyRightYear = date("Y");

	$bodytext = "
<!DOCTYPE html>
<html lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='x-apple-disable-message-reformatting'>
    <title>" . $emailHeadLine . "</title>
	<link href='http://www.sknedushop.co.kr/subComm/emailCSS.css' rel='stylesheet' type='text/css' />
</head>
<body width='100%' bgcolor='#ffffff' style='margin: 0; mso-line-height-rule: exactly;'>
    <center style='width: 100%; background: #ffffff; text-align: left;'>
        <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
            (Optional) This text will appear in the inbox preview, but not the email body. It can be used to supplement the email subject line or even summarize the email's contents. Extended text preheaders (~490 characters) seems like a better UX for anyone using a screenreader or voice-command apps like Siri to dictate the contents of an email. If this text is not included, email clients will automatically populate it using the text (including image alt text) at the start of the email's body.
        </div>
        <div style='max-width: 600px; margin: auto;' class='email-container'>
            <table role='presentation' cellspacing='0' cellpadding='0' border='0' align='center' width='100%' style='max-width: 600px;'>
                <tr>
                    <td style='padding: 20px 0; text-align: left'>
                        <h1>SKN DEP System</h1>
                    <hr>
                    </td>
                </tr>
            </table>
            <table role='presentation' cellspacing='0' cellpadding='0' border='0' align='center' width='100%' style='max-width: 600px;'>
                <tr>
                    <td bgcolor='#ffffff'>
                        <table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'>
                            <tr>
                                <td style='padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;'>
                                    <h1 style='margin: 0 0 10px 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #333333; font-weight: normal;'>" . 
                                    $emailHeadLine . "</h1>
                                    <p style='margin: 0;'>" . 
                                    	$emailContent . 
                                    	"</p>
                                </td>
                            </tr>
                            <!--
                            <tr>
                                <td style='padding: 0 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;'>
                                    <table role='presentation' cellspacing='0' cellpadding='0' border='0' align='center' style='margin: auto;'>
                                        <tr>
                                            <td style='border-radius: 3px; background: #222222; text-align: center;' class='button-td'>
                                                <a href='http://www.skndep.com' style='background: #222222; border: 15px solid #222222; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;' class='button-a'>
                                                    <span style='color:#ffffff;' class='button-link'>&nbsp;&nbsp;&nbsp;&nbsp;Nedushop으로 이동&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            -->
                            <tr>
                                <td style='padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #555555;'>
                                    <h2 style='margin: 0 0 10px 0; font-family: sans-serif; font-size: 18px; line-height: 125%; color: #333333; font-weight: bold;'></h2>
                                    <p style='margin: 0;'></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table role='presentation' cellspacing='0' cellpadding='0' border='0' align='center' width='100%' style='max-width: 680px; font-family: sans-serif; color: #888888; font-size: 12px; line-height: 140%;'>
                <tr>
                    <td style='padding: 40px 10px; width: 100%; font-family: sans-serif; font-size: 12px; line-height: 140%; text-align: center; color: #888888;' class='x-gmail-data-detectors'>
                        <webversion style='color: #cccccc; text-decoration: underline; font-weight: bold;'><font color='#cccccc'>support@skndep.com</font></webversion>
                        <!--
                        <br>
                        <font color='#cccccc'>(주)이노타임<br>서울특별시 금천구 가산동 319 호서대벤처타워 B109<br>070-4736-3785</font>
                        -->
                        <br><br>
                    </td>
                </tr>
            </table>
        </div>
        <table role='presentation' bgcolor='#ff9933' cellspacing='0' cellpadding='0' border='0' align='center' width='100%'>
            <tr>
                <td valign='top' align='center'>
                    <div style='max-width: 600px; margin: auto;' class='email-container'>
                        <table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'>
                            <tr>
                                <td style='padding: 40px; text-align: left; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #ffffff;'>
                                    <p style='margin: 0;'>본 메일은 회신이 되지 않는 메일입니다. 문의 사항이 있으신 경우 Nedushop 사이트를 이용해 주세요.</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>";

	mail("stevesgyoon@gmail.com", $emailHeadLine, $bodytext, $mailheaders);
	//mail($targetEmail, $emailHeadLine, $bodytext, $mailheaders);
?>

