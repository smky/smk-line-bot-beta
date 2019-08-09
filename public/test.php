<?php 
$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);


//function


function push_text($to,$Message){
	
		$path = __DIR__ . '/../vendor/autoload.php';
		require_once $path;
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LINEBOT_CHANNEL_TOKEN'));
		$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('LINEBOT_CHANNEL_SECRET')]);
		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($Message);
		$response = $bot->pushMessage($to, $textMessageBuilder);
		echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
		//echo $response->getHTTPStatus();
		//echo $htmlHeader;
		
		while($stuff)	{
		echo $stuff;
						}  
		
}

function push_image($to,$url){
	
		$path = __DIR__ . '/../vendor/autoload.php';
		require_once $path;
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LINEBOT_CHANNEL_TOKEN'));
		$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('LINEBOT_CHANNEL_SECRET')]);
		$imageMessageBuilder = new LINE\LINEBot\MessageBuilder\ImageMessageBuilder($url,$url);
		$response = $bot->pushMessage($to, $imageMessageBuilder);
		echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

		echo $htmlHeader;
		while($stuff)	{
		echo $stuff;
						}  

}


function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
 
function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
     
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
     
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}

		
//		push_text('Uf9a10a3ad619a46dc309b4bbb748b5e1','Test Push'); //SMK
		

date_default_timezone_set('ASIA/Bangkok');
print date("l jS \of F Y h:i:s A") .PHP_EOL;
print date("j F Y") .PHP_EOL;

//Group Id: C1a08a6e5568e569516cdf3ff93f69a2b
//User Id: Uf9a10a3ad619a46dc309b4bbb748b5e1

?> 
