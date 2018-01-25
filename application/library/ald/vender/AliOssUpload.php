<?php
/**
 * @name 获取阿里云服务器上传密钥
 * return 签名结果
 */
class Ald_Vender_AliOssUpload{
    private $ossClient;
    private $bucket;

    public function __construct(){
        Ald_Vender_Alioss_AliossAutoload::init();
        $arrConfig = Ald_Config::getAppConfig('alioss');
        if(empty($arrConfig)){
            throw new Ald_Exception_SysWarning(Ald_Const_Errno::ERROR, 'can not find config file alioss.ini');
        }
        if(!isset($arrConfig['id']) || !isset($arrConfig['key']) || !isset($arrConfig['endpoint']) || !isset($arrConfig['bucket'])){
            throw new Ald_Exception_SysWarning(Ald_Const_Errno::ERROR, 'can not find option id|key|endpoint|bucket in alioss.ini');
        }
        $this -> bucket = $arrConfig['bucket'];
        $this -> ossClient = new \OSS\OssClient($arrConfig['id'], $arrConfig['key'], $arrConfig['endpoint']);
    }

    public function upload($filePath, $object){
        try{
            $this -> ossClient -> uploadFile($this -> bucket, $object, $filePath);
        } catch(OssException $e) {
            return __FUNCTION__ . ": FAILED " . $e->getMessage();
        }
        return true;
    }

}