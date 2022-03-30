<?php
require_once(ROOT_PATH.'/Models/dbc.php');


class User extends dbc{
    private $table = 'users';

    public function __construct($dbh = null){
        parent::__construct($dbh);
    }

    /**
    *usersテーブルにユーザーを作成
    *
    *@param string $_POST['name'],$_POST['email'],$_POST['password'] 
    */
    public function createUser($name,$email,$password):void{
        try{
            $this->dbh->beginTransaction();
            $sql = "INSERT into ".$this->table." (name,email,password) values (:name,:email,:password)";
            $sth = $this->dbh->prepare($sql);
            $sth ->bindParam(':name',$name,PDO::PARAM_STR);
            $sth ->bindParam(':email',$email,PDO::PARAM_STR);
            $sth ->bindParam(':password',$password,PDO::PARAM_STR);
            $sth ->execute();
            $this->dbh->commit();
        }catch(PDOException $e){
            $this->dbh->rollback();
            echo 'ユーザー登録失敗しました:'.$e->getMessage();
            exit;
        }
    }

    /**
    *usersテーブルからメールアドレスと一致するデータを取得
    *
    *@param string $email メールアドレス
    *@return Array $result  ユーザー情報
    */
    public function getUserByEmail($email=0){
        $sql = "SELECT * from ".$this->table." where email = :email ";
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam(':email',$email,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
    *usersテーブルから指定したユーザーのパスワードを編集
    *
    *@param Int $user_id 指定したユーザーのid
    */
    public function editPassword($user_id=0,$newpassword=0){
        try{
            $this->dbh->beginTransaction();
            $sql  = "UPDATE ".$this->table;
            $sql .= " set password = :password where id = :id";
            $sth  =$this->dbh->prepare($sql);
            $sth ->bindParam(':password', $newpassword, PDO::PARAM_STR);
            $sth ->bindParam(':id', $user_id, PDO::PARAM_INT);
            $sth ->execute();
            $this->dbh->commit();
            return '更新しました！';
        }catch(PDOException $e){
            $this->dbh->rollback();
            echo "パスワードの編集に失敗しました。".$e->getMessage();
            exit();
        }
    }

    /**
    *usersテーブルからuser情報を取得
    *
    *@param string $user_id 
    *@return Array $result  ユーザー情報
    */
    public function getUserByUser_id($user_id=0){
        $sql = "SELECT * from ".$this->table." where id = :id ";
        $sth = $this->dbh->prepare($sql);
        $sth ->bindParam('id',$user_id,PDO::PARAM_STR);
        $sth ->execute();
        $result = $sth ->fetch(PDO::FETCH_ASSOC);
        return $result;
    }


}

?>