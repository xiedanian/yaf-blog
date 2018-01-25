<?php

class Service_Admin_Data_AlbumModel {
    private $objDaoAlbum;

    function __construct(){
        $this -> objDaoAlbum = new Dao_Db_AlbumModel();
    }

    public function Add($data)
    {
        $data['create_time'] = $data['update_time'] = time();
        return $this -> objDaoAlbum -> insert($data);
    }

    public  function getAlbumListCount(){
        $conds = array(
            'album_id >=1',
        );
        return $this -> objDaoAlbum -> selectCount($conds);
    }

    public function getAlbumList($page_num, $page_size){
        $fields = array(
            'album_id',
            'uid',
            'icon',
            'title',
            'intro',
            'create_time',
            'update_time',
        );

        $conds = array(
            'album_id >=1',
            'valid' => 1
        );
        $appends = sprintf("order by create_time desc limit %s,%s", ($page_num - 1) * $page_size, $page_size);
        return $this -> objDaoAlbum -> select($fields, $conds, $appends);
        echo $this -> objDaoAlbum ->getLastSql();
    }

    public function getAlbumDetail($album_id){
        $fields = array(
            'album_id',
            'uid',
            'icon',
            'title',
            'intro',
            'create_time',
            'update_time',
        );

        $conds = array(
            'album_id' => $album_id,
        );
        return $this -> objDaoAlbum -> selectOne($fields, $conds);
    }

    public function updateAlbum($data,$album_id){

        $conds = array(
            'album_id' => $album_id,
        );
        $result =  $this -> objDaoAlbum -> update($data, $conds);
        if($result == false){
            return false;
        }
        return true;

    }

    public function delAlbum($album_id){

        $data = array(
            'valid' => "2",
        );

        $conds = array(
            'album_id' => $album_id,
        );
        $result =  $this -> objDaoAlbum -> update($data, $conds);
        if($result == false){
            return false;
        }
        return true;
    }

} 