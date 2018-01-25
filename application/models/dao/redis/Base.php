<?php

class Dao_Redis_BaseModel{

    const KEY_SEPARATOR = ':';
    const CLUSTER_NAME = 'common';
    protected $objRedis;
    protected $prefix = 'ald:zhaopin:';
    protected $key = 'zhaopin';

    public function __construct(){
        $this->objRedis =  Ald_Redis::getInstance(self::CLUSTER_NAME);
    }

    /**
     * 组装redisKEY,支持不定数量的scalar参数，如：
     * $prefix = 'wm:openapi';
     * $key = 'test';
     * 'wm:openapi:test:baidu' = buildKey('baidu');
     * @params scalar参数
     * @return string 组装后的redisKEY
     */
    protected function buildKey(){
        $name = func_get_args();
        if(!empty($name)){
            $name = self::KEY_SEPARATOR . implode(self::KEY_SEPARATOR, $name);  
        }else{
            $name = '';
        }
        return sprintf('%s%s%s', $this->prefix, $this->key, $name);
    }
}
