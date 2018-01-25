<?php

interface Ald_Db_Interface{

    public function select($fields, $conds, $append = '', $prefix = '');
    public function selectOne($fields, $conds, $append = '', $prefix = '');
    public function selectCount($conds, $append = '', $prefix = '');
    public function insert($data);
    public function update($conds, $data);
    public function query($sql);
    public function delete($data);
    public function escape($val);
    public function getLastSql();
    public function beginTransaction();
    public function commit();
    public function rollBack();
    public function getLastError();
    public function getLastErrno();
    public function getTable();

}