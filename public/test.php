<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Bangchak PRICE

$returned_content = get_data('https://crmmobile.bangchak.co.th/webservice/oil_price.aspx');

$xmlObj = simplexml_load_string($returned_content); // สร้างเป็น xml object
$arrXml = objectsIntoArray($xmlObj); // แปลงค่า xml object เป็นตัวแปร array ใน php

if ($arrXml['item'][0]['tomorrow'] != 0) {

    echo $arrXml['item'][0]['tomorrow'] . PHP_EOL;

    switch (true) {

        case ($arrXml['item'][0]['today'] > $arrXml['item'][0]['tomorrow']):
        case ($arrXml['item'][1]['today'] > $arrXml['item'][1]['tomorrow']):
        case ($arrXml['item'][2]['today'] > $arrXml['item'][2]['tomorrow']):
        case ($arrXml['item'][3]['today'] > $arrXml['item'][3]['tomorrow']):
        case ($arrXml['item'][4]['today'] > $arrXml['item'][4]['tomorrow']):
        case ($arrXml['item'][5]['today'] > $arrXml['item'][5]['tomorrow']):
        case ($arrXml['item'][6]['today'] > $arrXml['item'][6]['tomorrow']):
        case ($arrXml['item'][7]['today'] > $arrXml['item'][7]['tomorrow']):
        case ($arrXml['item'][8]['today'] > $arrXml['item'][8]['tomorrow']):

            $OilAltText = 'พรุ่งนี้น้ำมันลดราคา';
            break;

        case ($arrXml['item'][0]['today'] < $arrXml['item'][0]['tomorrow']):
        case ($arrXml['item'][1]['today'] < $arrXml['item'][1]['tomorrow']):
        case ($arrXml['item'][2]['today'] < $arrXml['item'][2]['tomorrow']):
        case ($arrXml['item'][3]['today'] < $arrXml['item'][3]['tomorrow']):
        case ($arrXml['item'][4]['today'] < $arrXml['item'][4]['tomorrow']):
        case ($arrXml['item'][5]['today'] < $arrXml['item'][5]['tomorrow']):
        case ($arrXml['item'][6]['today'] < $arrXml['item'][6]['tomorrow']):
        case ($arrXml['item'][7]['today'] < $arrXml['item'][7]['tomorrow']):
        case ($arrXml['item'][8]['today'] < $arrXml['item'][8]['tomorrow']):

            $OilAltText = 'พรุ่งนี้น้ำมันขึ้นราคา';

            break;

        default:

            $OilAltText = 'ราคาน้ำมันบางจาก';

            break;

            $oil_price = 'oilprice ' . $arrXml['item'][0]['today'] . ' ' . $arrXml['item'][0]['tomorrow'] . ' ' . $arrXml['item'][2]['today'] . ' ' . $arrXml['item'][2]['tomorrow'] . ' ' . $arrXml['item'][3]['today'] . ' ' . $arrXml['item'][3]['tomorrow'] . ' ' . $arrXml['item'][4]['today'] . ' ' . $arrXml['item'][4]['tomorrow'] . ' ' . $arrXml['item'][5]['today'] . ' ' . $arrXml['item'][5]['tomorrow'] . ' ' . $arrXml['item'][6]['today'] . ' ' . $arrXml['item'][6]['tomorrow'] . ' ' . $arrXml['item'][7]['today'] . ' ' . $arrXml['item'][7]['tomorrow'] . ' ' . $arrXml['item'][8]['today'] . ' ' . $arrXml['item'][8]['tomorrow'] . ' ' . $arrXml['item'][1]['today'] . ' ' . $arrXml['item'][1]['tomorrow'];
            $ClearOilPrice = getredis('oilprice_dialogflow');
            remRedis('oilprice_dialogflow', $ClearOilPrice[0]);
            saveRedis('oilprice_dialogflow', $oil_price);
    }

    $BangchakLastPrice = getRedis('BangchakPrice');
    $decoded = json_decode($BangchakLastPrice[0], true);

    switch (true) {

        case ($decoded['item'][0]['tomorrow'] !== $arrXml['item'][0]['tomorrow']):
        case ($decoded['item'][1]['tomorrow'] !== $arrXml['item'][1]['tomorrow']):
        case ($decoded['item'][2]['tomorrow'] !== $arrXml['item'][2]['tomorrow']):
        case ($decoded['item'][3]['tomorrow'] !== $arrXml['item'][3]['tomorrow']):
        case ($decoded['item'][4]['tomorrow'] !== $arrXml['item'][4]['tomorrow']):
        case ($decoded['item'][5]['tomorrow'] !== $arrXml['item'][5]['tomorrow']):
        case ($decoded['item'][6]['tomorrow'] !== $arrXml['item'][6]['tomorrow']):
        case ($decoded['item'][7]['tomorrow'] !== $arrXml['item'][7]['tomorrow']):
        case ($decoded['item'][8]['tomorrow'] !== $arrXml['item'][8]['tomorrow']):
		//case (True):

            if (!empty($decoded['item'][0]['tomorrow'])) {
                if (!empty($arrXml['item'][0]['tomorrow'])) {

                    $alertTo = getRedis('testAlert');
                    $inputJSON = file_get_contents('oil.json');
                    $jsonData = json_decode($inputJSON, true);

                    $jsonData['messages'][0]['altText'] = $OilAltText; //ราคาน้ำมันบางจากวันนี้
                    $jsonData['messages'][0]['contents']['header']['contents'][1]['text'] = $OilAltText; //ราคาน้ำมันบางจากวันนี้ั ขึ้นหรือลดราคา

                    $jsonData['messages'][0]['contents']['body']['contents'][3]['contents'][3]['contents'][0]['text'] = $arrXml['item'][0]['today']; //$HiPremiumDieselSToday
                    $jsonData['messages'][0]['contents']['body']['contents'][3]['contents'][5]['contents'][0]['text'] = $arrXml['item'][0]['tomorrow']; //$HiPremiumDieselSTomorrow
                    $HiPremiumDieselSDiff = $arrXml['item'][0]['tomorrow'] - $arrXml['item'][0]['today'];
                    if ($HiPremiumDieselSDiff > 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][3]['contents'][7]['contents'][0]['text'] = '+' . strval(number_format($HiPremiumDieselSDiff, 2, '.', ''));
                    } else if ($HiPremiumDieselSDiff == 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][3]['contents'][7]['contents'][0]['text'] = '-';
                    } else {
                        $jsonData['messages'][0]['contents']['body']['contents'][3]['contents'][7]['contents'][0]['text'] = strval(number_format($HiPremiumDieselSDiff, 2, '.', ''));
                    }

                    $jsonData['messages'][0]['contents']['body']['contents'][5]['contents'][3]['contents'][0]['text'] = $arrXml['item'][1]['today']; //$DieselSToday
                    $jsonData['messages'][0]['contents']['body']['contents'][5]['contents'][5]['contents'][0]['text'] = $arrXml['item'][1]['tomorrow']; //$DieselSTomorrow
                    $DieselSDiff = $arrXml['item'][1]['tomorrow'] - $arrXml['item'][1]['today'];
                    if ($DieselSDiff > 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][5]['contents'][7]['contents'][0]['text'] = '+' . strval(number_format($DieselSDiff, 2, '.', ''));
                    } else if ($DieselSDiff == 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][5]['contents'][7]['contents'][0]['text'] = '-';
                    } else {
                        $jsonData['messages'][0]['contents']['body']['contents'][5]['contents'][7]['contents'][0]['text'] = strval(number_format($DieselSDiff, 2, '.', ''));
                    }

                    $jsonData['messages'][0]['contents']['body']['contents'][7]['contents'][3]['contents'][0]['text'] = $arrXml['item'][2]['today']; //$HIDIESELSB10Today
                    $jsonData['messages'][0]['contents']['body']['contents'][7]['contents'][5]['contents'][0]['text'] = $arrXml['item'][2]['tomorrow']; //$HIDIESELSB10Tomorrow
                    $HIDIESELSB10Diff = $arrXml['item'][2]['tomorrow'] - $arrXml['item'][2]['today'];
                    if ($HIDIESELSB10Diff > 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][7]['contents'][7]['contents'][0]['text'] = '+' . strval(number_format($HIDIESELSB10Diff, 2, '.', ''));
                    } else if ($HIDIESELSB10Diff == 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][7]['contents'][7]['contents'][0]['text'] = '-';
                    } else {
                        $jsonData['messages'][0]['contents']['body']['contents'][7]['contents'][7]['contents'][0]['text'] = strval(number_format($HIDIESELSB10Diff, 2, '.', ''));
                    }

                    $jsonData['messages'][0]['contents']['body']['contents'][9]['contents'][3]['contents'][0]['text'] = $arrXml['item'][3]['today']; //$HIDIESELB20SToday
                    $jsonData['messages'][0]['contents']['body']['contents'][9]['contents'][5]['contents'][0]['text'] = $arrXml['item'][3]['tomorrow']; //$HIDIESELB20STomorrow
                    $HIDIESELB20SDiff = $arrXml['item'][3]['tomorrow'] - $arrXml['item'][3]['today'];
                    if ($HIDIESELB20SDiff > 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][9]['contents'][7]['contents'][0]['text'] = '+' . strval(number_format($HIDIESELB20SDiff, 2, '.', ''));
                    } else if ($HIDIESELB20SDiff == 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][9]['contents'][7]['contents'][0]['text'] = '-';
                    } else {
                        $jsonData['messages'][0]['contents']['body']['contents'][9]['contents'][7]['contents'][0]['text'] = strval(number_format($HIDIESELB20SDiff, 2, '.', ''));
                    }

                    $jsonData['messages'][0]['contents']['body']['contents'][11]['contents'][3]['contents'][0]['text'] = $arrXml['item'][4]['today']; //$GasoholE85SToday
                    $jsonData['messages'][0]['contents']['body']['contents'][11]['contents'][5]['contents'][0]['text'] = $arrXml['item'][4]['tomorrow']; //$GasoholE85STomorrow
                    $GasoholE85SDiff = $arrXml['item'][4]['tomorrow'] - $arrXml['item'][4]['today'];
                    if ($GasoholE85SDiff > 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][11]['contents'][7]['contents'][0]['text'] = '+' . strval(number_format($GasoholE85SDiff, 2, '.', ''));
                    } else if ($GasoholE85SDiff == 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][11]['contents'][7]['contents'][0]['text'] = '-';
                    } else {
                        $jsonData['messages'][0]['contents']['body']['contents'][11]['contents'][7]['contents'][0]['text'] = strval(number_format($GasoholE85SDiff, 2, '.', ''));
                    }

                    $jsonData['messages'][0]['contents']['body']['contents'][13]['contents'][3]['contents'][0]['text'] = $arrXml['item'][5]['today']; //$GasoholE20SToday
                    $jsonData['messages'][0]['contents']['body']['contents'][13]['contents'][5]['contents'][0]['text'] = $arrXml['item'][5]['tomorrow']; //$GasoholE20STomorrow
                    $GasoholE20SDiff = $arrXml['item'][5]['tomorrow'] - $arrXml['item'][5]['today'];
                    if ($GasoholE20SDiff > 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][13]['contents'][7]['contents'][0]['text'] = '+' . strval(number_format($GasoholE20SDiff, 2, '.', ''));
                    } else if ($GasoholE20SDiff == 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][13]['contents'][7]['contents'][0]['text'] = '-';
                    } else {
                        $jsonData['messages'][0]['contents']['body']['contents'][13]['contents'][7]['contents'][0]['text'] = strval(number_format($GasoholE20SDiff, 2, '.', ''));
                    }

                    $jsonData['messages'][0]['contents']['body']['contents'][15]['contents'][3]['contents'][0]['text'] = $arrXml['item'][6]['today']; //$Gasohol91SToday
                    $jsonData['messages'][0]['contents']['body']['contents'][15]['contents'][5]['contents'][0]['text'] = $arrXml['item'][6]['tomorrow']; //$Gasohol91STomorrow
                    $Gasohol91SDiff = $arrXml['item'][6]['tomorrow'] - $arrXml['item'][6]['today'];
                    if ($Gasohol91SDiff > 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][15]['contents'][7]['contents'][0]['text'] = '+' . strval(number_format($Gasohol91SDiff, 2, '.', ''));
                    } else if ($Gasohol91SDiff == 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][15]['contents'][7]['contents'][0]['text'] = '-';
                    } else {
                        $jsonData['messages'][0]['contents']['body']['contents'][15]['contents'][7]['contents'][0]['text'] = strval(number_format($Gasohol91SDiff, 2, '.', ''));
                    }

                    $jsonData['messages'][0]['contents']['body']['contents'][17]['contents'][3]['contents'][0]['text'] = $arrXml['item'][7]['today']; //$Gasohol95SToday
                    $jsonData['messages'][0]['contents']['body']['contents'][17]['contents'][5]['contents'][0]['text'] = $arrXml['item'][7]['tomorrow']; //$Gasohol95STomorrow
                    $Gasohol95SDiff = $arrXml['item'][7]['tomorrow'] - $arrXml['item'][7]['today'];
                    if ($Gasohol95SDiff > 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][17]['contents'][7]['contents'][0]['text'] = '+' . strval(number_format($Gasohol95SDiff, 2, '.', ''));
                    } else if ($Gasohol95SDiff == 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][17]['contents'][7]['contents'][0]['text'] = '-';
                    } else {
                        $jsonData['messages'][0]['contents']['body']['contents'][17]['contents'][7]['contents'][0]['text'] = strval(number_format($Gasohol95SDiff, 2, '.', ''));
                    }

                    $jsonData['messages'][0]['contents']['body']['contents'][19]['contents'][3]['contents'][0]['text'] = $arrXml['item'][8]['today']; //$NGVToday
                    $jsonData['messages'][0]['contents']['body']['contents'][19]['contents'][5]['contents'][0]['text'] = $arrXml['item'][8]['tomorrow']; //$NGVTomorrow
                    $NGVDiff = $arrXml['item'][8]['tomorrow'] - $arrXml['item'][8]['today'];
                    if ($NGVDiff > 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][19]['contents'][7]['contents'][0]['text'] = '+' . strval(number_format($NGVDiff, 2, '.', ''));
                    } else if ($NGVDiff == 0) {
                        $jsonData['messages'][0]['contents']['body']['contents'][19]['contents'][7]['contents'][0]['text'] = '-';
                    } else {
                        $jsonData['messages'][0]['contents']['body']['contents'][19]['contents'][7]['contents'][0]['text'] = strval(number_format($NGVDiff, 2, '.', ''));
                    }

                    foreach ($alertTo as $to) {
                        $jsonData['to'] = $to;
                        $outputJSON = json_encode($jsonData, JSON_PRETTY_PRINT);
                        $result = PushJson($outputJSON);
                        echo $result;
                    }

                }
            }
            break;

        default:
            // No Action
            break;

    }

    //Encode the array into a JSON string.
    $encodedString = json_encode($arrXml);

    //Save last price into Redis
    delRedis('BangchakPrice');
    saveRedis('BangchakPrice', $encodedString);

}

