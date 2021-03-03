<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?> - <?php echo $powered; ?></title>
<link rel="stylesheet" href="./css/install.css" />
<script src="js/jquery.js"></script>
<body>
<div class="wrap">
  <?php require 'tpl/header.php';?>
  <section class="section">
      <div class="">
        <div class="success_tip cc"> <a href="/admin" class="f16 b">安装完成，进入后台管理</a>
        <p>为了您的站点安全，安装完成后即可删除网站根目录下的 <span style="color: red; font-weight: 600">install </span>文件夹。<p>
      </div>
      <div class="bottom tac"> 
        <a href="/" class="btn">进入前台</a>
        <a href="/admin" class="btn btn_submit J_install_btn">进入后台</a>
      </div>
      <div class=""></div>
    </div>
  </section>
</div>
<?php require 'tpl/footer.php';?>
</body>
</html>