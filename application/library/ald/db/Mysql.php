<?php

class Ald_Db_Mysql extends Ald_Db_Abstract implements Ald_Db_Interface{

    const LOG_TYPE = 'sql';
    protected $errno;
    protected $error;
    protected $conn;
    protected $cluster; //必需指定

    private function logSql($sql = ''){
        if(empty($sql)){
            $sql = $this->lastSql;
        }
        if(defined('LOG_SQL') && 1 == LOG_SQL){
            $logStr = sprintf('[SQL] app[%s] sql[%s]', APP_NAME, $sql);
            Ald_Log::write(Ald_Log::LEVEL_NOTICE, $logStr, false, self::LOG_TYPE);
        }
    }
    
    private function logWarning(){
        $logStr = sprintf('sql[%s] error[%s] errno[%s]', $this->lastSql, $this->error, $this->errno);
        Ald_Log::write(Ald_Log::LEVEL_WARNING, $logStr, false, self::LOG_TYPE);
    }

    public function getConnect($slave = Ald_Db_Connect::DB_SLAVE){
        if($slave == Ald_Db_Connect::DB_SLAVE){
            $this -> conn = Ald_Db_Connect::getSlavePDO($this -> cluster);
        }else{
            $this -> conn = Ald_Db_Connect::getMasterPDO($this -> cluster);
        }
        return $this -> conn;
    }

    public function select($fields, $conds, $append = '', $prefix = ''){
        if(!$this -> getConnect()){
            return false;
        }
        $query = $this -> lastSql = 'select ' . $prefix . ' ' . $this -> parseFields($fields) . ' from ' . $this -> table . $this -> parseConds($conds) . ' ' . $append;
        $statement = $this -> conn -> prepare($query);
        if(is_array($conds) && !empty($conds)){
            foreach($conds as $key => $val){
                if(is_numeric($key)) continue;
                $value = is_scalar($val) ? $val: $val[1];
                $statement -> bindValue(':cond_' . $key, $value);
                $this -> lastSql = str_replace(':cond_' . $key, is_string($value) ? "'$value'": $value, $this -> lastSql);
            }
        }
        $this->logSql();

        if($statement -> execute() === false){
            $this -> errno = $statement -> errorCode();
            $this -> error = ($tr = $statement -> errorInfo()) ? $tr[2] : '';
            $this->logWarning();
            return false;
        }
        return $statement -> fetchAll();
    }

    public function selectOne($fields, $conds, $append = '', $prefix = ''){
        if(!$this -> getConnect()){
            return false;
        }
        $query = $this -> lastSql = 'select ' . $prefix . ' ' . $this -> parseFields($fields) . ' from ' . $this -> table . $this -> parseConds($conds) . ' ' . $append;
        $statement = $this -> conn -> prepare($query);
        foreach($conds as $key => $val){
            if(is_numeric($key)) continue;
            $value = is_scalar($val) ? $val: $val[1];
            $statement -> bindValue(':cond_' . $key, $value);
            $this -> lastSql = str_replace(':cond_' . $key, is_string($value) ? "'$value'": $value, $this -> lastSql);
        }
        $this->logSql();
        if($statement -> execute() === false){
            $this -> errno = $statement -> errorCode();
            $this -> error = $statement -> errorInfo()[2];
            $this->logWarning();
            return false;
        }
        return $statement -> fetch();
    }

    public function selectCount($conds, $append = '', $prefix = ''){
        if(!$this -> getConnect()){
            return false;
        }
        $query = $this -> lastSql = 'select ' . $prefix . ' count(*) as total from ' . $this -> table . $this -> parseConds($conds) . ' ' . $append;
        $statement = $this -> conn -> prepare($query);
        foreach($conds as $key => $val){
            if(is_numeric($key)) continue;
            $value = is_scalar($val) ? $val: $val[1];
            $statement -> bindValue(':cond_' . $key, $value);
            $this -> lastSql = str_replace(':cond_' . $key, is_string($value) ? "'$value'": $value, $this -> lastSql);
        }
        $this->logSql();
        if($statement -> execute() === false){
            $this -> errno = $statement -> errorCode();
            $this -> error = ($tr = $statement -> errorInfo()) ? $tr[2] : '';
            $this->logWarning();
            return false;
        }
        $ret = $statement -> fetch();
        return $ret['total'];
    }

