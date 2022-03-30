<?php
session_start();
require_once(ROOT_PATH.'Controllers/UserController.php');

$user = new UserController;
$user->signconfirm();

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

    <title>signconfirm</title>
  </head>
  <body>
    <?php include('header.php'); ?>

    <div class="wrapper">
        <h1 class="homeh1">ユーザーアカウントを作ろう</h1>

        <div>
            
            <div class="signupmenu">名前</div>
            <div style="margin: 20px 0;" class='sessionfont'><?php if(isset($_SESSION['name'])):?><?php echo $_SESSION['name'];?><?php endif;?></div>
            <div class="signupmenu">メールアドレス</div>
            <div style="margin: 20px 0;" class='sessionfont'><?php if(isset($_SESSION['email'])):?><?php echo $_SESSION['email'];?><?php endif;?></div>
            <div class="signupmenu">パスワード</div>
            <div style="margin: 20px 0;" class='sessionfont'><?php if(isset($_SESSION['password'])):?><?php echo $_SESSION['password'];?><?php endif;?></div>
            <div class="formFooter">
                <button class="btn btn-warning" style="width:350px;"><a href="login.php" style="text-decoration: none;">本当に登録しますか？</a></button>
            </div>
            
            <div class="formFooter">
                <button class="btn btn-link" style="width:350px;"><a href="signup.php" style="text-decoration: none;">入力画面に戻る</a></button>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
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