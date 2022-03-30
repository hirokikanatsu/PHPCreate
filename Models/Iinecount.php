<?php

require_once(ROOT_PATH.'/Models/dbc.php');

class Iinecount extends dbc{
    private $table = 'iinecounts';

    public function __construct($dbh = null){
        parent::__construct($dbh);
    }


    /**
    *iinecountsテーブルのいいね総数TOP３を取得
    *
    *@param 
    *@return Array $result
    */
    public function getiinetop(){
        $sql = "SELECT menu_id, count(menu_id) from ".$this->table.' group by menu_id order by count(menu_id) desc limit 3 ';
        $sth = $this->dbh->prepare($sql);
        $sth ->execute();
        $result = $sth ->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
    *指定のメニューを取得
    *
    *@param $user_id,$menu_id
    *@return Array $result
    */
    public function getmenuiine($user_id=0,$menu_id=0){
        $sql = "SELECT * from ".$this->table.' where user_id = :user_id and menu_id = :menu_id';
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':user_id',$user_id,PDO::PARAM_STR);
        $sth ->bindParam(':menu_id',$menu_id,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
    *iinecountsテーブルからいいね数を取得
    *
    *@param str $menu_id
    *@return int $result
    */
    public function getsumiine($menu_id=0){
        $sql = "SELECT count(menu_id) from ".$this->table.' where menu_id = :menu_id';
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':menu_id',$menu_id,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetch(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
    *iinecountsテーブルからすでに指定の投稿にいいねされているか確認
    *
    *@param str $user_id,$menu_id
    *@return void
    */
    public function insertiinecount($user_id=0,$menu_id=0){
        try{
            $this->dbh->beginTransaction();
            $sql = "INSERT INTO ".$this->table.' (user_id,menu_id) VALUES (:user_id , :menu_id)';
            $sth = $this->dbh->prepare($sql);
            $sth ->bindParam(':user_id',$user_id,PDO::PARAM_STR);
            $sth ->bindParam(':menu_id',$menu_id,PDO::PARAM_STR);
            $sth ->execute();
            $this->dbh->commit();
        }catch(PDOException $e){
            $this->dbh->rollback();
            echo '登録失敗'. $e->getMessage();
            exit;
        }
    }


     /**
     * いいねを削除
     * 
     * @param string $user_id,menu_id
     * @return void
     */
    public function deleteiinecount($user_id=0,$menu_id = 0){
        try{
            $this->dbh->beginTransaction();
            $sql = "DELETE from ".$this->table." where user_id = :user_id and menu_id = :menu_id";
            $sth = $this->dbh->prepare($sql);
            $sth ->bindParam(':user_id',$user_id,PDO::PARAM_STR);
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
     * 削除されたいいねと同じメニューのいいねを削除
     * 
     * @param string menu_id
     * @return void
     */
    public function deleteiine($menu_id = 0){
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
    *iinecountsテーブルから自分がいいねした献立IDを取得
    *
    *@param str $user_id
    *@return int $result
    */
    public function getmyiine($user_id=0){
        $sql = "SELECT menu_id from ".$this->table.' where user_id = :user_id';
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':user_id',$user_id,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }


    

}