<?php
$a = 0;
$i = 0;
session_start();
require_once(ROOT_PATH.'Controllers/UserController.php');
require_once(ROOT_PATH.'Controllers/MenuController.php');

$user = new UserController();
$menu = new MenuController();
$menu->quitpagemove();

$result = $menu->index();
$iine = $menu->iinecount();
$menus = $menu->search();

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>index.php</title>
  </head>
  <body>

    <?php include('user_header.php'); ?>
<div class='indexwrapper'>   
    <div class="indexwrap">
        <?php if($_SESSION['user']['role'] == 1): ?>
            <div><a href="allpage.php" class="allmenus">～全献立一覧はこちら～</a></div>
        <?php endif; ?>
            

        <div class="containerwrap">
            <div class="container" >

            <h3 class="indexh1">キーワードで検索しよう</h3>

            <div class='reseach'>
                <div class="input-group mb-3 reserchform rounded-2" >
                    <form action="" method="POST" style="display: flex; margin:0 auto;">
                        <select class="form-select col-sm-10" name='genre' style="width:300px;" aria-label="Default select example">
                            <option value="1">米</option>
                            <option value="2">サラダ</option>
                            <option value="3">汁物</option>
                            <option value="4">パスタ</option>
                            <option value="5">メインディッシュ</option>
                        </select>
                        <input type="text" name='keyword' class="form-control" aria-label="Text input with dropdown button"  placeholder="キーワードを入力してください" style="width:500px; margin-left: 50px;">
                        <input type="submit" class="fadeIn fourth search_btn" name='search' value="検索">
                    </form>
                </div>
               
                <?php if(isset($_POST['search'])): ?>
                    <div style="width:1200px; height:600px;">  
                        <?php if(isset($menus)): ?>
                            
                                <?php foreach($menus as $menu): ?>
                                    
                                        <div style="float:left; margin:0 50px 130px 0;">
                                        
                                        <a href="detailmenu.php?id=<?= $_SESSION['search'][$i]['id']; ?>" style="height: 400px;">
                                            <img src="<?= $menu[0]['file_path']; ?>" alt="画像なし"  style='width:380px; height:400px; margin:0 0px 20px 0;' >
                                            <p style="margin-bottom: 130px;"><?php echo $_SESSION['search'][$i]['name'];?></p>
                                        </a>  
                                        <?php $i++; ?>
                                        </div>          
                                <?php endforeach; ?>
                        <?php else: ?>
                            <p style="font-weight: bold; font-size:50px;"><?php echo '検索結果がありませんでした'; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>   
            </div>

            <div class="me-auto p-2 bd-highlight"><h1 class="indexh1">献立一覧</h1></div>
                       
                    <?php if(isset($result)): ?>
                        <?php foreach($result as $image): ?>
                                <div style="float:left; margin:0 50px 130px 0;">
                                <a href="detailmenu.php?id=<?php echo $_SESSION['menu'][$a]['id']; ?>" style="height: 400px; text-decoration: none ">
                                    <img src="<?php echo $image['0']['file_path']; ?>" alt="画像なし"  style='width:380px; height:400px; margin:0 0px 20px 0;' >
                                    <p style="margin-bottom: 130px; font-size: 20px;"><?php echo $_SESSION['menu'][$a]['name'];?></p>
                                </a>  
                                <?php $a++; ?>
                                </div>          
                        <?php endforeach; ?>
                    <?php endif; ?>
                   
                     
               
            </div>
                

            <h2 class="indexh1">ランキングTOP３</h2>
            
            <div class="ranking">
                <div class="container">
                    <div class="row">
                        <div style="float:left; margin:20 50px 100px 0;">
                            <?php for($b=0; $b<3; $b++): ?>
                                <a href="detailmenu.php?id=<?php echo $_SESSION['iinetop'][$b]['menu_id']; ?>" style="height: 400px; text-decoration: none ">
                                    <img src="<?php echo $_SESSION['imagetop'][$b][0]['file_path']; ?>" alt="画像なし"  style='width:380px; height:400px; margin:0 45px 20px 0;' >
                                    <p style="font-size: 30px; font-weight;bold;"><?php echo  $b+1; ?>位</p>
                                    <p style="margin-bottom: 20px; font-size: 40px; font-weight;bold; width:400px;"><?php echo $_SESSION['menutop'][$b]['name'];?></p>
                                    <p  style="margin-bottom: 100px; font-size: 23px;">いいね数　:<?= $iine[$b]['count(menu_id)']; ?></p>
                                </a>  
                            <?php endfor; ?>   
                        </div>  
                    </div>
                </div>
            </div>

            
        </div>
    </div>

    <?php include('footer.php'); ?>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
  </body>
</html>