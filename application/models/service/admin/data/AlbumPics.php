<?php

class Service_Admin_Data_AlbumPicsModel {
    private $objDaoAlbumPics;

    function __construct(){
        $this -> objDaoAlbumPics = new Dao_Db_AlbumPicsModel();
    }

    public function Add($data)
    {
        $pics_arr = explode(",",$data['pics_str']);

        foreach ($pics_arr as $value){
            $picsData[] = array(
                $data['album_id'],
                $value,
                visk_Const_Define::INTRO,
                visk_Const_Define::SORT,
                visk_Const_Define::IS_DELETED,
                time(),
            );
        }

        $fields = array(
            'album_id',
            'url',
            'intro',
            'sort',
            'is_deleted',
            'create_time',
        );
        return $this -> objDaoAlbumPics -> multiInsert($fields, $picsData);
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
        );
        $appends = sprintf("order by create_time desc");
        return $this -> objDaoAlbumPics -> select($fields, $conds);
        echo $this -> objDaoAlbumPics -> getLastSql();
    }

} 