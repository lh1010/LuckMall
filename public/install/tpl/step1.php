<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?> - <?php echo $powered; ?></title>
<link rel="stylesheet" href="./css/install.css" />
</head>
<body>
<div class="wrap">
  <?php require 'header.php';?>
  <div class="section">
    <div class="main cc">
      	<div class="pact" readonly="readonly">
          <h1 class="title">使用说明</h1>
    			<p>LuckMall是一款100%开放源码的PHP商城系统，基于ThinkPHP5开发，容易扩展，且具有强大的负载能力和稳定性。LuckMall基于Apache2.0开源协议发布，免费且不限制商业使用。用户可以自由修改，打造符合自己意愿的电商平台。</p>
    			<p>当前为 1.0 版本，1.0 版本提供了PC电脑端电商平台的基础功能，包括：产品管理、订单管理、购物流程等等一系列电商购物平台功能。</p>
          <p><b>官方地址：</b></p>
          <p><a href="http://home.luckmall.lh1010.com" target="_blank">http://home.luckmall.lh1010.com</a></p>
    			<p><b>使用须知：</b></p>
    			<p>1，LuckMall是一款免费的商城系统，在您确定使用后，我们将提供完整的源代码。在您使用过程中，请严格遵从法律法规，后续使用过程中所引起的法律责任，与LuckMall无关。</p>
    			<p>2，LuckMall演示站中的图片及内容均来自互联网，侵权请联系删除。</p>
          <p><b>使用手册：</b></p>
          <p><a href="https://www.yuque.com/luckmall/ukqmgh" target="_blank">https://www.yuque.com/luckmall/ukqmgh</a></p>
          <p><b>技术支持：</b></p>
          <p>QQ：610392592</p>
		</div>
    </div>
    <div class="bottom tac"> <a href="<?php echo $_SERVER['PHP_SELF']; ?>?step=2" class="btn">接 受</a> </div>
  </div>
</div>
<?php require 'footer.php';?>
</body>
</html>