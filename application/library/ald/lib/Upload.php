<?php

class Ald_Lib_Upload{

    private $baseDir;
    private $subDir;
    private $baseUrl = '/static';
    private $lastError = '';

    private $allowTypes = array(
        'image/png',
        'image/jpeg',
        'image/gif',
    );

    public function __construct(){
        if(defined('UPLOAD_DIR')){
            $this->baseDir = UPLOAD_DIR;
        }
        $this->subDir = date('Ymd');
    }
    
    public function setAllowTypes($types){
        $this->allowTypes = $types;
    }
    
    public function addAllowTypes($types){
        if(is_scalar($types)){
            if(!in_array($types, $this->allowTypes)){
                $this->allowTypes[] = $types;
            }
        }else{
            $this->allowTypes += $types;
        }
    }
    
    public function setBaseDir($baseDir){
        $this->baseDir = $baseDir;
    }
    
    public function setSubDir($subDir){
        $this->subDir = $subDir;
    }
    
    public function getUploadDir($createIfNotExist = false){
        $uploadDir = $this->baseDir . DS . $this->subDir;
        if($createIfNotExist && !is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }
        return $uploadDir;
    }
    
    public function getUploadUrl(){
        return $this->baseUrl . DS . $this->subDir;
    }
    
    public function getLastError(){
        return $this->lastError;
    }

    public function upload($formName, $baseDir = null, $baseUrl = null){
        if($baseDir){
            $this->baseDir = $baseDir;
        }
        if($baseUrl){
            $this->baseUrl = $baseUrl;            
        }
        if(!$_FILES) return false;
        $rawFiles = $_FILES[$formName];
        $upFiles = array();
        if(is_array($rawFiles['name'])){
            foreach($rawFiles['name'] as $k=>$v){
                if(!empty($rawFiles['error'][$k])){
                    $this->lastError = $rawFiles['error'][$k];
                    return false;
                }
                if(!in_array($rawFiles['type'][$k], $this->allowTypes)){
                    $this->lastError = 'file type not allowed: ' . $rawFiles['type'][$k];
                    return false;
                }
                $upFiles[] = array(
                    'name' => $v,
                    'type' => $rawFiles['type'][$k],
                    'tmp_name' => $rawFiles['tmp_name'][$k],
                    'error' => $rawFiles['error'][$k],
                    'size' => $rawFiles['size'][$k],
                );
            }
        }else{
            if(!empty($rawFiles['error'])){
                $this->lastError = $rawFiles['error'];
                return false;
            }
            $upFiles = array($rawFiles);
        }
        if(is_array($upFiles)){
            foreach($upFiles as $file){
                $uploadDir = $this->getUploadDir(true);
                $fileType = mime_content_type($file['tmp_name']);
                if(!in_array($fileType, $this->allowTypes)){
                    throw new Ald_Exception_AppWarning(Ald_Const_Errno::ERROR, '不允许的附件类型');
                }
                $destFilename = basename($file['tmp_name']) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                $dest = $uploadDir . DS . $destFilename;
                move_uploaded_file($file['tmp_name'], $dest);
                $one = array();
                $one['file_name'] = $file['name'];
                $one['file_type'] = $fileType;
                $one['file_size'] = $file['size'];
                $one['file_size_str'] = Ald_Lib_Util::fileSizeFormat($file['size']);
                $one['file_url'] = $this->getUploadUrl() . DS . $destFilename;
                $result[] = $one;
            }
        }
        return $result;
    }

    public function uploadOne($formName, $baseDir = null, $baseUrl = null){
        $ret = $this->upload($formName, $baseDir, $baseUrl);
        return isset($ret[0]) ? $ret[0] : false;
    }

    public function setBaseUrl($baseUrl){
        $this->baseUrl = $baseUrl;
    }
}