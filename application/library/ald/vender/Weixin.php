<?php


class Ald_Vender_Weixin{

    const REDIS_CLUSTER_NAME = 'common';
    protected $objRedis;
    protected $AppID;
    protected $AppSecret;
    protected $noncestr;
    protected $jsapi_ticket_url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=ACCESS_TOKEN&type=jsapi';
    protected $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET';

    public function __construct()
    {
        $arrConfig = Ald_Config::getAppConfig('weixin');
        if(empty($arrConfig)){
            throw new Ald_Exception_SysWarning(Ald_Const_Errno::ERROR, 'can not find config file weixin.ini');
        }
        $this -> AppID = $arrConfig['AppID'];
        $this -> AppSecret = $arrConfig['AppSecret'];
        $this -> noncestr = $arrConfig['noncestr'];
        $this -> objRedis =  Ald_Redis::getInstance(self::REDIS_CLUSTER_NAME);
    }

    public function getAccessToken(){
        $redisKey = 'weixin_access_token';
        $access_token= $this -> objRedis -> get($redisKey);
        if(!empty($access_token)){
            return $access_token;
        }

        $i = 1;
        $this -> access_token_url = str_replace(array('APPID', 'APPSECRET'), array($this -> AppID, $this -> AppSecret), $this -> access_token_url);
        while($i <= 2){
            $ret = Ald_Lib_Curl::fetch($this -> access_token_url, Ald_Lib_Curl::METHOD_GET);
            $data = json_decode($ret, true);
            if(isset($data['access_token'])){
                $access_token = $data['access_token'];
                $expires_in = $data['expires_in'];
                $ret = $this -> objRedis -> setex($redisKey, $expires_in - 300, $access_token);
                if($ret == false){
                    Ald_Log::warning(sprintf("%s set weixin access_token to redis faild", __METHOD__));
                }
                return $access_token;
            }
            $i++;
        }
        if($i > 2){
            throw new Ald_Exception_SysWarning(Ald_Const_Errno::ERROR, 'get weixin access_token failed!');
        }
    }

    public function getTicket(){
        $redisKey = 'weixin_ticket';
        $jsapi_ticket= $this -> objRedis -> get($redisKey);
        if(!empty($jsapi_ticket)){
            return $jsapi_ticket;
        }

        $i = 1;
        $this -> jsapi_ticket_url = str_replace('ACCESS_TOKEN', $this -> getAccessToken(), $this -> jsapi_ticket_url);
        while($i <= 2){
            $ret = Ald_Lib_Curl::fetch($this -> jsapi_ticket_url, Ald_Lib_Curl::METHOD_GET);
            $data = json_decode($ret, true);
            if(isset($data['ticket'])){
                $jsapi_ticket= $data['ticket'];
                $expires_in = $data['expires_in'];
                $ret = $this -> objRedis -> setex($redisKey, $expires_in - 300, $jsapi_ticket);
                if($ret == false){
                    Ald_Log::warning(sprintf("%s set weixin access_token to redis faild", __METHOD__));
                }
                return $jsapi_ticket;
            }
            $i++;
        }
        if($i > 2){
            throw new Ald_Exception_SysWarning(Ald_Const_Errno::ERROR, 'get weixin ticket failed!');
        }
    }

    public function genSign($url){
        $cur_time = time();
        $data = array(
            'noncestr' => $this -> noncestr,
            'jsapi_ticket' => $this -> getTicket(),
            'timestamp' => time(),
            'url' => $url,
        );
        ksort($data);
        $signStr = '';
        foreach($data as $key => $da){
            $signStr .= $key . '=' . $da . '&';
        }
        $signStr = rtrim($signStr, '&');
        $signature = sha1($signStr);
        return array(
            'appId' => $this -> AppID,
            'timestamp' => $cur_time,
            'nonceStr' => $this -> noncestr,
            'signature' => $signature,
        );
    }

}
