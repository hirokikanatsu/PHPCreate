<?php

require_once(ROOT_PATH.'/Models/dbc.php');

class Image extends dbc{
    private $table = 'images';

    public function __construct($dbh = null){
        parent::__construct($dbh);
    }

    /**
     * 画像を登録する
     * 
     * @param string $image_name,$file_path,$image_size,$menu_id
     * @return void
     */
    public function insertimage($image_name=0,$file_path=0,$image_size=0,$menu_id=0){
        try{
            $this->dbh->beginTransaction();
            $sql = "INSERT into ".$this->table." (image_name,file_path,image_size,menu_id) values (:image_name,:file_path,:image_size,:menu_id)";
            $sth = $this->dbh->prepare($sql);
            $sth ->bindParam(':image_name',$image_name,PDO::PARAM_STR);
            $sth ->bindParam(':file_path',$file_path,PDO::PARAM_STR);
            $sth ->bindParam(':image_size',$image_size,PDO::PARAM_STR);
            $sth ->bindParam(':menu_id',$menu_id,PDO::PARAM_STR);
            $sth ->execute();
            $this->dbh->commit();
        }catch(PDOException $e){
            $this->dbh->rollback();
            echo 'ユーザー登録失敗しました:'.$e->getMessage();
            exit;
        }
    }
    

    /**
     * 編集で別の画像を登録
     * 
     * @param string $image_name,$file_path,$image_size,$menu_id
     * @return void
     */
    public function updateimage($image_name=0,$file_path=0,$image_size=0,$menu_id=0){
        try{
            $this->dbh->beginTransaction();
            $sql = "UPDATE ".$this->table." SET image_name=:image_name,file_path=:file_path,image_size=:image_size,menu_id=:menu_id where menu_id = :menu_id";
            $sth = $this->dbh->prepare($sql);
            $sth ->bindParam(':image_name',$image_name,PDO::PARAM_STR);
            $sth ->bindParam(':file_path',$file_path,PDO::PARAM_STR);
            $sth ->bindParam(':image_size',$image_size,PDO::PARAM_INT);
            $sth ->bindParam(':menu_id',$menu_id,PDO::PARAM_INT);
            $sth->execute();
            $this->dbh->commit();
        }catch(PDOException $e){
            $this->dbh->rollback();
            echo '編集失敗'. $e->getMessage();
            exit;
        }
    }

    /**
     * 指定した画像を削除
     * 
     * @param string $menu_id
     * @return void
     */
    public function deletemyimage($menu_id = 0){
        try{
            $this->dbh->beginTransaction();
            $sql = "DELETE from ".$this->table." where menu_id = :menu_id";
            $sth = $this->dbh->prepare($sql);
            $sth ->bindParam(':menu_id',$menu_id,PDO::PARAM_STR);
            $sth ->execute();
            $this->dbh->commit();
        }catch(PDOException $e){
            $this->dbh->rollBack();
            echo '削除失敗'. $e->getMessage();
            exit;
        }
    }


    /**
    *imagesテーブルからmenu_idで指定した画像を取得
    *
    *@param string $menu_id
    *@return Array $result
    */
    public function getimageByid($menu_id){
        $sql = "SELECT * from ".$this->table.' where menu_id = :menu_id';
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':menu_id',$menu_id,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
    *imagesテーブルから指定した画像を取得
    *
    *@param int $menu_id_1
    *@return Array $result
    */
    public function getimage($menu_id=0){
        $sql = "SELECT * from ".$this->table.' where menu_id=:menu_id  ';
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':menu_id',$menu_id,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }


}