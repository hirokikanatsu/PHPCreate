<?php

require_once(ROOT_PATH.'/Models/dbc.php');

class Menu extends dbc{
    private $table = 'menus';

    public function __construct($dbh = null){
        parent::__construct($dbh);
    }

    /**
    *menusテーブルに献立を作成
    *
    *@param string $name,$item,$howtomake,$price,$image,$user_id,$genre_id
    */
    public function createmenu($name=0,$item=0,$howtomake=0,$price=0,$user_id=0,$genre_id=0):void{
        try{
            $this->dbh->beginTransaction();
            $sql = "INSERT into ".$this->table." (name,item,howtomake,price,user_id,genre_id) values (:name,:item,:howtomake,:price,:user_id,:genre_id)";
            $sth = $this->dbh->prepare($sql);
            $sth ->bindParam(':name',$name,PDO::PARAM_STR);
            $sth ->bindParam(':item',$item,PDO::PARAM_STR);
            $sth ->bindParam(':howtomake',$howtomake,PDO::PARAM_STR);
            $sth ->bindParam(':price',$price,PDO::PARAM_INT);
            $sth ->bindParam(':user_id',$user_id,PDO::PARAM_STR);
            $sth ->bindParam(':genre_id',$genre_id,PDO::PARAM_STR);
            $sth ->execute();
            $this->dbh->commit();
        }catch(PDOException $e){
            $this->dbh->rollback();
            echo '登録失敗しました:'.$e->getMessage();
            exit;
        }
    }

    /**
    *menusテーブルの自分の献立を全て取得
    *
    *@param string $use_id
    *@return Array $result
    */
    public function getmymenu($user_id=0){
        $sql = "SELECT * from ".$this->table.' where user_id = :user_id';
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':user_id',$user_id,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * 指定したメニューを編集
     * 
     * @param string $name,$item,$howtomake,$price,$image,$user_id,$genre_id
     * @return void
     */
    public function updatemenu($name=0,$item=0,$howtomake=0,$price=0,$user_id=0,$genre_id=0,$menu_id=0){
        try{
            $this->dbh->beginTransaction();
            $sql = "UPDATE ".$this->table." SET name=:name,item=:item,howtomake=:howtomake,price=:price,user_id=:user_id,genre_id=:genre_id where id = :menu_id";
            $sth = $this->dbh->prepare($sql);
            $sth ->bindParam(':name',$name,PDO::PARAM_STR);
            $sth ->bindParam(':item',$item,PDO::PARAM_STR);
            $sth ->bindParam(':howtomake',$howtomake,PDO::PARAM_STR);
            $sth ->bindParam(':price',$price,PDO::PARAM_INT);
            $sth ->bindParam(':user_id',$user_id,PDO::PARAM_STR);
            $sth ->bindParam(':genre_id',$genre_id,PDO::PARAM_STR);
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
    *menusテーブルから指定したメニューを取得
    *
    *@param string $item,$howtomake
    *@return Array $result
    */
    public function getmenuid($item=0,$howtomake=0){
        $sql = "SELECT * from ".$this->table.' where item = :item and howtomake = :howtomake';
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':item',$item,PDO::PARAM_STR);
        $sth ->bindParam(':howtomake',$howtomake,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * メニューを削除
     * 
     * @param string $menu_id
     * @return void
     */
    public function deletemymenu($menu_id = 0){
        try{
            $this->dbh->beginTransaction();
            $sql = "DELETE from ".$this->table." where id = :menu_id";
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
    *menusテーブルからmenu_idで指定したメニューを取得
    *
    *@param string $menu_id
    *@return Array $result
    */
    public function getmenuByid($menu_id=0){
        $sql = "SELECT * from ".$this->table.' where id = :menu_id';
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':menu_id',$menu_id,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetch(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
    *menusテーブルから全メニューを取得
    *
    *@param string $menu_id
    *@return Array $result
    */
    public function getallmenu(){
        $sql = "SELECT * from ".$this->table;
        $sth = $this->dbh->prepare($sql);
        $sth ->execute();
        $result = $sth ->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
    *menusテーブルからランダムにメニューを８件取得
    *
    *@param void
    *@return Array $result
    */
    public function getrandommenu(){
        $sql = "SELECT * from ".$this->table.' order by rand() limit 6';
        $sth = $this->dbh->prepare($sql);
        $sth ->execute();
        $result = $sth ->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
    *menusテーブルからキーワードが入ったメニューを取得
    *
    *@param str $genre_id,$keyword
    *@return Array $result
    */
    public function getmenuBykeyword($genre_id=0,$keyword=0){
        $sql = "SELECT * from ".$this->table.' where genre_id = :genre_id and item like (:keyword)';
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':genre_id',$genre_id,PDO::PARAM_STR);
        $sth ->bindParam(':keyword',$keyword,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }
}
?>