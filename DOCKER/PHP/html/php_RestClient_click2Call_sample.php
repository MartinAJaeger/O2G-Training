<html>
<head>
<title>A basic Click-to-call example</title>
<?php

function clickAndCall() {
	$server = $_POST['Server'];
	$serverUrl = "http://" . $server . "/api/rest/";
	$userlogin = $_POST['Userlogin'];
	$password =$_POST['Password'];
	$calling = $_POST['Calling'];
	$callee = $_POST['Called'];
	$verbose = fopen('php://temp', 'w+');

	############################################
	####  Authentication with user/password ####
	############################################

	$ch_auth = curl_init();
		echo "Begin authent on $serverUrl !\n";

	// receive server response ...
	curl_setopt($ch_auth, CURLOPT_RETURNTRANSFER, true);
	#curl_setopt($ch_auth, CURLOPT_VERBOSE, true);
	#curl_setopt($ch_auth, CURLOPT_STDERR, $verbose);

	curl_setopt($ch_auth, CURLOPT_USERPWD, $userlogin . ":" . $password);
	curl_setopt($ch_auth, CURLOPT_URL, $serverUrl . "authenticate?version=1.0");
	// save cookie in tmp file
	curl_setopt($ch_auth, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');

	$result = curl_exec ($ch_auth);
	if ($result === FALSE) {
		printf("cUrl error on authent(#%d): %s<br>\n", curl_errno($handle),
			   htmlspecialchars(curl_error($handle)));
	}
	echo "End authent !\n";



	#############################
	####  Create session	 ####
	#############################

	echo "Begin create session !\n";
	$ch_session = curl_init();
	curl_setopt($ch_session, CURLOPT_VERBOSE, true);
	curl_setopt($ch_session, CURLOPT_STDERR, $verbose);

	curl_setopt($ch_session, CURLOPT_URL, $serverUrl . "1.0/sessions");
	curl_setopt($ch_session, CURLOPT_POST, 1);
	// set cookie
	curl_setopt($ch_session, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
	// set mandatory applicationName
	$appName = array("applicationName" => "testPHP");                                                                    
	$appName_string = json_encode($appName);
	curl_setopt($ch_session, CURLOPT_POSTFIELDS, $appName_string);                                                                  
	curl_setopt($ch_session, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($appName_string))                                                                       
	);                                                                                   

	$result = curl_exec ($ch_session);
	if ($result === FALSE) {
		printf("cUrl error on create session(#%d): %s<br>\n", curl_errno($handle),
			   htmlspecialchars(curl_error($handle)));
	}
	echo "End create session !\n";


	#####################
	####  make call	 ####
	#####################

	echo "Begin make call !\n";
	$ch_mkcall = curl_init();
	curl_setopt($ch_mkcall, CURLOPT_VERBOSE, true);
	curl_setopt($ch_mkcall, CURLOPT_STDERR, $verbose);

	curl_setopt($ch_mkcall, CURLOPT_URL, $serverUrl . "1.0/telephony/calls");
	curl_setopt($ch_mkcall, CURLOPT_POST, 1);
	// set cookie
	curl_setopt($ch_mkcall, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
	// set mandatory  makeCall parameters
	$makeCallReqParams = array("deviceId" => $calling, "callee" => $callee, "autoAnswer" => true);                                                                    
	$makeCallReqParams_string = json_encode($makeCallReqParams);
	curl_setopt($ch_mkcall, CURLOPT_POSTFIELDS, $makeCallReqParams_string);                                                                  
	curl_setopt($ch_mkcall, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($makeCallReqParams_string))                                                                       
	);                                                                                   

	$result = curl_exec ($ch_mkcall);
	if ($result === FALSE) {
		printf("cUrl error on make  call(#%d): %s<br>\n", curl_errno($handle),
			   htmlspecialchars(curl_error($handle)));
	}
	echo "End make  call !\n";

	#############################
	####  Close session	 ####
	#############################

	echo "Begin close session !\n";

	curl_setopt($ch_session, CURLOPT_CUSTOMREQUEST, "DELETE");
	// set cookie
	curl_setopt($ch_session, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');

	$result = curl_exec ($ch_session);
	if ($result === FALSE) {
		printf("cUrl error on delete session(#%d): %s<br>\n", curl_errno($handle),
			   htmlspecialchars(curl_error($handle)));
	}
	echo "End close session !\n";


rewind($verbose);
$verboseLog = stream_get_contents($verbose);

echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";


curl_close ($ch_auth);
curl_close ($ch_session);
curl_close ($ch_mkcall);

}
?>
</head>
<body>
<h2> This is an example of click-to-call on a PHP server</h2>
<ul>
<li> preliminary, enter the server address, the user login, its password, its calling device, the destination number </li>
<li> then click to authenticate the user, to start the session (thanks to the returned cookie)and make the call </li>
</ul>

<FORM METHOD ="POST" ACTION = "php_RestClient_click2Call_sample.php">

    <P>
    <LABEL for="serverIP">Server address </LABEL>
              <INPUT type="text" name ="Server" value = "vm-roxel2.bstlabrd.fr.alcatel-lucent.com"> <BR>
    <LABEL for="userlogin">User loginName </LABEL>
              <INPUT type="text" name ="Userlogin" value = "oxe70121">  <BR>
    <LABEL for="password">User password </LABEL>
              <INPUT type="password" name="Password" value = "0000"><BR>
    <LABEL for="calling">Calling device number </LABEL>
              <INPUT type="text" name="Calling" value = "70121"><BR>
    <LABEL for="called">Called </LABEL>
              <INPUT type="text" name="Called" value = "70120"><BR>
			  
	<INPUT TYPE = "Submit" Name = "ClickAndCall" VALUE = "clickAndCall">

    
    </P>
 </FORM>
         <?php
           if($_SERVER['REQUEST_METHOD']=='POST')
           {
               clickAndCall();
           } 
        ?>
  
</body>
</html>
