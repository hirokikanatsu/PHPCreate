<?php 
session_start();
require_once(ROOT_PATH.'Controllers/MenuController.php');

$menu = new MenuController;
$menu->quitpagemove();
$result = $menu->myiine();

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

    <title>myiine.php</title>
  </head>
  <body>
<?php include('user_header.php'); ?>

<div class='indexwrapper'>   
    <div class="indexwrap">
        <div class="d-flex bd-highlight mb-3">
            <div class="me-auto p-2 bd-highlight"><h1 class="indexh1">myいいね一覧</h1></div>
            <div class="p-2 bd-highlight">
                <div><a href="createmenu.php" class="text-right allmenus">my献立新規作成</a></div>
            </div>
        </div>

        <div class="containerwrap">
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">献立名</th>
                    <th scope="col">更新日</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                
                <tbody>
                    
                    <?php $i=0; while($i < count($result)): ?>    
                            <tr>   
                                <th scope="row"><?php echo $i+1; ?></th>
                                <td><?php echo $result[$i]['name'];?></td>
                                <td><?php echo $result[$i]['updated_at'];?></td>
                                <td><a href='detailmenu.php?id=<?php echo $result[$i]['id'];?>'>詳細</a></td>
                                <td><a href='editmymenu.php?id=<?php echo $result[$i]['id'];?>'>編集</a></td>
                                <td><a href='deleteend.php?id=<?php echo $result[$i]['id'];?>' onclick="return confirm('削除してもよろしいですか？')">削除</a></td>                                
                            </tr>
                        <?php  $i++ ?>        
                    <?php endwhile; ?>
                   
                    
                </tbody>
            </table>
                        
        </div>
        <button type="button" class="btn btn-light text-right pageback" style="font-size: 30px;  width:350px;"><a href="mypage.php">my献立一覧に戻る</a></button>
        <button type="button" class="btn btn-info text-info pageback" style="font-size: 30px; width:200px;"><a href="index.php">トップに戻る</a></button>
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