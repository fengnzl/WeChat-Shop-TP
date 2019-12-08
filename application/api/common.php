<?php
/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */
function curl_get($url,&$http_code=0){
    //初始化curl
    $ch = curl_init();
    //设置请求的url
    curl_setopt($ch,CURLOPT_URL,$url);
    //设置获得的结果以字符串返回而不是输出
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //设置连接的等待的超时时间
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
    //设置数据传输的超时时间
    curl_setopt($ch,CURLOPT_TIMEOUT,500);
    //发起请求
    $file_contents = curl_exec($ch);
    //获取连接资源的最后一个http代码
    $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    //关闭请求
    curl_close($ch);
    //返回获取的资源数据
    return $file_contents;
}

/**
 * 获得随机字符串
 * @param $length
 * @return null|string
 */
function getRandomChars($length){
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;
    for($i=0;$i<$length;$i++){
        $str.= $strPol[rand(0,$max)];
    }

    return $str;
}