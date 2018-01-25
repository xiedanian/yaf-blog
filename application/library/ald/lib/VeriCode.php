<?php

class Ald_Lib_VeriCode {
    const CLUSTER_NAME = 'common';
    const KEY_SEPARATOR = ':';
    protected $prefix = 'ald';
    protected $key = 'vericode';

    public function __construct(){
        $this->objRedis =  Ald_Redis::getInstance(self::CLUSTER_NAME);
    }

    /**
     * 创建key
     * @return string
     */
    protected function buildKey(){
        $name = func_get_args();
        if(!empty($name)){
            $name = self::KEY_SEPARATOR . implode(self::KEY_SEPARATOR, $name);
        }else{
            $name = '';
        }
        return sprintf('%s%s%s%s', $this->prefix, APP_NAME, $this->key, $name);
    }

    /**
     * 发送验证码
     * @param $key
     * @param $code
     * @param $expire
     * @return mixed
     */
    public function send($phone, $expire = 600, $tag = ''){
        $code = rand(1000,9999);
        $key = $this->buildKey($phone, $tag);
        $ret1 = $this->objRedis->setex($key, $expire, $code);
        if($ret1 == false)
        {
            throw  new Ald_Exception_SysWarning(Ald_Const_Errno::SMS_FAILED);
        }
        $sms = new Ald_Lib_Sms();
        $ret = $sms -> send($phone, array('code' => $code));
        if($ret == false)
        {
            throw  new Ald_Exception_SysWarning(Ald_Const_Errno::SMS_FAILED);
        }
        return true;
    }

    /**
     * 获取验证码
     * @param $phone
     * @param string $tag
     * @return mixed
     */
    public function get($phone, $tag = ''){
        $key = $this->buildKey($phone, $tag);
        return $this->objRedis->get($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function destroy($phone, $tag = ''){
        $key = $this->buildKey($phone, $tag);
        return $this->objRedis->del($key);
    }

} 