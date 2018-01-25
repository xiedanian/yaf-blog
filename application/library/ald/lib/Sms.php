<?php

class Ald_Lib_Sms
{
    //ak
    private $ak = 'e33aab69f75240d2971947c01769d130';
    //sk
    private $sk = '4fc85e19dd834c3f8af1a62ca92950bc';
    //请求方法
    private $httpMethod = 'POST';
    //短信服务host
    private $host = 'sms.bj.baidubce.com';
    //短信服务uri
    private $smsUri = '/v1/message';
    //授权版本
    private $authVersion = 'bce-auth-v1';
    //有效时间
    private $expire = 3600;
    //中间变量
    private $signingKey;
    //中间变量-参与签名的header
    private $authHeaders = array();
    //短信模板ID
    private $smsTplId = 'smsTpl:45701fe4c9c642d79e439051d845e6ad';
    //上次返回值
    private $lastResult;
    
    /**
     * 短信发送入口
     * @param $phones 目标手机号码，支持字符串和数组形式传入
     * @param $vars 短信内容变量数组
     */
    public function send($phones, $vars){
        //发送短信时，貌似必须将时区设置成UTC
        //为不影响全局，此处记录当前时区，再发送完成后恢复设置
        $oldTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        if(is_scalar($phones)){
            $phones = array($phones);
        }
        //不是字符串类型居然还报错，坑太多
        if(is_array($vars) && !empty($vars)){
            foreach($vars as $k=>$v){
                $vars[$k] = strval($v);
            }
        }
        $timestamp = time();
        $data = array(
            'templateId' => $this->smsTplId,
            'receiver' => $phones,
            'contentVar' => json_encode($vars),
        );
        //print_r($data);exit;
        $data = json_encode($data);
        //echo $data;exit;
        $params = array();
        $headers = array(
            'User-Agent' => 'PHP-CURL',
            'Content-Type' => 'application/json',
            'Host' => $this->host,
            'x-bce-date' => $this->formatTime($timestamp),
        );
        $sign = $this->genSign($this->host, $this->smsUri, $this->httpMethod, $params, $headers, $timestamp);
        $headers['Authorization'] = $sign;
        $headers['x-bce-content-sha256'] = $this->signingKey;
    
        $headerArr = array();
        foreach($headers as $k=>$v){
            $headerArr[] = $k . ':' . $v;
        }
        $url = $this->host . $this->smsUri;
        $ret = Ald_Lib_Curl::fetch($url, $this->httpMethod, $data, $headerArr);
        date_default_timezone_set($oldTimezone); //恢复时区设置
        return $ret;
    }
    
    public function getLastResult(){
        return $this->lastResult;
    }
    
    /**
     * 鉴权认证
     * @url:https://bce.baidu.com/doc/Reference/AuthenticationMechanism.html
     */
    private function genSign($host, $uri, $method, $params, $headers, $timestamp){
        $method = strtoupper($method);
        $canonicalRequest = implode("\n", array(
            $method,
            $uri,
            $this->buildQueryString($params),
            $this->buildHeaders($headers),
        ));
    
        $authStringPrefix = implode('/', array(
            $this->authVersion,
            $this->ak,
            $this->formatTime($timestamp),
            $this->expire,
        ));
        $signingKey = hash_hmac('sha256', $authStringPrefix, $this->sk);
        $this->signingKey = $signingKey;
        $signatrue = hash_hmac('SHA256', $canonicalRequest, $signingKey);
        $authorization = implode('/', array(
            $this->authVersion,
            $this->ak,
            $this->formatTime($timestamp),
            $this->expire,
            implode(';', $this->authHeaders),
            $signatrue,
        ));
        return $authorization;
    }
    
    private function formatTime($timestamp){
        return date('Y-m-d\TH:i:s\Z', $timestamp);
    }
    
    private function curl($url, $method = 'GET', $data = null, $headers = array()){
        $method = strtoupper($method);
        if(is_array($data)){
            $data = http_build_query($data);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if('POST' === $method){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $ret = curl_exec($ch);
        $this->lastResult = $ret;
        $info = curl_getinfo($ch);
        curl_close($ch);
        $arr = json_decode($ret, true);
        if(200 === $info['http_code'] && isset($arr['sendStat']['failList']) && empty($arr['sendStat']['failList'])){
            return $arr['messageId'];
        }
        return false;
    }
    
    private function buildQueryString($params){
        if(empty($params)){
            return "";
        }
        $temp = array();
        foreach($params as $k=>$v){
            $temp[urlencode($k)] = urlencode($v);
        }
        asort($temp);
        return http_build_query($temp);
    }
    
    private function buildHeaders($headers){
        $list = array(
            'host',
            'content-length',
            'content-type',
            'content-md5',
        );
        $temp = array();
        foreach($headers as $k=>$v){
            $k = strtolower(trim($k));
            if(!in_array($k, $list) && 0 !== strpos($k, 'x-bce-')){
                continue;
            }
            $this->authHeaders[] = $k;
            $temp[] = urlencode($k) . ':' . urlencode(trim($v));
        }
        asort($temp);
        $ret = implode("\n", $temp);
        return $ret;
    }
}
?>
