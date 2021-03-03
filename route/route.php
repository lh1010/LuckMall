<?php

Route::group('category', function() {
	Route::get('', 'category/index');
});

Route::group('product', function() {
	Route::get(':sku', 'product/show')->pattern(['sku' => '\w+']);
});

Route::group('products', function() {
	Route::get(':category_id', 'product/products')->pattern(['category_id' => '\d+']);
	Route::get('', 'product/products');
});

Route::group('cart', function() {
	Route::get('', 'cart/index');
});

Route::group('checkout', function() {
	Route::get('', 'checkout/index');
	Route::get('onekeybuy', 'checkout/onekeybuy');
	Route::get('pay', 'checkout/pay');
});

Route::group('payment', function() {
	Route::get('', 'payment/index');
	Route::get('result', 'payment/payResult');
});

Route::get('register', 'account/register');
Route::get('login', 'account/login');
Route::get('logout', 'account/logout');

Route::group('account', function() {
	Route::get('', 'account/index');
	Route::get('order_show', 'account/order_show');
	Route::get('order', 'account/order');
	Route::get('user', 'account/user');
	Route::get('face', 'account/face');
	Route::get('address', 'account/address');
	Route::get('collect_product', 'account/collectProduct');
	Route::get('bind', 'account/bind');
	Route::get('security', 'account/security');
	Route::get('set_password', 'account/set_password');
	Route::get('set_email', 'account/set_email');
	Route::get('set_phone', 'account/set_phone');
});

Route::group('article', function() {
	Route::get(':article_id', 'article/show');
});

Route::get('help/:article_id', 'article/help_show');

Route::group('oauth', function() {
	Route::get('qq', 'oauth/qq');
	Route::get('qq_return', 'oauth/qq_return');
	Route::get('weibo', 'oauth/weibo');
	Route::get('weibo_return', 'oauth/weibo_return');
	Route::get('weixin', 'oauth/weixin');
	Route::get('weixin_return', 'oauth/weixin_return');
});