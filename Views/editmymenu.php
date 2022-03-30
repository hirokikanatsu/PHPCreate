<?php 
session_start();
require_once(ROOT_PATH.'Controllers/MenuController.php');

$menu = new MenuController;
$menu->quitpagemove();
$result = $menu->editmymenu();
$menu->settoken();

var_dump($_SESSION['menuimg']);


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

    <title>editmymenu</title>
  </head>
  <body>
  <?php include('user_header.php'); ?>

<div class='indexwrapper'>   
    <div class="indexwrap">
        <div class="d-flex bd-highlight mb-3">
            <div class="me-auto p-2 bd-highlight"><h1 class="indexh1">my献立編集</h1></div>
        </div>

        <div class="containerwrap">
            <form action="editend.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3 row">
                    <label for="" class="col-sm-2 col-form-label" style="font-size: 30px;">献立名</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="menuname" placeholder="献立名" value="<?php if(isset($result['name'])):?><?php echo $result['name'];?><?php elseif(isset($_SESSION['edit']['editmenuname'])):?><?= $_SESSION['edit']['editmenuname']; ?><?php endif;?>">
                        <p style="color:red;"><?php if(isset($_SESSION['edit']['menuedit'])):?><?php echo $_SESSION['edit']['menuedit'];?><?php endif;?></p>
                    </div>
                    <label for="" class="col-sm-2 col-form-label" style="font-size: 30px;">食材</label>
                    <div class="col-sm-10">
                        <textarea style="margin-bottom: 20px;" class="form-control formarea" name="item"><?php if(isset($result['item'])):?><?php echo $result['item'];?><?php elseif(isset($_SESSION['edit']['edititem'])):?><?= $_SESSION['edit']['edititem']; ?><?php endif;?></textarea>
                        <p style="color:red;"><?php if(isset($_SESSION['edit']['itemedit'])):?><?php echo $_SESSION['edit']['itemedit'];?><?php endif;?></p>
                    </div>
                    <label for="" class="col-sm-2 col-form-label" style="font-size: 30px;">作り方</label>
                    <div class="col-sm-10">
                        <textarea style="margin-bottom: 20px;" class="form-control formarea" name="howtomake" ><?php if(isset($result['howtomake'])):?><?php echo $result['howtomake'];?><?php elseif(isset($_SESSION['edit']['edithowtomake'])):?><?= $_SESSION['edit']['edithowtomake']; ?><?php endif;?></textarea>
                        <p style="color:red;"><?php if(isset($_SESSION['edit']['howtomakeedit'])):?><?php echo $_SESSION['edit']['howtomakeedit'];?><?php endif;?></p>
                    </div>
                    <label for="" class="col-sm-2 col-form-label" style="font-size: 30px;">価格</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="price" placeholder="価格" value="<?php if(isset($result['price'])):?><?php echo $result['price'];?><?php elseif(isset($_SESSION['edit']['editprice'])):?><?= $_SESSION['edit']['editprice']; ?><?php endif;?>">円
                        <p style="color:red;"><?php if(isset($_SESSION['edit']['priceedit'])):?><?php echo $_SESSION['edit']['priceedit'];?><?php endif;?></p>
                    </div>
                    <label class="col-sm-2 col-form-label" style="font-size: 30px;">ジャンル</label>
                    <select class="form-select col-sm-10" name='genre' style="width:500px; margin:20px 0 0 20px;" aria-label="Default select example">
                        <option value="1">米</option>
                        <option value="2">サラダ</option>
                        <option value="3">汁物</option>
                        <option value="4">パスタ</option>
                        <option value="5">メインディッシュ</option>
                    </select>
                    <p style="color:red;"><?php if(isset($_SESSION['edit']['genreedit'])):?><?php echo $_SESSION['edit']['genreedit'];?><?php endif;?></p>
                    <div class="mb-3">
                        <label class="col-sm-2 col-form-label" style="font-size: 30px;">献立画像</label>
                        <input class="filecss col-sm-10" style="width:500px; margin-left:10px;" type="file" name="image">
                        <p style="color:red;"><?php if(isset($_SESSION['edit']['file'])):?><?php echo $_SESSION['edit']['file'];?><?php endif;?></p>
                    </div>  
                </div>
                
                <input type="hidden" name='token' value="<?php echo  $menu->settoken(); ?>">
                
                <input type="submit" class=" fadeIn fourth" style="font-size: 30px;" value="編集" onclick="return confirm('編集してもよろしいですか？')">
            </form>   
        </div>
        
        <button type="button" class="btn btn-info text-right pageback" style="font-size: 30px;" ><a href="mypage.php">戻る</a></button>
        
        
    </div>

    <?php include('footer.php'); ?>
</div>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>