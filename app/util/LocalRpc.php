<?php

namespace app\util;

class LocalRpc
{
    private $url = "http://localhost:13000";

    public function balance($address, $contract = 'eth')
    {
        //http://localhost:13000/rpc/balance/token
        $requestUrl = $this->url . "/rpc/balance/token";
        $requestData = [
            'contract' => $contract,
            'address' => $address,
        ];
        $result = curl_post($requestUrl, $requestData);

        if ($result == null) {
            return 0;
        } else {
            $result = json_decode($result, true);
            if (intval($result['code']) === 0) {
                return floatval($result['data']);
            } else {
                return 0;
            }
        }
    }

    public function transfer($from, $to, $privatekey, $amount, &$errMsg = "")
    {
        $requestUrl = $this->url . "/rpc/transfer";
        $requestData = [
            'from' => $from,
            'to' => $to,
            'privatekey' => $privatekey,
            'amount' => $amount,
        ];
        $result = curl_post($requestUrl, $requestData);
        if ($result == null) {
            $errMsg = 'rpc service error';
            return false;
        } else {
            $result = json_decode($result, true);
            if (intval($result['code']) === 0) {
                return $result['data'];
            } else {
                $errMsg = $result['message'];
                return false;
            }
        }
    }
}