    public function insert($data){
        if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
            return false;
        }
        $conds_pre = '';
        foreach($data as $key => $value){
            $conds_pre .= "`$key`" . '=:' . $key . ',';
        }
        $conds_pre = rtrim($conds_pre, ',');
        $query = $this -> lastSql = 'insert into ' . $this -> table . ' set ' . $conds_pre;
        $statement = $this -> conn -> prepare($query);
        foreach($data as $key => $val){
            $statement -> bindValue(':' . $key, $val);
            $this -> lastSql = str_replace(':' . $key, is_string($val) ? "'$val'": $val , $this -> lastSql);
        }
        $this->logSql();
        if($statement -> execute() === false){
            $this -> errno = $statement -> errorCode();
            $this -> error = ($tr = $statement -> errorInfo()) ? $tr[2] : '';
            $this->logWarning();
            return false;
        }
        return $this -> conn -> lastInsertId();
    }

    public function multiInsert($fields, $data){
        if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
            return false;
        }
        $query = sprintf('insert into %s(%s) values ', $this -> table, implode(',', $fields));
        foreach($data as $da){
            $query .= '(\'' . implode('\',\'', $da) . '\'),';
        }
        $query = rtrim($query, ',');
        $this -> lastSql = $query;
        $this->logSql();
        if(($num = $this -> conn -> exec($query)) === false){
            $this -> errno = $this -> conn -> errorCode();
            $this -> error = ($tr = $this -> conn -> errorInfo()) ? $tr[2] : '';
            $this->logWarning();
            return false;
        }
        return $num;
    }

    public function update($data, $conds){
        if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
            return false;
        }
        $data_pre = '';
        foreach($data as $key2 => $tr2){
            if(is_numeric($key2)){
                $data_pre .= $tr2 . ',';;
            }else{
                $data_pre .= $key2 . '=:data_' . $key2 . ',';
            }
        }
        $data_pre = rtrim($data_pre, ',');
        $query = $this -> lastSql = 'update ' . $this -> table . ' set ' . $data_pre . $this -> parseConds($conds);
        $statement = $this -> conn -> prepare($query);
        foreach($data as $key2 => $val2){
            if(is_numeric($key2)) continue;
            $statement -> bindValue(':data_' . $key2, $val2);
            $this -> lastSql = str_replace(':data_' . $key2, is_string($val2) ? "'$val2'": $val2, $this -> lastSql);
        }
        foreach($conds as $key => $val){
            if(is_numeric($key)) continue;
            $value = is_scalar($val) ? $val: $val[1];
            $statement -> bindValue(':cond_' . $key, $value);
            $this -> lastSql = str_replace(':cond_' . $key, is_string($value) ? "'$value'": $value, $this -> lastSql);
        }
        $this->logSql();
        if($statement -> execute() === false){
            $this -> errno = $statement -> errorCode();
            $this -> error = ($tr = $statement -> errorInfo()) ? $tr[2] : '';
            $this->logWarning();
            return false;
        }
        return $statement -> rowCount();
    }

    public function delete($conds){
        if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
            return false;
        }
        $query = $this -> lastSql = 'delete from ' . $this -> table . $this -> parseConds($conds);
        $statement = $this -> conn -> prepare($query);
        foreach($conds as $key => $val){
            if(is_numeric($key)) continue;
            $value = is_scalar($val) ? $val: $val[1];
            $statement -> bindValue(':cond_' . $key, $value);
            $this -> lastSql = str_replace(':cond_' . $key, is_string($value) ? "'$value'": $value, $this -> lastSql);
        }
        $this->logSql();
        if($statement -> execute() === false){
            $this -> errno = $statement -> errorCode();
            $this -> error = ($tr = $statement -> errorInfo()) ? $tr[2] : '';
            $this->logWarning();
            return false;
        }
        return $statement -> rowCount();
    }

    public function query($sql){
        $sql = trim($sql);
        if(preg_match('/^(insert|update|delete)/', $sql)){
            if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
                return false;
            }
        }else{
            if(!$this -> getConnect()){
                return false;
            }
        }
        $this -> lastSql = $sql;
        $this->logSql();
        if(($statement = $this -> conn -> query($sql)) === false){
            $this -> errno = $this -> conn -> errorCode();
            $this -> error = ($tr = $statement -> errorInfo()) ? $tr[2] : '';
            $this->logWarning();
            return false;
        }
        return $statement -> fetchAll();
    }

    public function queryOne($sql){
        $sql = trim($sql);
        if(preg_match('/^(insert|update|delete)/', $sql)){
            if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
                return false;
            }
        }else{
            if(!$this -> getConnect()){
                return false;
            }
        }
        $this -> lastSql = $sql;
        $this->logSql();
        if(($statement = $this -> conn -> query($sql)) === false){
            $this -> errno = $this -> conn -> errorCode();
            $this -> error = ($tr = $statement -> errorInfo()) ? $tr[2] : '';;
            $this->logWarning();
            return false;
        }
        return $statement -> fetch();
    }

    public function exec($sql){
        $sql = trim($sql);
        if(preg_match('/^(insert|update|delete)/', $sql)){
            if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
                return false;
            }
        }else{
            if(!$this -> getConnect()){
                return false;
            }
        }
        $this -> lastSql = $sql;
        $this->logSql();
        if(($ret = $this -> conn -> exec($sql)) === false){
            $this -> errno = $this -> conn -> errorCode();
            $this -> error = ($tr = $statement -> errorInfo()) ? $tr[2] : '';;
            $this->logWarning();
            return false;
        }
        return $ret;
    }

    public function beginTransaction(){
        if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
            return false;
        }
        $this->logSql("begin");
        return $this -> conn -> beginTransaction();
    }

    public function inTransaction(){
        if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
            return false;
        }
        return $this -> conn -> inTransaction();
    }

    public function commit(){
        if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
            return false;
        }
        return $this -> conn -> commit();
    }

    public function rollBack(){
        if(!$this -> getConnect(Ald_Db_Connect::DB_MASTER)){
            return false;
        }
        return $this -> conn -> rollBack();
    }

    public function getLastErrno()
    {
        return $this -> errno;
    }

    public function getLastError()
    {
        return $this -> error;
    }

    public function getTable(){
        return $this->table;
    }

}