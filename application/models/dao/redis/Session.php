<?php

class Dao_Redis_SessionModel extends Dao_Redis_BaseModel{

    const SECRET_KEY = 'XrXr0G'; //可以通过修改这个强制所有用户下线
    protected $key = 'session';

    public function __construct($platform){
        parent::__construct();
        $this -> key .= $platform;
    }

    /**
     * 添加session
     * @param $user_id
     * @param $cookie
     * @return mixed
     */
    public function addSession($user_id, $cookie){
        $key = $this->buildKey(self::SECRET_KEY, $user_id);
        return $this->objRedis->hSet($key, $cookie, time());
    }

    /**
     * 获取session
     * @param $session_id
     * @return mixed
     */
    public function getSession($user_id, $cookie){
        $key = $this->buildKey(self::SECRET_KEY, $user_id);
        return $this->objRedis->hGet($key, $cookie);
    }

    /**
     * 获取某用户的所有session
     * @param $user_id
     * @return mixed
     */
    public function getAllSession($user_id){
        $key = $this->buildKey(self::SECRET_KEY, $user_id);
        return $this->objRedis->hGetAll($key);
    }

    /**
     * 删除session
     * @param $session_id
     * @return mixed
     */
    public function delSession($user_id, $cookie){
        $key = $this->buildKey(self::SECRET_KEY, $user_id);
        return $this->objRedis->hDel($key, $cookie);
    }
}
