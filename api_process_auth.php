<?php




$certFile = "/etc/ssl/certs/GRX-0001192168.ACC1914.Test.AppleCare.chain.pem";
$keyFile = "/home/sknb2b/dep_test_key/privatekey.key";
$actualUrl = "https://api-applecareconnect-ept.apple.com/enroll-service/1.0/check-transaction-status";
$requestXml = "";
$caFile = "/etc/ssl/certs/ca-certificates.crt";

// $ch = curl_init(); 
// curl_setopt($ch, CURLOPT_URL, $actualUrl); 
// //curl_setopt($ch, CURLOPT_VERBOSE, 1); 
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); 
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); 
// curl_setopt($ch, CURLOPT_FAILONERROR, 1); 
// curl_setopt($ch, CURLOPT_SSLCERT, $certFile); 
// curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM'); 
// curl_setopt($ch, CURLOPT_SSLKEY, $keyFile); 
// curl_setopt($ch, CURLOPT_CAINFO, $caFile); 
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
// //curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXml);
// $ret = curl_exec($ch);

// var_dump($ret);


$ch = curl_init($actualUrl);
curl_setopt($ch, CURLOPT_URL, $actualUrl);
curl_setopt($ch, CURLOPT_SSLKEY, $keyFile);
curl_setopt($ch, CURLOPT_CAINFO, $caFile);
curl_setopt($ch, CURLOPT_SSLCERT, $certFile);
$ret = curl_exec($ch);

var_dump($ret);



/*

// curl -v --cert /etc/ssl/certs/GRX-0001192168.ACC1914.Test.AppleCare.chain.pem  --key ./privatekey.key https://api-applecareconnect-ept.apple.com/enroll-service/1.0/check-transaction-status
// curl -v --cert /etc/ssl/certs/GRX-0001192168.ACC1914.Test.AppleCare.chain.pem  --key ./privatekey.key https://api-applecareconnect-ept.apple.com/enroll-service/1.0/check-transaction-status 
// --cacert /etc/ssl/certs/ca-certificates.crt


$data = "var1=value1&var2=value2&...";
$url = "https://www.somesite.com/page";


$keyFile = "/home/sknb2b/dep_test_key/privatekey.key";
$caFile = "/etc/ssl/certs/ca-certificates.crt";
$certFile = "/etc/ssl/certs/GRX-0001192168.ACC1914.Test.AppleCare.chain.pem";
$certPass = "";
$actualUrl = "https://api-applecareconnect-ept.apple.com/enroll-service/1.0/check-transaction-status";

// Initialise cURL
$ch = curl_init($actualUrl);

// The -d option is equivalent to CURLOPT_POSTFIELDS. But...
// PHP's libcurl interface does not implement the -G flag - instead you would
// append $data to $url like this:
$actualUrl = $url.'?'.$data;
curl_setopt($ch, CURLOPT_URL, $actualUrl);

// The -v flag only makes sense at the command line, but it can be enabled
// with CURLOPT_VERBOSE - in this case the information will be written to
// STDERR, or the file specified by CURLOPT_STDERR. I will ignore this for
// now, but if you would like a demonstration let me know.

// The --key option - If your key file has a password, you will need to set
// this with CURLOPT_SSLKEYPASSWD
curl_setopt($ch, CURLOPT_SSLKEY, $keyFile);

// The --cacert option
curl_setopt($ch, CURLOPT_CAINFO, $caFile);

// The --cert option
curl_setopt($ch, CURLOPT_SSLCERT, $certFile);
// curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $certPass);


//   Now we should get an identical request to the one created by your command
//   line string, let's have a look at some of the other options you set...

// CURLOPT_HEADER is disabled by default, there's no need for this unless you
// enabled it earlier
//curl_setopt($ch, CURLOPT_HEADER, 0);

// Your command line string forces a GET request with the -G option, are you
// trying to POST or GET?
//curl_setopt($ch, CURLOPT_POST, true);

// We don't need body data with a GET request
//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

// Since we've gone to all the trouble of supplying CS information, we might
// as well validate it!
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);






	
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode( $ret );
*/
?>
