<?php
session_start();
require_once(ROOT_PATH.'Controllers/MenuController.php');
require_once(ROOT_PATH.'Controllers/UserController.php');

$menu = new MenuController;
$user = new UserController;
$menu->quitpagemove();
$result = $menu->detailmenu();
$good = $menu->detailmenuajax();


?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <title>createconfirm</title>
  </head>

  <style>
     .active{
    color: #f44336;
    }
  </style>

  <body>
  <?php include('user_header.php'); ?>

<div class='indexwrapper'>   
    <div class="indexwrap">
        <div class="d-flex bd-highlight mb-3" >
            <div class="me-auto p-2 bd-highlight"><h1 class="indexh1">献立詳細</h1></div>
            <div class="p-2 bd-highlight">
                <div class="text-right" ><img src='<?php echo $_SESSION['searchimg'][0]['file_path']; ?>' alt='画像なし' style="width:400px; height:400px;"></div>
            </div>
        </div>

        <div class="containerwrap"  style="margin-top:100px;">
            <form action="#" method="POST">
                <div class="mb-3 row">
                    <label for="" class="col-sm-2 col-form-label" style="font-size: 30px; font-weight:bold;">献立名</label>
                    <div class="col-sm-10">
                        <p class="info" style="font-weight: bold;"><?php echo $_SESSION['searchmenu']['name']; ?></p>
                    </div>
                    <label for="" class="col-sm-2 col-form-label" style="font-size: 30px;">食材</label>
                    <div class="col-sm-10">
                        <p class="info" id="textspace" style="white-space:pre-wrap;"><?php echo $_SESSION['searchmenu']['item']; ?></p>
                    </div>
                    <label for="" class="col-sm-2 col-form-label" style="font-size: 30px;">作り方</label>
                    <div class="col-sm-10">
                        <p class="info" id="textspace" style="white-space:pre-wrap;"><?php echo $_SESSION['searchmenu']['howtomake']; ?></p>
                    </div>
                    <label for="" class="col-sm-2 col-form-label" style="font-size: 30px;">価格</label>
                    <div class="col-sm-10">
                        <p class="info"><?php echo $_SESSION['searchmenu']['price']; ?>円</p>
                    </div>
                </div>
            </form>   
        </div>
        
            <section class="post"  data-postid="<?php echo $_SESSION['searchmenu']['id']; ?>">
                <button type="button" class="btn btn-danger text-right pageback btn_good"   style="background-color: #f6f7de;">
                    <i class="<?php if(isset($result[0]['user_id']) && isset($result[0]['menu_id'])){
                        echo 'active' ;}?>" style="font-size: 60px; line-height:50px;">&hearts;</i>
                </button>
            </section>
            <p style="font-size: 30px;"><?= $_SESSION['sum']['count(menu_id)']; ?>いいね</p>
            

        
        <button type="button" class="btn btn-info text-right pageback" style="font-size: 30px; width:250px;" ><a href="index.php">トップへ戻る</a></button>
        <button type="button" class="btn btn-warning text-right pageback" style="font-size: 30px; width:250px;" ><a href="mypage.php">my献立へ戻る</a></button>
    </div>

    <?php include('footer.php'); ?>
</div>

    
    <script>
        $(function(){
            var $good = $('.btn_good'),goodPostId;
            $good.on('click',function(e){
                e.preventDefault();
                var $this = $(this);

                goodPostId = <?php echo $_SESSION['searchmenu']['id']; ?>;
                user_id = <?php echo $_SESSION['user']['id']; ?>;
                $.ajax({
                    type:'POST',
                    url:'./detailmenu.php',
                    dataType:'text',
                    data:{ postId:goodPostId,
                            user_Id:user_id}
                }).done(function(data){
                    $this.children('i').toggleClass('off');
                    $this.children('i').toggleClass('active');
                }).fail(function(msg){
                    alert('失敗しました');
                })
            })
        })

        
    </script>

  </body>
</html>