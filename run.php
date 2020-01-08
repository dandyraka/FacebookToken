<?php
require __DIR__.'/vendor/autoload.php';

define('FB_USERNAME', 'CHANGE_THIS');
define('FB_PASSWORD', 'CHANGE_THIS');
define('API_KEY', '3e7c78e35a76a9299309885393b02d97');
define('SIG', 'c1e620fa708a1d5696fb991c1bde5662');

function sign_creator(&$data){
	$sig = "";
	foreach($data as $key => $value){
		$sig .= $key . "=" . $value;
	}
	$sig .= SIG;
	$sig = md5($sig);
	return $sig;
}

$data = array(
	"api_key"               => API_KEY,
	"email"                 => FB_USERNAME,
	"format"                => "JSON",
	"locale"                => "id_ID",
	"method"                => "auth.login",
	"password"              => FB_PASSWORD,
	"return_ssl_resources"  => "0",
	"v"                     => "1.0"
);

$fb = "https://api.facebook.com/restserver.php?api_key=" . API_KEY . "&email=" . FB_USERNAME;
$fb .= "&format=JSON&locale=id_ID&method=auth.login&password=" . FB_PASSWORD;
$fb .= "&return_ssl_resources=0&v=1.0&sig=" . sign_creator($data);

$client     = new GuzzleHttp\Client([
    'verify'    => false,
    'headers'   => [
        'Origin'        => 'https://facebook.com',
        'User-Agent'    => 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1',
    ],
]);
$send       = $client->get($fb);
$response   = $send->getBody()->getContents();
$json       = json_decode($response);
if(!empty($json->access_token)){
    echo "Token : " . $json->access_token;
} else {
    echo $json->error_msg;
    $error_json = json_decode($json->error_data);
    echo "\n".$error_json->error_message;
}
