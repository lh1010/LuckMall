function createAddress()
{
	layer.open({
		type: 2,
		skin: 'mylayer-open',
		area: ['530px', '565px'],
		title: '创建地址',
	  	content: '/index/address/createPopup',
	});
}

function updateAddress(id)
{
	layer.open({
		type: 2,
		skin: 'mylayer-open',
		area: ['530px', '565px'],
		title: '修改地址',
	  	content: '/index/address/editPopup?id='+id,
	});
}

$('.address-box').click(function() {
	$('.address-box').removeClass('active');
	$(this).addClass('active');
	$(this).find('input[name="ship_time"]').prop('checked');
	initData();
})

$('.ship-time .box').click(function() {
	$('.ship-time .box').removeClass('active');
	$(this).addClass('active');
})

$('.postage-out-box').click(function() {
	$(this).parents('.postage').find('.postage-box').removeClass('active');
	$(this).find('.postage-box').addClass('active');
})

function destroyAddress(id)
{
	layer.confirm('确认删除该条地址么？', function() {
		$.ajax({
			url: '/api/address/destroy',
			type: 'post',
			data: {
				id: id
			},
			success: function(data) {
				if (data.code == 200) {
	                window.location.reload();
				} else if (data.code == 400) {
					layer.msg(data.message);
				} else {
					layer.msg('操作失败');
				}
			}
		})
	})
}

function initData()
{
	$('.order-product').html('<div class="loading1 margin-bottom-50"></div>');
	$('#pay_info').html('计算中');
	$('.total_price').html('计算中');
	var address_id = 0;
	$('.address-box').each(function() {
		if ($(this).hasClass('active')) address_id = $(this).attr('data-address-id');
	})
	var type = $('input[name="type"]').val();
	if (type == 'cart') {
		var url = '/api/checkout';
		var data = {address_id: address_id};
	} else if (type == 'onekeybuy') {
		var url = '/api/checkout/onekeybuy';
		var data = {sku: $('input[name="sku"]').val(), count: $('input[name="count"]').val(), address_id: address_id};
	} else {
		return false;
	}
	$.ajax({
		url: url,
		type: 'post',
		data,
		success: function(res) {
			if (res.code == 401) {
				goLogin();
				return false;
			} else if (res.code == 200) {
				if (type == 'cart') {
					setInitData_cart(res.data);
				}
				if (type == 'onekeybuy') {
					setInitData_onekeybuy(res.data);
				}
				return false;
			} else if (res.code == 400) {
				layer.msg(res.message);
			} else {
				layer.msg('操作失败');
			}
		}
	})
}

function setInitData_cart(data)
{
	if (data.product == undefined) {
		layer.msg('购物车产品为空', {time:1500}, function() {
			window.location.href = '/cart';
		});
		return false;
	}

	var str = '';
		str += '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="order-product-list">';
		str += '<tbody>';
		str += '<tr>';
		str += '<th class="product-title" colspan="3">';
		str += '</th>';
		str += '<th class="product-price">单价（元）</th>';
		str += '<th class="product-amount">数量</th>';
		str += '<th class="product-sum">小计(元)</th>';
		str += '</tr>';
			for (var i = 0; i < data.product.length; i++) {
			str += '<tr class="product-list-tr">';
			str += '<td class="product-img">';
			str += '<a href="/product/'+data.product[i].sku+'.html" target="_blank" class="img"><img src="'+data.product[i].image+'"></a>';
			str += '</td>';
			str += '<td class="product-master">';
			str += '<p class="item-title"><a href="/product/'+data.product[i].sku+'.html" target="_blank">'+data.product[i].name+'</a></p>';
			str += '</td>';
			str += '<td class="product-attr">';
			if (data.product[i].specifications.length > 0) {
				for (var y = 0; y < data.product[i].specifications.length; y++) {
					str += '<p class="sku-line">'+data.product[i].specifications[y].specification_name+'：'+data.product[i].specifications[y].specification_option_value+'</p>';
				}
			}
			str += '</td>';
			str += '<td class="product-price"> ¥'+data.product[i].sale_price+' </td>';
			str += '<td class="product-amount">'+data.product[i].count+'</td>';
			str += '<td class="product-sum"><p class="color">¥'+data.product[i].total_price+'</p></td>';
			str += '</tr>';
			}
		str += '<tr>';
		str += '<td colspan="6" class="product-postage">';
		if (data.shipping_freight_total_price == 0) {
			str += '<div class="postage-price">免运费</div>';
		} else {
			str += '<div class="postage-price">¥'+data.shipping_freight_total_price+'</div>';
		}
		str += '</td>';
		str += '</tr>';
		str += '<tr>';
		str += '<td colspan="3" class="product-annex">';
		str += '<div class="memo">';
		str += '<span>买家留言：</span>';
		str += '<div class="buyer-msg">';
		str += '<textarea placeholder="选填，可填写您与卖家达成一致的要求" id="message"></textarea>';
		str += '</div>';
		str += '</div>';
		str += '</td>';
		str += '<td colspan="3" class="product-bill">';
		str += '<div class="order-pay">商品总额：<strong class="color">¥'+data.product_total_price+'</strong></div>';
		str += '</td>';
		str += '</tr>';
		str += '</tbody>';
		str += '</table>';
	
	$('.order-product').html(str);
	$('.total_price').html(data.total_price);
	var str = '';
	str += '<span>商品总额：¥'+data.product_total_price+'</span><em>+</em><span>运费：¥'+data.shipping_freight_total_price+'</span><em>=</em><span class="end color">应付款：¥'+data.total_price+'</span>';
	$('#pay_info').html(str);
}

