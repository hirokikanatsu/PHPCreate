<?php
session_start();



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

    <title>Hello, world!</title>
  </head>
  <body>
    <?php include('header.php'); ?>

    <div class="wrapper">
        <h1 class="homeh1">ユーザーアカウントを作ろう</h1>

        <div id="formContent">
            <form action='signconfirm.php' method="POST">
                <input type="text" id="name" class="fadeIn second" name="name" placeholder="ユーザー名" value="<?php if(isset($_SESSION['name'])):?><?php echo $_SESSION['name'];?><?php endif;?>">
                  <p style="color:red;"><?php if(isset($_SESSION['err']['name'])):?><?php echo $_SESSION['err']['name'];?><?php endif;?></p>
                <input type="text" id="email" class="fadeIn second" name="email" placeholder="メールアドレス" value="<?php if(isset($_SESSION['email'])):?><?php echo $_SESSION['email'];?><?php endif;?>">
                  <p style="color:red;"><?php if(isset($_SESSION['err']['email'])):?><?php echo $_SESSION['err']['email'];?><?php endif;?></p>
                <input type="text" id="password" class="fadeIn third" name="password" placeholder="パスワード" value="<?php if(isset($_SESSION['password'])):?><?php echo $_SESSION['password'];?><?php endif;?>">
                  <p style="color:red;"><?php if(isset($_SESSION['err']['password'])):?><?php echo $_SESSION['err']['password'];?><?php endif;?></p>
                <input type="submit" class="fadeIn fourth" style="font-size: 20px;" name='confirm' value="確認へ">
            </form>

            <div id="formFooter">
                <a class="underlineHover" href="home.php">戻る</a>
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