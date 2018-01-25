<?php

class Ald_Redis_Driver{

    const LINK_MASTER = 'm';
    const LINK_SLAVER = 's';
    private $linkPool;
    private $config;

    /**
     * $config = array(
     *  'timeout' => 5, //秒 支持小数
     *  'servers' => array(
     *      array(
     *          'host' => '127.0.0.1',
     *          'port' => 6379,
     *          'auth' => null,
     *          'type' => 'm', //master
     *      ),
     *      array(
     *          'host' => '127.0.0.1',
     *          'port' => 6379,
     *          'auth' => null,
     *          'type' => 's',
     *      ),
     *  ),
     * );
     */
    public function __construct($config){
        $this->config = $config;
    }

    public function getLink($linkType = self::LINK_MASTER){
        if(isset($this->linkPool[$linkType])){
            return $this->linkPool[$linkType];
        }
        if(!is_array($this->config) || empty($this->config)){
            throw new Exception('redis config required');
        }
        $servers = array();
        foreach($this->config['servers'] as $server){
            $server['timeout'] = $this->config['timeout'];
            if($server['type'] === $linkType){
                $servers[] = $server;
            }
        }
        if(empty($servers)){
            throw new Exception('no redis server found');
        }
        shuffle($servers);
        $link = new Redis();
        $linkSucc = false;
        while(!empty($servers)){
            $server = array_pop($servers);
            if(!$link->connect($server['host'], $server['port'], $server['timeout'])){
                Ald_Log::warning(sprintf('%s: redis connect faild, server[%s]', 
                    __METHOD__, json_encode($server)));
                continue;
            }
            if(isset($server['auth']) && !is_null($server['auth'])){
                if(!$link->auth($server['auth'])){
                    Ald_Log::warning(sprintf('%s: redis auth faild, server[%s]', 
                        __METHOD__, json_encode($server)));
                    continue;
                }
            }
            $linkSucc = true;
            break;
        }
        if($linkSucc){
            Ald_Log::notice(sprintf('%s: redis link success, server[%s]',
                __METHOD__, json_encode($server)));
            $this->linkPool[$linkType] = $link;
            return $this->linkPool[$linkType];
        }
        throw new Exception(sprintf('redis get link failed'));
    }

    public function __call($method, $params){
        try{
            $linkType = $this->getLinkType($method);
            $link = $this->getLink($linkType);
            $ret = call_user_func_array(array($link, $method), $params);
            return $ret;
        }catch(Exception $e){
            Ald_Log::warning(sprintf('%s: %s', __METHOD__, $e->getMessage()));
            return false;
        }
    }

    private function getLinkType($method){
        $slaveMethods = array(
            'get' => '',
            'mget' => '',
        );
        if(array_key_exists($method, $slaveMethods)){
            return self::LINK_SLAVER;
        }
        return self::LINK_MASTER;
    }
}
