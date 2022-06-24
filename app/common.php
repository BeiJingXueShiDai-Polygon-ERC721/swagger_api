<?php
// 应用公共文件
function curl_post($url, $data = '', $headers = array(), $agent = '')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    //curl_setopt($ch, CURLOPT)
    $file_contents = curl_exec($ch);
    curl_close($ch);
    if ($file_contents === false) {
        throw new \Exception('CURL错误，错误代码：' . curl_errno($ch));
    }
    //这里解析
    return $file_contents;
}

/**
 * http get
 * @param $durl
 * @param array $headers
 * @return bool|string
 */
function curl_get($durl, $headers = array())
{
    // 初始化
    $curl = curl_init();
    // 设置url路径
    curl_setopt($curl, CURLOPT_URL, $durl);
    // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
    // 添加头信息
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    // CURLINFO_HEADER_OUT选项可以拿到请求头信息
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    // 不验证SSL
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    // 执行
    $data = curl_exec($curl);
    // 打印请求头信息
//        echo curl_getinfo($curl, CURLINFO_HEADER_OUT);
    // 关闭连接
    curl_close($curl);
    // 返回数据
    return $data;
}
