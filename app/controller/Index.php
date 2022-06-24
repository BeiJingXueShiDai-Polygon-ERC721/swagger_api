<?php

namespace app\controller;

use app\ApiController;
use app\util\Binance;
use app\util\LocalRpc;

class Index extends ApiController
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V' . \think\facade\App::version() . '<br/><span style="font-size:30px;">14载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    /**
     * 生成助记词
     */
    public function genMnemonicWord()
    {
        $Binance = new Binance();
        $data = $Binance->genMnemonicWord();
        $user = [];
        $user['wallet_eth_mnemonic'] = $data['mnemonic'];
        $user['wallet_eth_seed'] = $data['seed'];
        $user['wallet_eth_public_key'] = $data['public_key'];
        $user['wallet_eth_private_key'] = $data['private_key'];
        $user['wallet_eth_address'] = $data['address'];
        $this->jsuccess($user);
    }

    public function importMnemonicWord($mnemonic)
    {
        $Binance = new Binance();
        $data = $Binance->importByMnemonic($mnemonic);
        $user = [];
        $user['wallet_eth_mnemonic'] = $mnemonic;
        $user['wallet_eth_seed'] = $data['seed'];
        $user['wallet_eth_public_key'] = $data['public_key'];
        $user['wallet_eth_private_key'] = $data['private_key'];
        $user['wallet_eth_address'] = $data['address'];
        $this->jsuccess($user);
    }

    public function importPrivateKey($privatekey)
    {
        $Binance = new Binance();
        //init
        $user = [
            'wallet_eth_mnemonic' => '',
            'wallet_eth_seed' => '',
            'wallet_eth_public_key'=>''
        ];
        $user['wallet_eth_private_key'] = $privatekey;

        $res = $Binance->importByPrivatekey($privatekey,$err);
        if (!$res) {
            $this->jerror($err);
        }
        $user['wallet_eth_address'] = $res;
        $this->jsuccess($user);
    }

    public function importPublicKey($publicKey)
    {
        $Binance = new Binance();

    }

    public function transfer($from,$to,$privatekey,$amount){
        $LocalRpc = new LocalRpc();
        $result = $LocalRpc->transfer($from, $to, $privatekey, $amount,$err);
        if (!$result) {
            $this->jerror($err);
        }else{
            $this->jsuccess($result);
        }
    }

    public function balance($address)
    {
        $localRpc = new LocalRpc();
        $balanceBnb = $localRpc->balance($address);
//        $balanceLy = $localRpc->balance($address, "0xCf4f6a66972e7442f19C50250B0b24bc14Dc070E");
        $balanceLy = $localRpc->balance($address, "0x2f3C6D2FEa752942856F8e34AF26Ae76F4bBF53c");
        $return = [
            'bnb' => $balanceBnb,
            'ly'=>$balanceLy
        ];
        $this->jsuccess($return);
    }
}
