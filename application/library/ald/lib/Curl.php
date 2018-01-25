<?php

class Ald_Lib_Curl{

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const LOG_TYPE = 'http';

    public static function request($serviceName, $uri, $method = self::METHOD_GET, $data = '', $headers = null){
        $configFile = DS . 'http' . DS . $serviceName . '.ini';
        $config = Ald_Config::getSysConfig($configFile, YAF_ENVIRON);
        if(!isset($config['servers']) || empty($config['servers']) || !is_array($config['servers'])){
            Ald_Log::warning(sprintf('%s: empty config, serviceName[%s]', __METHOD__, $serviceName));
            return false;
        }
        $_data = $data;
        if(is_array($data)){
            $_data = http_build_query($_data);
        }
        if(self::METHOD_GET === $method && !empty($_data)){
            if(false !== strpos($uri, '?')){
                $uri .= trim($uri, '&') . '&' . $_data;
            }else{
                $uri .= '?' . $_data;
            }
        }
        $servers = $config['servers'];
        shuffle($servers);
        $ch = curl_init();
        $retried = -1;
        $retry = 0;
        do{
            $retried++;
            $server = array_pop($servers);
            $url = $server['host'] . ':' . $server['port'] . $uri;
            $timeout = isset($server['timeout']) ? $server['timeout'] : 1;
            if(isset($server['retry'])){
                $retry = $server['retry'];
            }
            if(self::METHOD_POST === $method){
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            if(!empty($headers)){
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $ret = curl_exec($ch);
            $info = curl_getinfo($ch);
            $logStr = sprintf('service[%s] retry[%s/%s] host[%s] port[%s] timeout[%s] uri[%s] method[%s] req[%s] ret[%s] header[%s] info[%s]', 
                $serviceName, $retried, $retry, $server['host'], $server['port'], $timeout, $uri, $method, 
                is_scalar($data) ? $data : json_encode($data), is_scalar($ret) ? $ret : json_encode($ret), 
                is_scalar($headers) ? $headers : json_encode($headers),
                is_scalar($info) ? $info : json_encode($info));
            Ald_Log::write(Ald_Log::LEVEL_NOTICE, $logStr, false, self::LOG_TYPE);
            if(!isset($info['http_code']) || '200' != $info['http_code']){
                Ald_Log::write(Ald_Log::LEVEL_WARNING, $logStr, false, self::LOG_TYPE);
                continue;
            }
            return $ret;
        }while($retried < $retry);
        return false;
    }

    public static function fetch($url, $method = self::METHOD_GET, $data = '', $headers = null, $timeout = 8){
        $ch = curl_init();
        if(self::METHOD_GET === $method && !empty($data)){
            $_data = $data;
            if(is_array($data)){
                $_data = http_build_query($_data);
            }
            if(false !== strpos($url, '?')){
                $url .= trim($url, '&') . '&' . $_data;
            }else{
                $url .= '?' . $_data;
            }
        }else if(self::METHOD_POST === $method){
            curl_setopt($ch, CURLOPT_POST, 1);
            if(is_array($data)){
                $data = http_build_query($data);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        $info = curl_getinfo($ch);
        Ald_Log::notice(sprintf("%s:url[%s] method[%s] req[%s] res[%s] info[%s]", __METHOD__, $url, $method, is_array($data) ? json_encode($data) : $data, $ret, json_encode($info)));
        if('200' != $info['http_code']){
            Ald_Log::warning(sprintf("%s:curl failed!", __METHOD__));
        }
        curl_close($ch);
        return $ret;
    }
}