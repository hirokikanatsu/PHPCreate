<?php
require_once(ROOT_PATH.'/Models/User.php');
require_once(ROOT_PATH.'/Models/Token.php');


class UserController {
    private $user;

    public  function __construct(){
        //リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post']= $_POST;

        //  モデルオブジェクトの生成
        $this->User = new User();

        $dbh= $this->User->get_db_handler();
        $this->Token = new Token($dbh);
    }

    //2重送信防止・予期せぬページ遷移の防止
    public function settoken(){
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'] = $token;

        return $token;
    }
    public function checktoken(){
        $onetimetoken = filter_input(INPUT_POST,'token');
        if(!isset($_SESSION['token']) || $onetimetoken !== $_SESSION['token']){
            header('Location:index.php');
            exit;
        } 
        unset($_SESSION['token']);
    }

    public function signconfirm(){
        if(!empty($this->request['post']['confirm'])){
            $err = [];
            if(empty($this->request['post']['name']) || mb_strlen($this->request['post']['name']) >10){
                $err['name'] = 'ユーザー名は１０文字以内で必須入力です';
            }

            if(empty($this->request['post']['email']) || !filter_var($this->request['post']['email'], FILTER_VALIDATE_EMAIL)){
                $err['email'] = 'メールアドレスが不正です';
            }

            if(empty($this->request['post']['password']) || !preg_match("/^[a-z][a-z0-9_]{7,14}$/i", $this->request['post']['password'])){
                $err['password'] = 'パスワードは８～１５文字以内で必須入力です';
            }
        }

        if(empty($err)){
        $_SESSION['name'] = $this->request['post']['name'];
        $_SESSION['email'] = $this->request['post']['email'];
        $_SESSION['password'] = $this->request['post']['password'];
        $_SESSION['confirm'] = $this->request['post']['confirm'];
        $_SESSION['name'] = htmlspecialchars($_SESSION['name'],ENT_QUOTES);
        $_SESSION['email'] = htmlspecialchars($_SESSION['email'],ENT_QUOTES);
        $_SESSION['password'] = htmlspecialchars($_SESSION['password'],ENT_QUOTES);
        }else{
            $_SESSION['err'] = $err;
            header('Location:signup.php');
        }
    }

    public function signup(){
        if(!empty($_SESSION['confirm'])){
        $_SESSION['password'] = password_hash($_SESSION['password'],PASSWORD_DEFAULT);

        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];

            $this->User->createUser($name,$email,$password);

            $_SESSION = [];
            session_destroy();
        }
        
    }

    public function login(){
        $email = '';
        $err = [];
        if(isset($_POST['login'])){
            if(empty($_POST['email']) || empty($_POST['password'])){
                $err = 'メールアドレスとパスワードを両方入力してください';
                return $err;
            }else{
                $email = $_POST['email'];
            }

        if(isset($email) ){
            $user = $this->User->getUserByEmail($email);
        }else{
            $err = '不正です';
        }

        if(empty($err)){
            if(password_verify($_POST['password'],$user['password'])== true){
                $_SESSION['user'] = $user;
                header("Location:index.php");
                return $_SESSION['user'];
            }else{
                $err = 'メールアドレスもしくはパスワードが間違っています';
                return "ログイン失敗 : ".$err;
            }
        }else{
            return "ログイン失敗です : ".$err;
        }   
    }
        
    }

    public function logout(){
        $_SESSION = array();
        session_destroy();
    }

    public function re_pwd(){
        if(!empty($_POST)){
            $passResetToken = md5(uniqid(rand(),true));

            if(empty($this->request['post']['email']) || !filter_var($this->request['post']['email'], FILTER_VALIDATE_EMAIL)){
                $_SESSION['error'] = 'メールアドレスが不正です';
                return $_SESSION['error'];
            }else{
                    $email = '';
                    if(!empty($this->request['post'])){
                        $email = $this->request['post']['email'];

                        //メール送信
                        mb_language("Japanese");
                        mb_internal_encoding("UTF-8");
                        $title = 'パスワード変更';
                        $message = '以下のアドレスからパスワードのリセットを行ってください。' . PHP_EOL;
                        $message .=  'アドレスの有効時間は30分間です。' . PHP_EOL . PHP_EOL;
                        $message.= 'http://localhost/re_pwdconfirm.php?token=' . $passResetToken;
                        $header = 'From:hoenhaimu6561@gmail.com';
                        $from = 'hoenhaimu6561@gmail.com';
                        $p_from = '-f $from';
                        
                            if(mb_send_mail($email, $title, $message,$header,$p_from)){
                                $_SESSION['sendmail'] = 'メール送信しました';
                                }else{
                                //メール送信失敗
                            }
                
                            //emailに一致するユーザー取得
                            $result = $this->User->getUserByEmail($email);
                            
                            //tokensテーブルの先ほど取得したユーザーと同じuser_idにトークンを保存する
                            if(!empty($result)){
                                $user_id = $result['id'];
                                $this->Token->inserttoken($user_id,$passResetToken);
                            }
                    } 
                }
                
        }
    }

    public function re_pwdconfirm(){
        $token =  $this->request['get']['token'];
        $result = $this->Token->getToken($token);
        //urlのトークンが不正でないかチェック
        if($result == false){
           header('Location:login.php');
        }else{
            $limitTime = date("Y-m-d H:i:s",strtotime("-30 min"));
        }

        //トークンの作成時間が30分以内かチェック
        if(strtotime($result['created_at']) >= strtotime($limitTime)){
            //何もしない
        }else{
            $_SESSION['errortime'] = 'URLが時間切れです。';
            return false;
        }
        
        if(isset($_POST['passwordconfirm'])){
            $err = [];
            //パスワードバリデーション
            if(empty($this->request['post']['newpassword']) || !preg_match("/^[a-z][a-z0-9_]{7,14}$/i", $this->request['post']['newpassword'])){
                $err[] = 'パスワードが不正です 。英数字8～15文字で作成してください';
            }
            if(!($this->request['post']['newpassword'] === $this->request['post']['newpasswordconf'])){
                $err[] = '確認用パスワードが一致しません';
            }

            if(empty($err)){
                $user_id = $result['user_id'];
                $password = $this->request['post']['newpassword'];
                $password = password_hash($password,PASSWORD_DEFAULT);
                $this->User->editPassword($user_id,$password);
                $this->Token->deletetoken($user_id);
                header('Location:login.php');
            }else{
                return $err;
            }
        }
    }


    public function iinecount(){
        if(empty($_SESSION['user'])){
            $user = $this->User->getUserByUser_id($_SESSION['searchmenu']['user_id']);
            $_SESSION['user'] = $user;
        }
    }
    
    
    

}


?>