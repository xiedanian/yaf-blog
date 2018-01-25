<?php

class Visk_Tree{

    private $indentStr = '----';

    private $idField = 'id';
    
    private $platTree = [];

    public function __construct(){

    }
    
    public function setIdField($field){
        $this->idField = $field;
    }

    public function buildTree($data, $pid = 0){
        $children = [];
        if(is_array($data)){
            foreach($data as $v){
                if($v['pid'] == $pid){
                    $v['sub_list'] = $this->buildTree($data, $v[$this->idField]);
                    $children[] = $v;
                }
            }
        }
        return $children;
    }

    public function buildPlatTree($data, $pid = 0, $level = -1){
        if(is_array($data)){
            $level++;
            foreach($data as $v){
                if($v['pid'] == $pid){
                    $v['level'] = $level;
                    $v['prefix'] = str_repeat($this->indentStr, $level);
                    $this->platTree[] = $v;
                    $sub = $this->buildPlatTree($data, $v[$this->idField], $level);
                    if(!empty($sub)){
                        $this->platTree += $sub;
                    }
                }
            }
        }
        return $this->platTree;
    }
}