function setInitData_onekeybuy(data)
{
	if (data.product == undefined) {
		layer.msg('产品为空', {time:1500}, function() {
			window.location.href = '/';
		});
		return false;
	}
	var str = '';
	str += '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="order-product-list">';
	str += '<tbody>';
	str += '<tr>';
	str += '<th class="product-title" colspan="3"></th>';
	str += '<th class="product-price">单价（元）</th>';
	str += '<th class="product-amount">数量</th>';
	str += '<th class="product-sum">小计(元)</th>';
	str += '</tr>';
	str += '<tr class="product-list-tr">';
	str += '<td class="product-img">';
	str += '<a href="/product/'+data.product.sku+'.html" target="_blank" class="img"><img src="'+data.product.image_430x430+'"></a>';
	str += '</td>';
	str += '<td class="product-master">';
	str += '<p class="item-title"><a href="/product/'+data.product.sku+'.html" target="_blank">'+data.product.name+'</a></p>';
	str += '</td>';
	str += '<td class="product-attr">';
	if (data.product.product_specifications.length > 0) {
		for (var x = 0; x < data.product.product_specifications.length; x++) {
			str += '<p class="sku-line">'+data.product.product_specifications[x].name+'：'+data.data.product.current_attributes[x].value+'</p>';
		}
	}
	str += '</td>';
	str += '<td class="product-price"> ¥'+data.product.sale_price+' </td>';
	str += '<td class="product-amount">'+data.product.count+'</td>';
	str += '<td class="product-sum"><p class="color">¥'+data.product.total_price+'</p></td>';
	str += '</tr>';
	str += '<tr>';
	str += '<td colspan="6" class="product-postage">';
	if (data.shipping_freight_total_price == 0) {
		str += '<div class="postage-price">免运费</div>';
	} else {
		str += '<div class="postage-price">¥'+data.shipping_freight_total_price+'</div>';
	}
	str += '</td>';
	str += '</tr>';
	str += '<tr>';
	str += '<td colspan="3" class="product-annex">';
	str += '<div class="memo">';
	str += '<span>买家留言：</span>';
	str += '<div class="buyer-msg">';
	str += '<textarea placeholder="选填，可填写您与卖家达成一致的要求" id="message"></textarea>';
	str += '</div>';
	str += '</div>';
	str += '</td>';
	str += '<td colspan="3" class="product-bill">';
	str += '<div class="order-pay">商品总额：<strong class="color">¥'+data.product_total_price+'</strong></div>';
	str += '</td>';
	str += '</tr>';
	str += '</tbody>';
	str += '</table>';
	$('.order-product').html(str);
	$('.total_price').html(data.total_price);
	var str = '';
	str += '<span>商品总额：¥'+data.product_total_price+'</span><em>+</em><span>运费：¥'+data.shipping_freight_total_price+'</span><em>=</em><span class="end color">应付款：¥'+data.total_price+'</span>';
	$('#pay_info').html(str);	
}

function createOrder()
{
	var type = $('input[name="type"]').val();
	var address_id = 0;
	$('.address-box').each(function() {
		if ($(this).hasClass('active')) address_id = $(this).attr('data-address-id');
	})
	var payment_id = $('input[name="payment_id"]:checked').val();
	if (address_id == 0 || !isRealInt(address_id)) {
		layer.msg('收货地址不能为空'); return false;
	}
	var load = layer.load();
	if (type == 'cart') {
		var data = {
			type: 'cart',
			address_id: address_id,
			message: $('#message').val()
		};
	} else if (type == 'onekeybuy') {
		var data = {
			type: 'onekeybuy',
			address_id: address_id, 
			sku: $('input[name="sku"]').val(), 
			count: $('input[name="count"]').val(),
			message: $('#message').val()
		};
	}
	$.ajax({
		url: '/api/order/create',
		type: 'post',
		data,
		success: function(res) {
			layer.close(load);
			if (res.code == 401) {
				goLogin(); return false;
			} else if (res.code == 200) {
				window.location.href = '/checkout/pay.html?order_id='+res.data.order.id;
			} else if (res.code == 400) {
				layer.msg(res.message, {time:1500}, function() {
					window.location.reload();
					return false;
				});
			} else {
				layer.msg('操作失败');
				return false;
			}
		}
	})
}


	