<?php

abstract class Ald_Validate_Abstract {
    protected $data;
    protected $rules;
    protected $res;
    protected $error;

    function __construct($data, $rules){
        $this -> data = $data;
        $this -> res = $data;
        $this -> _init_rule($rules);
    }

    /**
     * 校验开始
     * @return bool
     */
    public function validate(){
        if(is_array($this->rules)){
            foreach($this -> rules as $func => $param){
                $func = '_' . $func;
                if(!method_exists($this, $func)){
                    trigger_error('validate method not found:' . __CLASS__ . '|' . $func, E_USER_WARNING);
                    continue;
                }
                if(false === call_user_func_array(array($this, $func), array($param))){
                    $this -> error = __CLASS__ . '::' . $func;
                    return false;
                }
            }
        }
        return $this -> res;
    }

    /**
     * 初始化规则
     * @param $rules
     */
    protected function _init_rule($rules){
        if(empty($rules) || !is_array($rules)){
            return;
        }
        $rules[] = 'init';
        foreach($rules as $rule){
            $res = preg_match('/^(\w+)(\[(.*)\])?$/', $rule, $matches);
            if(false === $res){
                trigger_error("wrong validate rules[{$rule}]", E_USER_WARNING);
                continue;
            }
            if(!isset($matches[3]) || is_null($matches[3])){
                $matches[3] = '';
            }
            if(is_null($this -> data)){ //字段为null，只保留required和default
                if(isset($matches[1]) && ($matches[1] == 'required' || $matches[1] == 'default')){
                    $this->rules[$matches[1]] = $matches[3];
                }
            }else{
                $this->rules[$matches[1]] = $matches[3];
            }
        }
    }

    /**
     * 不允许不传
     * @return bool
     */
    protected function _required(){
        if(is_null($this -> data)){
            return false;
        }
        return true;
    }

    /**
     * 没传，就置默认值
     * @param string $param
     */
    protected function _default($param = ''){
        if(is_null($this -> data)){
            $this -> res = $param;
        }
        return true;
    }

    /**
     * 传了，但是为空
     * @param string $param
     * @return bool
     */
    protected function _notempty($param = ''){
        if(is_null($this->data)){
            return false;
        }
        if(is_scalar($this->data)){
            if(0 === strlen($this->data)){
                return false;
            }
            return true;
        }
        if(empty($this->data)){
            return false;
        }
        return true;
    }

    /**
     * 防注入
     * @param $strValue
     * @return bool
     */
    protected static function isSqlInject($strValue){
        $SQL_INJECT = 'select\b.+?benchmark\b|\bchar\b\s?\(|\\/\\*.?\\*\\/|\b(load_file|information_schema|name_const|extractvalue|exec)\b|\bwaitfor\b.+?\bdelay\b|\b(or|select)\b.+?sleep|\b(or)\b.+?.+(=|>|<|\bin\b|\blike\b).+|union.+?select|update.+?set|insert\s+into.+?values|(select|delete).+?from|(create|alter|drop|truncate)\s+(table|database)|(updatexml|extractvalue).+?concat|case\s+when|group\s+by.+?having|select\b.+?select';
        if (is_array($strValue)) {
            $strValue = implode(' ', $strValue);
        }
        if (preg_match("/". $SQL_INJECT ."/is",$strValue) == 1 ) {
            return true;
        }
        return false;
    }

    /**
     * in_array
     */
    public function _inArr($param){
        $params = explode(',', $param);
        return in_array($this -> data, $params);
    }

    public function getError(){
        return $this -> error;
    }

    protected abstract function _init();
}