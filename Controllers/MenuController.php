<?php
require_once(ROOT_PATH.'/Models/Menu.php');
require_once(ROOT_PATH.'/Models/Image.php');
require_once(ROOT_PATH.'/Models/Iinecount.php');

class MenuController{
    private $menu;

    

    public  function __construct(){
        //リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post']= $_POST;

        //  モデルオブジェクトの生成
        $this->Menu = new Menu();
        $this->Image = new Image();
        $this->Iinecount = new Iinecount();
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

    public function quitpagemove(){
        if(!isset($_SESSION['user'])){
            header('Location:login.php');
        }
    }


    public function createconfirm(){
        function h($s){
            return htmlspecialchars($s,ENT_QUOTES,"UTF-8");
          }
            if(isset($_POST)){

                $err = [];
                $menuname = $this->request['post']['menuname'];
                $item = $this->request['post']['item'];
                $howtomake = $this->request['post']['howtomake'];
                $price = $this->request['post']['price'];
                $genre = $this->request['post']['genre'];
                
                if(empty($menuname) == true){
                    $err['menu'] = '献立名を入力してください';
                }
                if(empty($item) == true){
                    $err['item'] = '食材を入力してください';
                }
                if(empty($howtomake) == true){
                    $err['howtomake'] = '作り方を入力してください';
                }
                if(empty($price) == true || !preg_match('/^[0-9]+$/', $price)){
                    $err['price'] = '価格を半角で入力してください';
                }
                if(empty($genre) == true){
                    $err['genre'] = 'ジャンルを入力してください';
                }

                //パスワードばれないため
                $filename = basename($_FILES['image']['name']);
                $tmp_path = $_FILES['image']['tmp_name'];
                $file_err = $_FILES['image']['error'];
                $filesize = $_FILES['image']['size'];
                //ファイルサイズが１MB未満か
                if($filesize > 1048576 || $file_err == 2){
                    $err['file'] = 'ファイルが不正です';
                }

                //拡張は画像形式か
                $allow_ext = array('jpg','jpeg','png');
                $file_ext = pathinfo($filename,PATHINFO_EXTENSION);
                if(!in_array(strtolower($file_ext), $allow_ext)){
                    $err['file'] = 'ファイルが不正です';
                }

                //ファイルはあるか
                if(!is_uploaded_file($tmp_path)){
                    $err['file']  = 'ファイルが不正です';
                }

                $date = date('YmdHis');

                $upload_dir = './img/'.$date.'.'.$file_ext;

                

                if(empty($err)){

                    if(move_uploaded_file($tmp_path,$upload_dir)){
                        //画像アップ成功
                    }else{
                        //画像アップ失敗
                    } 

                    $name = h($menuname);
                    $item = h($item);
                    $howtomake = h($howtomake);
                    $price = h($price);
                    $user_id = $_POST['user_id'];
                    $genre_id = h($genre);
                    
                    $this->Menu->createmenu($name,$item,$howtomake,$price,$user_id,$genre_id);
                    $menuid = $this->Menu->getmenuid($item,$howtomake);
                    $this->Image->insertimage($upload_dir,$upload_dir,$_FILES['image']['size'],$menuid['id']);
                    if(isset($_SESSION['create'])){
                        $_SESSION['create'] = [];
                    }
                    
                    return $menuid;
                    
                }else{
                    $_SESSION['create']['createmenuname'] = $this->request['post']['menuname'];
                    $_SESSION['create']['createitem'] = $this->request['post']['item'];
                    $_SESSION['create']['createhowtomake'] = $this->request['post']['howtomake'];
                    $_SESSION['create']['createprice'] = $this->request['post']['price'];
                    $_SESSION['create']['menuerr'] = $err['menu'];
                    $_SESSION['create']['item'] = $err['item'];
                    $_SESSION['create']['howtomake'] = $err['howtomake'];
                    $_SESSION['create']['price'] = $err['price'];
                    $_SESSION['create']['genre'] = $err['genre'];
                    $_SESSION['create']['file'] = $err['file'];
                    header('Location:createmenu.php');
                    return $_SESSION;
                }
            }
        }


    public function mypage(){
            if(isset($_SESSION['edit'])){
                $_SESSION['edit'] = [];
            }
            
            $user_id = $_SESSION['user']['id'];
            $result = $this->Menu->getmymenu($user_id);
            return $result;
        }


    public function allpage(){
        if(isset($_SESSION['user']['id']) && $_SESSION['user']['role'] == 1){
            $user_id = $_SESSION['user']['id'];
            $result = $this->Menu->getallmenu();
            return $result;
        }else{
            header('Location:login.php');
        }
    }

    public function editmymenu(){
        $_SESSION['menu_id'] = $_GET;
        $result = $this->Menu->getmenuByid($_SESSION['menu_id']['id']);
        return $result;
    }


    public function editend(){
        function ht($s){
            return htmlspecialchars($s,ENT_QUOTES,"UTF-8");
          }
        
        if(isset($_POST)){
            $err = [];
            $menuname = $this->request['post']['menuname'];
            $item = $this->request['post']['item'];
            $howtomake = $this->request['post']['howtomake'];
            $price = $this->request['post']['price'];
            $genre = $this->request['post']['genre'];
            
            if(empty($menuname) == true){
                $err['menuedit'] = '献立名を入力してください';
            }
            if(empty($item) == true){
                $err['itemedit'] = '食材を入力してください';
            }
            if(empty($howtomake) == true){
                $err['howtomakeedit'] = '作り方を入力してください';
            }
            if(empty($price) == true || !preg_match('/^[0-9]+$/', $price)){
                $err['priceedit'] = '価格を入力してください';
            }
            if(empty($genre) == true){
                $err['genreedit'] = 'ジャンルを入力してください';
            }

            //パスワードばれないため
            $image_name = basename($_FILES['image']['name']);
            $file_path = $_FILES['image']['tmp_name'];
            $file_err = $_FILES['image']['error'];
            $image_size = $_FILES['image']['size'];
            
            //ファイルサイズが１MB未満か
            if($image_size > 1048576 || $file_err == 2){
                $err['file'] = '画像が不正です';
            }

            //拡張は画像形式か
            $allow_ext = array('jpg','jpeg','png');
            $file_ext = pathinfo($image_name,PATHINFO_EXTENSION);
            if(!in_array(strtolower($file_ext), $allow_ext)){
                $err['file'] = '画像が不正です';
            }

            //ファイルはあるか
            if(!is_uploaded_file($file_path)){
                $err['file']  = '画像が不正です';
            }

            $date = date('YmdHis');

            $upload_dir = './img/'.$date.'.'.$file_ext;
            
            if(empty($err)){    
                if(move_uploaded_file($file_path,$upload_dir)){
                    //画像アップ成功
                    }else{
                        //画像アップ失敗
                    } 

                $name = ht($menuname);
                $item = ht($item);
                $howtomake = ht($howtomake);
                $price = ht($price);
                $user_id = $_SESSION['user']['id'];
                $genre_id = ht($genre);
                $menu_id = $_SESSION['menu_id']['id'];

                $this->Menu->updatemenu($name,$item,$howtomake,$price,$user_id,$genre_id,$menu_id);
                $this->Image->updateimage($upload_dir,$upload_dir,$image_size,$menu_id);
                $_SESSION['edit'] = [];
                
                    return '成功';

            }else{
                $_SESSION['edit']['editmenuname'] = $this->request['post']['menuname'];
                $_SESSION['edit']['edititem'] = $this->request['post']['item'];
                $_SESSION['edit']['edithowtomake'] = $this->request['post']['howtomake'];
                $_SESSION['edit']['editprice'] = $this->request['post']['price'];
                $_SESSION['edit']['menuedit'] = $err['menuedit'];
                $_SESSION['edit']['itemedit'] = $err['itemedit'];
                $_SESSION['edit']['howtomakeedit'] = $err['howtomakeedit'];
                $_SESSION['edit']['priceedit'] = $err['priceedit'];
                $_SESSION['edit']['genreedit'] = $err['genreedit'];
                $_SESSION['edit']['file'] = $err['file'];
                header("Location:editmymenu.php?id=<?= $_SESSION[menu_id]; ?>");
                return $_SESSION;
            }
        }
    }


    public function deletemyend(){
          
            if(isset($_SESSION['user']['id'])){
                if( isset( $_SERVER['HTTP_REFERER']) && (preg_match('/mypage.php/', $_SERVER['HTTP_REFERER']) ||  preg_match('/allpage.php/', $_SERVER['HTTP_REFERER']))){
                    $menu_id = $this->request['get']['id'];
                    $result = $this->Image->getimageByid($menu_id);
                    $this->Menu->deletemymenu($menu_id);
                    $this->Image->deletemyimage($menu_id);
                    $this->Iinecount->deleteiine($menu_id);

                    if(isset($result['file_path'])){
                        $file_path = $result['file_path'];
                        $res = glob('img/*');
                        foreach($res as $image){
                            if('./'.$image == $file_path){
                                unlink("$file_path");
                            }
                        }
                    }     
                    return '削除しました';
                }header('Location:index.php');
            }else{
                header('Location:login.php');
            }
    }

    
    public function index(){
        if(isset($_SESSION['user']['id'])){       
            $result = [];
            $result = $this->Menu->getrandommenu();
            if(!empty($result) && count($result) >=6){
                $menu_id_1 = $result['0']['id'];
                $menu_id_2 = $result['1']['id'];
                $menu_id_3 = $result['2']['id'];
                $menu_id_4 = $result['3']['id'];
                $menu_id_5 = $result['4']['id'];
                $menu_id_6 = $result['5']['id'];
                $_SESSION['menu'] = $result;
                $result = [];
                $result[] = $this->Image->getimage($menu_id_1);
                $result[] = $this->Image->getimage($menu_id_2);
                $result[] = $this->Image->getimage($menu_id_3);
                $result[] = $this->Image->getimage($menu_id_4);
                $result[] = $this->Image->getimage($menu_id_5);
                $result[] = $this->Image->getimage($menu_id_6);
                $iinetop = $this->Iinecount->getiinetop();
                $menutop[] = $this->Menu->getmenuByid($iinetop['0']['menu_id']); 
                $menutop[] = $this->Menu->getmenuByid($iinetop['1']['menu_id']); 
                $menutop[] = $this->Menu->getmenuByid($iinetop['2']['menu_id']); 
                $imagetop[] = $this->Image->getimage($iinetop['0']['menu_id']);
                $imagetop[] = $this->Image->getimage($iinetop['1']['menu_id']);
                $imagetop[] = $this->Image->getimage($iinetop['2']['menu_id']);
                $_SESSION['iinetop'] = $iinetop;
                $_SESSION['menutop'] = $menutop;
                $_SESSION['imagetop'] = $imagetop;
                return $result;
            }
        }else{
            header('Location:login.php');
        }
    }

    public function iinecount(){
        if(isset($_SESSION['user']['id'])){
            $iine = $this->Iinecount->getiinetop();
            $iinemenu = [];
            for($i=0;$i<3;$i++){
                $iinemenu = $this->Menu->getmenuByid($iine[$i]['menu_id']);
                $_SESSION['menus']['iine'] = $iinemenu;
                $iineimage = $this->Image->getimageByid($iine[$i]['menu_id']);
            }
            return $iine;
        }else{
            header('Location:login.php');
        }
    }


    public function search(){
        if(isset($this->request['post']['search'])){
            $genre_id = $this->request['post']['genre'];
            $keyword = $this->request['post']['keyword'];
            $keyword = '%'.$keyword.'%';
            $result = $this->Menu->getmenuBykeyword($genre_id,$keyword);
            if(!empty($result)){
                $_SESSION['search'] = $result;
                foreach($result as $menu){
                    $menu_id = $menu['id'];
                    $searchimg[] = $this->Image->getimageByid($menu_id);
                }
                return $searchimg;
            }
            
            
        }
    }
    
        
    public function detailmenu(){
        $menu_id = $this->request['get']['id'];
        $search_menu = $this->Menu->getmenuByid($menu_id);
        $search_image = $this->Image->getimageByid($menu_id);
        $_SESSION['searchmenu'] = $search_menu;
        $_SESSION['searchimg'] = $search_image;

        $user_id = $_SESSION['user']['id'];
        $iineUser = $this->Iinecount->getmenuiine($user_id,$menu_id);
        $sum = $this->Iinecount->getsumiine($menu_id);
        $_SESSION['iineUser'] = $iineUser;
        $_SESSION['sum'] = $sum;
        return $iineUser;
        
    }


    public function detailmenuajax(){
        if(!empty($_POST['postId'])){
            $menu_id = $_POST['postId'];
            $user_id = $_POST['user_Id'];
            $result = $this->Iinecount->getmenuiine($user_id,$menu_id);
            if(isset($result) && empty($result)){
                $this->Iinecount->insertiinecount($user_id,$menu_id);
            }else{
               $this->Iinecount->deleteiinecount($user_id,$menu_id);
            }
        }
    }

    public function myiine(){
        if(isset($_SESSION['edit'])){
            $_SESSION['edit'] = [];
        }
        
        $user_id = $_SESSION['user']['id'];
        $result = $this->Iinecount->getmyiine($user_id);
        foreach($result as $menu){
            $iinemenu[] = $this->Menu->getmenuByid($menu['menu_id']);
        }
        return $iinemenu;
    }
           
}


?>