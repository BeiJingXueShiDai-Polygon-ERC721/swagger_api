<?php

namespace app\util;

use BitWasp\Bitcoin\Address\PayToPubKeyHashAddress;
use BitWasp\Bitcoin\Crypto\Random\Random;
use BitWasp\Bitcoin\Key\Factory\HierarchicalKeyFactory;
use BitWasp\Bitcoin\Key\Factory\PrivateKeyFactory;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39Mnemonic;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39SeedGenerator;
use BitWasp\Bitcoin\Mnemonic\MnemonicFactory;
use kornrunner\Keccak;
use Sop\CryptoEncoding\PEM;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Web3p\EthereumUtil\Util;

class Binance
{
    public function genMnemonicWord()
    {

        // Bip39
        $random = new Random();
        // 生成随机数(initial entropy)
        $entropy = $random->bytes(Bip39Mnemonic::MIN_ENTROPY_BYTE_LEN);
        $bip39 = MnemonicFactory::bip39();
        // 通过随机数生成助记词
        $mnemonic = $bip39->entropyToMnemonic($entropy);

        $return['mnemonic'] = $mnemonic;

        //echo "mnemonic: " . $mnemonic.PHP_EOL.PHP_EOL;// 助记词

        $seedGenerator = new Bip39SeedGenerator();
        // 通过助记词生成种子，传入可选加密串'hello'
        $seed = $seedGenerator->getSeed($mnemonic);
        //echo "seed: " . $seed->getHex() . PHP_EOL;
        $return['seed'] = $seed->getHex();
        $hdFactory = new HierarchicalKeyFactory();
        $master = $hdFactory->fromEntropy($seed);

        $util = new Util();
        // 设置路径account
        $hardened = $master->derivePath("44'/60'/0'/0/0");
        $return["public_key"] = $hardened->getPublicKey()->getHex();
        $return["private_key"] = $hardened->getPrivateKey()->getHex();
        $return["address"] = $util->publicKeyToAddress($util->privateKeyToPublicKey($hardened->getPrivateKey()->getHex()));
        return $return;
    }

    public function importByMnemonic($mnemonic)
    {
        $seedGenerator = new Bip39SeedGenerator();
        $seed = $seedGenerator->getSeed($mnemonic);
        //echo "seed: " . $seed->getHex() . PHP_EOL;
        $return['seed'] = $seed->getHex();
        $hdFactory = new HierarchicalKeyFactory();
        $master = $hdFactory->fromEntropy($seed);

        $util = new Util();
        // 设置路径account
        $hardened = $master->derivePath("44'/60'/0'/0/0");
        $return["public_key"] = $hardened->getPublicKey()->getHex();
        $return["private_key"] = $hardened->getPrivateKey()->getHex();
        $return["address"] = $util->publicKeyToAddress($util->privateKeyToPublicKey($hardened->getPrivateKey()->getHex()));
        return $return;
    }

    public function importByPrivatekey($privateKeyHex, &$err = '')
    {
//        $network = Bi::getNetwork();
        $Util = new Util();
        $publickey = $Util->privateKeyToPublicKey($privateKeyHex);
        $address = $Util->publicKeyToAddress($publickey);
        return $address;
    }

    public function importByPublicKey($publicKey, &$err = "")
    {

    }

    public function balance($address)
    {

    }

}