//Bangchak PRICE

//Redis Function

function getRedis($keyword)
{
    $path = 'vendor/predis/predis/autoload.php';
    require_once $path;
    $redis = new Predis\Client(getenv('REDIS_URL'));
    $value = $redis->lrange("response:$keyword", 0, -1);
    return $value;

}

function remRedis($keyword, $response)
{
    $path = 'vendor/predis/predis/autoload.php';
    require_once $path;
    $redis = new Predis\Client(getenv('REDIS_URL'));
    $value = $redis->lrem("response:$keyword", 0, $response);
    return $value;

}

function saveRedis($keyword, $response)
{
    $path = 'vendor/predis/predis/autoload.php';
    require_once $path;
    $redis = new Predis\Client(getenv('REDIS_URL'));
    $value = $redis->lpush("response:$keyword", $response);
    return $value;

}

function delRedis($keyword)
{
    $path = 'vendor/predis/predis/autoload.php';
    require_once $path;
    $redis = new Predis\Client(getenv('REDIS_URL'));
    $value = $redis->del("response:$keyword");
    return $value;

}

//Redis Function

function get_data($url)
{
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

function PushJson($inputJSON)
{
    $url = "https://api.line.me/v2/bot/message/push";
    $ContentLength = strlen($inputJSON);
    $XLineSignature = base64_encode(hash_hmac('sha256', $inputJSON, getenv('LineChannelSecret'), true));
    $headers['Content-Length'] = $ContentLength;
    $headers['X-Line-Signature'] = $XLineSignature;
    $headers['Host'] = "api.line.me";
    $headers['Authorization'] = 'Bearer ' . getenv('LineChannelToken');
    $headers['Content-Type'] = 'application/json;charset=UTF-8';

    $json_headers = array();
    foreach ($headers as $k => $v) {
        $json_headers[] = $k . ":" . $v;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $inputJSON);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $json_headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 0 | 2 ถ้าเว็บเรามี ssl สามารถเปลี่ยนเป้น 2
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // 0 | 1 ถ้าเว็บเรามี ssl สามารถเปลี่ยนเป้น 1
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
