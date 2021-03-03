$(document).ready(function() {
	$('.cart-checkbox').click(function() {
		if ($(this).attr('data-type') == 'all') {
			if ($(this).hasClass('select') == true) {
				$('.cart-checkbox').removeClass('select');
			} else {
				$('.cart-checkbox').addClass('select');
			}
		}
		if ($(this).attr('data-type') == 'product') {
			if ($(this).hasClass('select') == true) {
				$(this).removeClass('select');
			} else {
				$(this).addClass('select');
			}
		}
		calculateTotal(getSkus());
	})
})

function calculateTotal(skus)
{
	load = layer.load();
	$.ajax({
		url: '/api/cart/setSelected',
		type: 'post',
		data: {
			skus: skus
		},
		success: function(data) {
			layer.close(load);
			if (data.code == 401) {
				goLogin();
				return false;
			} else if (data.code == 200) {
				setSelected();
				$('#select_count').html(data.data.count);
				$('#total_price').html(data.data.total_price);
			} else {
				layer.msg(data.message, {time: 1000}, function() {
					window.location.reload();
				});
			}
		}
	})
}

function setSelected()
{
	$('.cart-checkbox').each(function() {
		if ($(this).attr('data-type') == 'all') {
			var allNode = $(this);
			var y = 1;
			$('.cart-checkbox').each(function() {
				if ($(this).hasClass('select') == false && $(this).attr('data-type') != 'all' && $(this).attr('data-type') != 'shop') y = 2;
			})
			if (y == 1) allNode.addClass('select');
			if (y == 2) allNode.removeClass('select');
		}
	})
}

function setCount(type)
{
	var thisNode = $(event.target);
	var sku = thisNode.parents('.item').attr('data-sku');
	var count = thisNode.parents('.item').find('.amount-input').val();
	if (type == 'inc') count = parseInt(count) + 1;
	if (type == 'dec') {
		if (count <= 1) return false;
		count = parseInt(count) - 1;
	}
	var load = layer.load();
	$.ajax({
		url: '/api/cart/setCount',
		type: 'post',
		data: {
			sku: sku,
			count: count
		},
		success: function(data) {
			layer.close(load);
			if (data.code == 401) {
				goLogin();
				return false;
			} else if (data.code == 200) {
				thisNode.parents('.item').find('.amount-input').val(data.data.product.count);
				thisNode.parents('.item').find('.cart-product-total').html(data.data.product.total);
				$('#select_count').html(data.data.cart.count);
				$('#total_price').html(data.data.cart.total_price);
			} else {
				layer.msg(data.message);
			}
		}
	})
}

function getSkus()
{
	var skus = '';
	$('.items .cart-checkbox').each(function() {
		if ($(this).hasClass('select') == true) {
			skus += $(this).attr('data-sku')+',';
		}
	})
	if (skus != '') skus = skus.substr(0, skus.length - 1);
	return skus;
}

function deleteCart(type = 'one', product_id = '')
{
	layer.confirm('确定删除？', function() {
		var load = layer.load();
		$.ajax({
			url: '/api/cart/delete',
			type: 'post',
			data: {
				type: type,
				product_id: product_id
			},
			success: function(data) {
				layer.close(load);
				if (data.code == 401) goLogin();
				if (data.code == 200) {
					window.location.reload();
				} else {
					layer.msg(data.message);
				}
			}
		})
	})
}

/**
 * Add Cart
 * @param string sku
 * @param int count
 * @param endFunction 1=cartFly
 */
function addCart(sku, count = 1, endFunction = 1)
{
	if (!isRealInt(count)) {
		layer.msg('产品数量不能为空');
		return false;
	}
	var eventNode = $(event.target);
	var load = layer.load();
	$.ajax({
		url: '/api/cart/addCart?sku='+sku+'&count='+count,
		type: 'post',
		success: function(data) {
			layer.close(load);
			if (data.code == 401) {
				goLogin();
				return false;
			} else if (data.code == 200) {
				if (endFunction == 1) cartFly('', eventNode, '#right_sidebar_cart');
				getCartCount();
				layer.msg('成功添加购物车');
 				return false;
			} else if (data.code == 400) {
				layer.msg(data.message);
				return false;
			}
		}
	})
}

function cartFly(image = '', event = '', target = '')
{
	var image = image == '' ? '<img style="z-index: 99" class="w-h-50" src="/static/index/images/product/product.png">' : image;
	var offset = $(target).offset();  
	var flyer = $(image);
	flyer.fly({
        start: {   
            left: $(event).offset().left - 20,   
            top: $(event).offset().top - $(window).scrollTop()   
        },   
        end: {   
            left: offset.left + 20,   
            top: offset.top - $(window).scrollTop() + 50,
            width: 0,
			height: 0
        },   
        onEnd: function() {      
            this.destroy();  
        }   
    });   
}