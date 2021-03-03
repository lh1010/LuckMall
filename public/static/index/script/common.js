function scrollTo(ele, speed)
{
	if (!speed) speed = 300;
  	if (!ele) {
    	$("html, body").animate({scrollTop: 0}, speed);
  	} else {
    	if(ele.length > 0) $("html, body").animate({scrollTop: $(ele).offset().top}, speed);
  	}
}

// 对象转数组
function object_to_array(object)
{
	var array = [];
	for (var i in object) {
		array[i] = object[i];
	}
	return array;
}

/**
 * Validate is int
 * @param string val
 * @return boolean
 */
function isRealInt(val = '')
{
	if (val == '') return false;
	var reg = /^[0-9]*$/;
	if (!reg.test(val)) return false;
	return true;
}

/**
 * Validate is float
 * @param string val
 * @return boolean
 */
function isFloat(val = '')
{
	if (val == '') return false;
	var reg = /(^[0-9]\d*(\.\d*)$)|(^\d*$)/;
	if (!reg.test(val)) return false;
	return true;
}

function goLogin(type = 1)
{
	if (type == 1) {
		layer.msg('请先登录', {time: 1000}, function() {
			window.location.href = '/login.html';
		})
	}
}

function getCartCount()
{
	$.ajax({
		url: '/api/cart/getCartCount',
		type: 'post',
		success: function(data) {
			if (data.code == 401) data = 0;
			if ($('#cart_count').length > 0) $('#cart_count').html(data);
			if ($('#right_sidebar_cart_count').length > 0) $('#right_sidebar_cart_count').html(data);
		}
	})
}

// 获取url中某个参数的值
function getUrlParam(param)
{
	var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParamName,
        i;
    for (i = 0; i < sURLVariables.length; i++) {
        sParamName = sURLVariables[i].split('=');
        if (sParamName[0] === param) {
            return sParamName[1] === undefined ? true : decodeURIComponent(sParamName[1]);
        }
    }
}

// Set Search
$('.search-type ul').mouseover(function() {
	$(this).attr('style', 'height: 35px; overflow: visible;');
})

$('.search-type li').click(function() {
	if ($(this).html() == '店铺') {
		$(this).parents('ul').children('li').eq(0).html('店铺');
		$(this).parents('ul').children('li').eq(1).html('商品');
		$(this).parents('form').attr('action', '/search/shop.html');
	} else {
		$(this).parents('ul').children('li').eq(0).html('商品');
		$(this).parents('ul').children('li').eq(1).html('店铺');
		$(this).parents('form').attr('action', '/search/product.html');
	}
	$(this).parents('ul').attr('style', 'height: 35px; overflow: hidden;');
})

$(document).ready(function() {
	getCartCount();
	initAdver();
});

function initAdver()
{
	if ($('.adver-ident').length < 1) return false;
	var head_str = '';
	head_str += '<link rel="stylesheet" type="text/css" href="/static/index/plugins/Swiper/4.0.2/swiper.css">';
	head_str += '<script type="text/javascript" src="/static/index/plugins/Swiper/4.0.2/swiper.js"></script>';
	$('head').append(head_str);
	$('.adver-ident').html('<div class="loading2"></div>');
	$('.adver-ident').each(function(index, element) {
		var str = '';
		var adver_id = $(element).attr('data-adver-id');
		var adver_type = $(element).attr('data-adver-type');
		$.ajax({
			url: '/api/adver/getAdver',
			type: 'post',
			data: {
				adver_id: adver_id
			},
			success: function(data) {
				if (data.code == 200 && JSON.stringify(data.data) != "{}" && data.data.values.length > 0) {
					var values = data.data.values;
					switch(adver_type) {
						case 'default':
					    	str = setAdverDefault(values);
					        break;
					    case 'section':
					        str = setAdverSection(values);
					        break;
					}
				} else {
					$(element).remove();
				}
				$(element).html(str);
				initSwiper();
			}
		})
	})
}

function setAdverDefault(values)
{
	var str = '';
	if (values.length == 1) {
		if (values[0].link == '') {
			str += '<img src="'+values[0]['image']+'" />';
		} else {
			str += '<a href="'+values[0]['link']+'" target="'+values[0]['target']+'"><img src="'+values[0]['image']+'" /></a>';
		}
	}
	if (values.length > 1) {
		str += '<div class="swiper-container">';
		str += '<div class="swiper-wrapper">';
		for (var i = 0; i < values.length; i++) {
			if (values[i]['link'] == '') {
				str += '<div class="swiper-slide"><img src="'+values[i]['image']+'"/></div>';
			} else {
				str += '<div class="swiper-slide"><a href="'+values[i]['link']+'"'+ (values[i]['link_ident'] == '2' ? ' target="_blank"' : '') +'><img src="'+values[i]['image']+'" /></a></div>';
			}
		}
		str += '</div>';
		str += '<div class="swiper-pagination"></div>';
		str += '</div>';
	}
	return str;
}

function setAdverSection(values)
{
	var str = '';
	for (var i = 0; i < values.length; i++) {
		str += '<div class="item">';
		if (values[i]['link'] == '') {
			str += '<img src="'+values[i]['image']+'"/>';
		} else {
			str += '<a href="'+values[i]['link']+'"'+ (values[i]['link_ident'] == '2' ? ' target="_blank"' : '') +'><img src="'+values[i]['image']+'" /></a>';
		}
		str += '</div>';
	}
	return str;
}

function initSwiper()
{
	var mySwiper = new Swiper('.swiper-container', {
		autoplay: true,
		loop: true,
		observer: true,
		observeParents: true,
		pagination: {
	      el: '.swiper-pagination',
	      clickable: true,
	    },
	})
}

// 获取热搜词
function get_search_hot_word()
{
	$.ajax({
		url: '/api/search/get_search_hot_word',
		type: 'post',
		success: function(res) {
			var str = '';
			for (var i = 0; i < res.length; i++) {
				str += '<li><a href="/products.html?k='+res[i].value+'" title="'+res[i].value+'">'+res[i].value+'</a></li>';
			}
			$('#search_hot_word').html(str);
		}
	})
}
