<?php

require_once(ROOT_PATH.'/Models/dbc.php');

class Token extends dbc{
    private $table = 'tokens';

    public function __construct($dbh = null){
        parent::__construct($dbh);
    }

    public function inserttoken($user_id=0,$token=0){
        try{
            $this->dbh->beginTransaction();
            $sql = 'INSERT into '.$this->table.' (user_id,token) values(:user_id,:token)';
            $sth = $this->dbh->prepare($sql);
            $sth ->bindParam(':user_id',$user_id,PDO::PARAM_INT);
            $sth ->bindParam(':token',$token,PDO::PARAM_STR);
            $sth ->execute();
            $this->dbh->commit();
        }catch(PDOException $e){
            $this->dbh->rollback();
            echo 'ユーザー登録失敗しました:'.$e->getMessage();
            exit;
        }
    }

    /**
    *tokensテーブルからtokenと一致するデータを取得
    *
    *@param string $token トークン
    *@return Array $result  トークン情報
    */
    public function getToken($token=0){
        $sql = "SELECT * from ".$this->table." where token = :token ";
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':token',$token,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetch(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * トークンを削除
     * 
     * @param string $user_id
     * @return void
     */
    public function deletetoken($user_id = 0){
        try{
            $this->dbh->beginTransaction();
            $sql = "DELETE from ".$this->table." where user_id = :user_id";
            $sth = $this->dbh->prepare($sql);
            $sth ->bindParam(':user_id',$user_id,PDO::PARAM_STR);
            $sth ->execute();
            $this->dbh->commit();
        }catch(PDOException $e){
            $this->dbh->rollBack();
            echo '削除失敗'. $e->getMessage();
            exit;
        }
    }


}





?>