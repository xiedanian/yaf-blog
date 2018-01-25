<?php

class Service_Data_AlbumPicsModel {
    private $objDaoAlbumPics;

    function __construct(){
        $this -> objDaoAlbumPics = new Dao_Db_AlbumPicsModel();
    }

    public function getAlbumPicsList($album_id){
        $fields = array(
            'id',
            'album_id',
            'url',
            'intro',
            'create_time',
        );

        $conds = array(
            'album_id' => $album_id,
            'is_deleted' => "0",
        );
        $appends = sprintf("order by sort desc");
        return $this -> objDaoAlbumPics -> select($fields, $conds, $appends);
    }
} 