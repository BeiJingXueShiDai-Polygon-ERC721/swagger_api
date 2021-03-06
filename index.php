<?php

use Pinata\Pinata;

include 'vendor/autoload.php';

/**
 * API Key: 696ef958686f9652c122
 * API Secret: cd9d69147aab5156f857703b93ab8c1a25e14e98c053df8b3aa7ae933a4a1257
 * JWT: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySW5mb3JtYXRpb24iOnsiaWQiOiIwODBmYjUxMC0wODk5LTRiOTItYTA4MS03MzE4ZTk4MTE2NTkiLCJlbWFpbCI6ImJpdG1hbi5ldGhAb3V0bG9vay5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwicGluX3BvbGljeSI6eyJyZWdpb25zIjpbeyJpZCI6IkZSQTEiLCJkZXNpcmVkUmVwbGljYXRpb25Db3VudCI6MX0seyJpZCI6Ik5ZQzEiLCJkZXNpcmVkUmVwbGljYXRpb25Db3VudCI6MX1dLCJ2ZXJzaW9uIjoxfSwibWZhX2VuYWJsZWQiOmZhbHNlLCJzdGF0dXMiOiJBQ1RJVkUifSwiYXV0aGVudGljYXRpb25UeXBlIjoic2NvcGVkS2V5Iiwic2NvcGVkS2V5S2V5IjoiNjk2ZWY5NTg2ODZmOTY1MmMxMjIiLCJzY29wZWRLZXlTZWNyZXQiOiJjZDlkNjkxNDdhYWI1MTU2Zjg1NzcwM2I5M2FiOGMxYTI1ZTE0ZTk4YzA1M2RmOGIzYWE3YWU5MzNhNGExMjU3IiwiaWF0IjoxNjU2MzU2ODgzfQ.TGATuiq64S-nmlh5McBjnznu9ElI3y4Vvb-C6C8FokY
 */

$apiKey = '696ef958686f9652c122';
$secretKey = 'cd9d69147aab5156f857703b93ab8c1a25e14e98c053df8b3aa7ae933a4a1257';
$openApiUrl = "http://polygon_erc721_api.btcsoft.org/NFTCN/openApi";

$method = $_POST['method'];
$pinata = new Pinata($apiKey, $secretKey);

header('Content-Type:application/json; charset=utf-8');
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:*');
header("Access-Control-Request-Headers:*");

//$hash = $pinata->pinJSONToIPFS(['test' => 'moo2']);
// $hash = $pinata->removePinFromIPFS('QmT7Ce9iW9P8ATw2y5ZSYdqhrKEwZify6DPUT9DJVXYutB');
// $hash = $pinata->removePinFromIPFS('QmT7Ce9iW9P8ATw2y5ZSYdqhrKEwZify6DPUT9DJVXYutB');
// $hash = $pinata->pinHashToIPFS('QmT7Ce9iW9P8ATw2y5ZSYdqhrKEwZify6DPUT9DJVXYutB');

if ($method == 'health') {
    $result = curlPost($openApiUrl . "/healthCheck");
    $result = json_decode($result, true);

    $return = [
        'api' => 'running',
        'backend' => 'closed',
        'jsonrpc' => 'closed'
    ];

    if ($result['status'] == 1) {
        $return['backend'] = $result['data']['backend'];
        $return['jsonrpc'] = $result['data']['jsonrpc'];
    }
    jsuccess($return);
}


if ($method == 'create') {
    debug_log("create nft ?????????");
    debug_log("1. ???????????????");
    debug_log($_POST);
    $fileFullPath = $_POST['file'];
    debug_log("2. ??????IPFS");
    $hash = $pinata->pinFileToIPFS($fileFullPath);
    debug_log($hash);
    if ($hash['IpfsHash'] != null) {
        $image = "https://gateway.pinata.cloud/ipfs/" . $hash['IpfsHash'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        $post = [
            'image' => $image,
            'name' => $name,
            'description' => $description
        ];
        debug_log("3. ??????metadata??????");
        $result = curlPost($openApiUrl . "/mint", $post);
        debug_log($result);
        exit($result);
    } else {
        jerror("ERROR: please check pinata account");
    }
}

if ($method == 'queryNFT') {
    $tokenId = $_POST['tokenId'];
    $post = ['tokenId' => $tokenId];
    exit(curlPost($openApiUrl . "/queryNFT", $post));
}

if ($method == 'burn') {
    $tokenId = $_POST['tokenId'];
    $post = ['tokenId' => $tokenId];
    exit(curlPost($openApiUrl . "/burn", $post));
}

if ($method == 'transfer') {
    $tokenId = $_POST['tokenId'];
    $to = $_POST['receive'];
    $post = ['tokenId' => $tokenId, 'to' => $to];
    exit(curlPost($openApiUrl . "/transfer", $post));
}

if ($method == 'baseinfo') {
    $post = [];
    exit(curlPost($openApiUrl . "/baseinfo", $post));
}


/*

  oooooooo8   ooooooo   oooo     oooo oooo     oooo   ooooooo   oooo   oooo      ooooooooooo ooooo  oooo oooo   oooo   oooooooo8 ooooooooooo ooooo   ooooooo   oooo   oooo
o888     88 o888   888o  8888o   888   8888o   888  o888   888o  8888o  88        888    88   888    88   8888o  88  o888     88 88  888  88  888  o888   888o  8888o  88
888         888     888  88 888o8 88   88 888o8 88  888     888  88 888o88        888ooo8     888    88   88 888o88  888             888      888  888     888  88 888o88
888o     oo 888o   o888  88  888  88   88  888  88  888o   o888  88   8888        888         888    88   88   8888  888o     oo     888      888  888o   o888  88   8888
 888oooo88    88ooo88   o88o  8  o88o o88o  8  o88o   88ooo88   o88o    88       o888o         888oo88   o88o    88   888oooo88     o888o    o888o   88ooo88   o88o    88


 */

function curlPost($url, $data = '', $headers = array(), $agent = '')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https?????? ??????????????????hosts
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    if ($file_contents === false) {
        throw new \Exception('CURL????????????????????????' . curl_errno($ch));
    }
    return $file_contents;
}

/**
 * http get
 * @param $durl
 * @param array $headers
 * @return bool|string
 */
function curlGet($durl, $headers = array())
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $durl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data = curl_exec($curl);
    curl_close($curl);
    // ????????????
    return $data;
}

function jsuccess($data = '', $msg = '')
{
    json(1, $msg, $data);
    exit;
}

function jerror($msg = '', $ret = 10000, $data = '')
{
    json($ret, $msg, $data);
    exit;
}

function json($ret = 0, $msg = '', $data = '')
{
    header('Content-Type:application/json; charset=utf-8');
    header('Access-Control-Allow-Methods:*');
    header('Access-Control-Allow-Headers:*');
    header("Access-Control-Request-Headers:*");
    $return = array(
        'status' => $ret,
        'info' => $msg,
        'data' => $data
    );
    echo json_encode($return);
    exit;
}

function debug_log($val)
{
    if (is_array($val)) {
        $val = var_export($val, true);
    }
    file_put_contents('debug_log.txt', date('Y-m-d H:i:s', time()) . ' debug : ' . $val . "\r\n", FILE_APPEND);
}

