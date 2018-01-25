<?php

abstract class Ald_Db_Abstract{

    protected $lastSql;
    protected $conn;
    protected $table;
    protected $lastError;
    protected $lastErrno;

    protected function parseFields($fields){
        if(is_string($fields)){
            return $fields;
        }
        if(is_array($fields)){
            return implode(', ', $fields);
        }
        Ald_Log::warning(sprintf('%s: unsupported db fileds type, fields[%s]',
            __METHOD__, json_encode($fields)));
        return false;
    }

    protected function parseConds($conds){
        if(empty($conds)){
            return '';
        }
        $conds_pre = array();
        if(is_array($conds) && !empty($conds)){
            foreach($conds as $key => $tr){
                if(is_numeric($key)){
                    $conds_pre[] = $tr;
                }else{
                    if(is_scalar($tr)){
                        $conds_pre[] = $key . '=:cond_' . $key;
                    }elseif(is_array($tr)){
                        $conds_pre[] = $key . $tr[0] . ':cond_' . $key;
                    }else{
                        Ald_Log::warning(sprintf('%s: unsupported db conds type, fields[%s], value[%s]',
                            __METHOD__, $tr[0], $tr[1]));
                    }
                }
            }
        }
        $conds_pre = ' where ' . implode(' and ', $conds_pre) . ' ';
        return $conds_pre;
    }
    
    public function getLastSql(){
        return $this->lastSql;
    }

    public function escape($val){
        return $val;
    }
}