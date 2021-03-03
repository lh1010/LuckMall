function initZoomImage()
{
	var zoomConfig = {
		borderColour: '#eee',
		lensBorder: 0,
		zoomWindowOffetx: 8
	};
	var zoomImage = $('.large-image img');
	zoomImage.elevateZoom(zoomConfig);
	$(".small-image ul li img").hover(function() {
		$('.small-image ul li img').removeClass('on');
		$(this).addClass('on');
		zoomImage.attr('src', $(this).attr("src_S"));
		zoomImage.attr('data-zoom-image', $(this).attr("src_L"));
		zoomImage.data('zoom-image', $(this).attr("src_L")).elevateZoom(zoomConfig);
	});
}

$(".top-nav li").click(function() {
	$(".top-nav li").removeClass('on');
	$(this).addClass('on');
	var top = $('.'+$(this).attr('data-key')).offset().top - 41;
	$("html, body").animate({scrollTop: top}, 0);
})

function locationTabs()
{
	var current_top = $(window).scrollTop();
	var top_nav_top = $('.top-nav').offset().top;
	var pd_specification_top = $('.pd-specification').offset().top - 42;
	var pd_detail_top = $('.pd-detail').offset().top - 42;
	$(".top-nav li").removeClass('on');
	if (current_top >= pd_detail_top) {
		$(".top-nav li").eq(1).addClass('on');
	} else {
		$(".top-nav li").eq(0).addClass('on');
	}
}

$(document).ready(function() {
	if ($('.top-nav').length > 0) {
		var top_nav_top = $('.top-nav').offset().top;
		$(window).on('scroll', function () {
			var current_top = $(window).scrollTop();
			if (current_top > top_nav_top) {
	        	$('.top-nav').addClass('fiexd');
	        	$('.pd').attr('style', 'margin-top: 41px;');
	        } else {
	        	$('.top-nav').removeClass('fiexd');
	        	$('.pd').attr('style', 'margin-top: 0px;');
	        }
	  		locationTabs();
	    });
	}
})

function setBuyCount(type)
{
	var count = $('#count').val();
	if (!isRealInt(count)) count = 0;
	if (type == 'decline') {
		count = parseInt(count) - 1;
		if (count < 1) count = 1;
	}
	if (type == 'increase') {
		count = parseInt(count) + 1;
	}
	$('#count').val(count);
	validateCount();
}

function validateCount()
{
	var count = $('#count').val();
	var stock = $('#stock').html();
	if (!isRealInt(count)) count = '';
	if (count < 1) count = '';
	if (parseInt(count) > parseInt(stock)) count = stock;
	$('#count').val(count);
	initFreight();
}

function collectProduct(sku, type = 'collect')
{
	var thisNode = $(event.target);
	if (thisNode.hasClass('iconfont')) thisNode = $(event.target).parent();
	var load = layer.load();
	$.ajax({
		url: '/api/product/collect',
		type: 'post',
		data: {
			sku: sku
		},
		success: function(data) {
			layer.close(load);
			if (data.code == 401) {
				goLogin();
			} else if (data.code == 200) {
				if (type == 'collect') {
					$(thisNode).attr('onclick', 'collectProduct('+sku+', "cancel")');
					$(thisNode).addClass('on');
				}
				if (type == 'cancel') {
					$(thisNode).attr('onclick', 'collectProduct('+sku+')');
					$(thisNode).removeClass('on');
				}
			} else if (data.code == 400) {
				layer.msg(data.message);
			} else {
				layer.mag('操作失败');
			}
		}
	})
}

/**
 * Collect Shop
 * @param int shop_id
 * @string type ''|cancel
 */
function collectShop(shop_id, type = '')
{
	var thisNode = $(event.target);
	var load = layer.load();
	$.ajax({
		url: '/api/user_collect/collectShop',
		type: 'post',
		data: {
			shop_id: shop_id,
			type: type
		},
		success: function(data) {
			layer.close(load);
			if (data.code == 401) {
				goLogin();
			} else if (data.code == 200) {
				if (thisNode.hasClass('color')) {
					thisNode.attr('onclick', 'collectShop('+shop_id+')');
					thisNode.removeClass('color');
				} else {
					thisNode.attr('onclick', 'collectShop('+shop_id+', "cancel")');
					thisNode.addClass('color');
				}
			} else if (data.code == 400) {
				layer.msg(data.message);
			} else {
				layer.msg('操作失败');
			}
		}
	})
}

$('.freight-address').click(function() {
	openFreight();
})

$('.freight-select-address .close').click(function() {
	closeFreight();
})

function openFreight()
{
	$('.freight-address').css('border-bottom', 'none');
	$('.freight-select-address').show();
}

function closeFreight()
{
	$('.freight-address').css('border-bottom', '1px solid #ccc');
	$('.freight-select-address').hide();
}

function initFreight(district_id = '')
{
	// 初始化所需参数
	var product_id = $('#product_id').val();
	var province_id = $('.freight-region-1').attr('data-id');
	var city_id = $('.freight-region-2').attr('data-id');
	if (district_id == '') district_id = $('.freight-region-3').attr('data-id');
	var count = $('#count').val();
	if (!isRealInt(count)) count = 1;
	if (province_id == '' || city_id == '') window.location.reload();
	var region_ids = '';
	if (district_id != undefined) region_ids = province_id+','+city_id+','+district_id;
	$.ajax({
		url: '/api/product/initFreight',
		type: 'post',
		data: {
			product_id: product_id,
			count: count,
			region_ids: region_ids
		},
		success: function(data) {
			if (data.code == 200) {
				$('.freight-address').html(data.data.address+' <i class="iconfont">&#xe60c;</i>');
				if (data.data.freight_price == 0) {
					$('#freight_price').html('免运费');
				} else {
					$('#freight_price').html('￥'+data.data.freight_price);
				}
				
				var str = '';
				var str = '<li class="freight-region freight-region-1" data-id="'+data.data.province_id+'" onclick="showRegions(1)"><span>'+data.data.province+'</span><i class="iconfont">&#xe60c;</i></li>';
				str += '<li class="freight-region freight-region-2" data-id="'+data.data.city_id+'" onclick="showRegions(2)"><span>'+data.data.city+'</span><i class="iconfont">&#xe60c;</i></li>';
				str += '<li class="freight-region freight-region-3" data-id="'+data.data.district_id+'" onclick="showRegions(3)"><span>'+data.data.district+'</span><i class="iconfont">&#xe60c;</i></li>';
				$('.freight-address-top').html(str);
				
				var str = '';
				for (var i = 0; i < data.data.provinces.length; i++) {
					if (data.data.provinces[i].selected == 1) {
						str += '<li onclick="regionList(1)" class="on" data-id="'+data.data.provinces[i].id+'">'+data.data.provinces[i].name+'</li>';
					} else {
						str += '<li onclick="regionList(1)" data-id="'+data.data.provinces[i].id+'">'+data.data.provinces[i].name+'</li>';
					}
				}
				$('.freight-regions-1').html(str);

				var str = '';
				for (var i = 0; i < data.data.citys.length; i++) {
					if (data.data.citys[i].selected == 1) {
						str += '<li onclick="regionList(2)" class="on" data-id="'+data.data.citys[i].id+'">'+data.data.citys[i].name+'</li>';
					} else {
						str += '<li onclick="regionList(2)" data-id="'+data.data.citys[i].id+'">'+data.data.citys[i].name+'</li>';
					}
				}
				$('.freight-regions-2').html(str);

				var str = '';
				for (var i = 0; i < data.data.districts.length; i++) {
					if (data.data.districts[i].selected == 1) {
						str += '<li onclick="initFreight('+data.data.districts[i].id+')" class="on" data-id="'+data.data.districts[i].id+'">'+data.data.districts[i].name+'</li>';
					} else {
						str += '<li onclick="initFreight('+data.data.districts[i].id+')" data-id="'+data.data.districts[i].id+'">'+data.data.districts[i].name+'</li>';
					}
				}
				$('.freight-regions-3').html(str);

				if (district_id == undefined) {
					$('.freight-regions-3').show();
				}
				closeFreight();
			} 
		}
	})
}

function regionList(level)
{
	var parent_id = $(event.target).attr('data-id');
	var name = $(event.target).html();
	$.ajax({
		url: '/api/city/getCitys',
		type: 'post',
		data: {
			parent_id: parent_id
		},
		success: function(data) {
			var str = '';
			for (var i = 0; i < data.data.length; i++) {
				if (level == 2) {
					str += '<li onclick="initFreight('+data.data[i].id+')">'+data.data[i].name+'</li>';
				} else {
					str += '<li onclick="regionList('+(parseInt(level) + 1)+')" data-id="'+data.data[i].id+'">'+data.data[i].name+'</li>';
				}
			}
			$('.freight-regions-'+(parseInt(level) + 1)).html(str);
			showRegion(level, name, parent_id);
			showRegions(parseInt(level) + 1);
		}
	})
}

function showRegions(level)
{
	$('.freight-regions').hide();
	$('.freight-regions-'+level).show();
	showRegion(level);
}

function showRegion(level, name = '请选择', id = '')
{
	$('.freight-region-'+level).show();
	$('.freight-region-'+level).find('span').html(name);
	$('.freight-region-'+level).attr('data-id', id);
	if (level == 1) {
		$('.freight-region-2').hide();
		$('.freight-region-3').hide();
	}
	if (level == 2) {
		$('.freight-region-3').hide();
	}
}

/* set url start */
$('.pl-select-general .oper-sort').click(function() {
	$('.pl-select-general .oper-sort').removeClass('color');
	$(this).addClass('color');
	$(this).children('i').hasClass('up') ? $(this).children('i').attr('class', 'iconfont down') : $(this).children('i').attr('class', 'iconfont up');
	setUrl();
})

$('.filter-btn a').click(function() {
	$(this).hasClass('on') ? $(this).removeClass('on') : $(this).addClass('on');
	setUrl();
})

$('#brands a').click(function() {
	$(this).siblings().removeClass('on');
	$(this).addClass('on');
	setUrl();
})

$('.pl-nav .label').click(function() {
	if ($(this).attr('data-ident') == 'price') {
		$('#min_price').val(''); $('#max_price').val('');
	}
	setUrl();
})

function setUrl()
{
	var load = layer.load();
	var url = '';
	var current_url = window.location.protocol+'//'+window.location.host+window.location.pathname;
	var sort = $('.pl-select-general .oper-sort.color').attr('data-ident');
	var order = $('.pl-select-general .oper-sort.color').find('i').hasClass('down') ? 2 : 1;
	var postage = $('.filter-btn .postage').hasClass('on') ? 1 : 0;
	var stock = $('.filter-btn .stock').hasClass('on') ? 1 : 0;
	if (sort != '') url = current_url+'?sort='+sort+'&order='+order;
	if ($('#min_price').val() != '') url = url == '' ? current_url + '?min_price=' + $('#min_price').val() : url + '&min_price=' + $('#min_price').val();
	if ($('#max_price').val() != '') url = url == '' ? current_url + '?max_price=' + $('#max_price').val() : url + '&max_price=' + $('#max_price').val();
	if ($('#brands a').hasClass('on')) {
		url = url == '' ? current_url + '?bid=' + $('#brands a.on').attr('data-id') : url + '&bid=' + $('#brands a.on').attr('data-id');
	}
	if (getUrlParam('k') != null) url = url == '' ? current_url + '?k=' + getUrlParam('k') : url + '&k=' + getUrlParam('k');
	if (url == '') url = current_url;
	layer.close(load);
	window.location.href = url;
}
/* set url end */

// 获取推荐产品
function getRecommendProducts()
{
	$('#shop_recommend_product').html('<div class="loading1"></div>');
	$.ajax({
		url: '/api/product/getRecommendProducts',
		type: 'post',
		success: function(data) {
			var str = '';
			if (data.data.length > 0) {
				for (var i = 0; i < data.data.length; i++) {
					str += '<div class="item" title="'+data.data[i].name+'">';
					str += '<div class="thumbnail"><a href="/product/'+data.data[i].sku+'.html" target="_blnak"><img src="'+data.data[i].image+'"></a></div>';
					str += '<div class="name"><a href="/product/'+data.data[i].sku+'.html" target="_blnak">'+data.data[i].name+'</a></div>';
					str += '<div class="price">¥'+data.data[i].sale_price+'</div>';
					str += '</div>';
				}
			} else {
				str += '<span class="color-999">暂无推荐</span>';
			}
			$('#shop_recommend_product').html(str);
		}
	})
}

// 获取随机产品
function getRandProducts()
{
	$('#shop_rand_product').html('<div class="loading1"></div>');
	$.ajax({
		url: '/api/product/getRandProducts',
		type: 'post',
		success: function(data) {
			var str = '';
			if (data.data.length > 0) {
				for (var i = 0; i < data.data.length; i++) {
					str += '<div class="item">';
					str += '<div class="thumbnail"><a href="/product/'+data.data[i].sku+'.html" target="_blnak"><img src="'+data.data[i].image+'"></a></div>';
					str += '<div class="name"><a href="/product/'+data.data[i].sku+'.html" target="_blnak">'+data.data[i].name+'</a></div>';
					str += '<div class="price"><em>¥</em>'+data.data[i].sale_price+'</div>';
					str += '</div>';
				}
			} else {
				str += '<span class="color-999">暂无内容</span>';
			}
			$('#shop_rand_product').html(str);
		}
	})
}

// 产品列表页 封面图切换
function product_skus_image_switch()
{
	$('.product_skus_image').hover(function() {
		$(this).parents('.item').find('.product_skus_image').removeClass('on');
		$(this).addClass('on');
		var sku = $(this).attr('data-sku');
		var sale_price = $(this).attr('data-sale_price');
		var image = $(this).attr('data-image');
		var url = '/product/' + sku + '.html';
		$(this).parents('.item').find('.sale-price').find('span').html(sale_price);
		$(this).parents('.item').find('.thumbnail').find('img').attr('src', image);
		$(this).parents('.item').find('.a').attr('href', url);
	})
}

function demo(thisNode)
{
	var sku = '';
	var tem = $('#tem').val();
	tem = JSON.parse(tem);
	//console.log(tem);
	var array = [];
	$('.attribute').each(function() {
		var specification_id = $(this).find('.dt').attr('id');
		var specification_option_id = $(this).find('a.on').attr('id');
		array[specification_id] = parseInt(specification_option_id);
	})

	for (var k in tem) {
		var value = object_to_array(tem[k]);
		if (array.sort().toString() == value.sort().toString()) {
			sku = k;
		}
	}

	if (sku != '') window.location.href = '/product/' + sku + '.html';

	//console.log(array);
	// $('.attribute .dd a').removeClass('on');
	// $(thisNode).removeClass('no').addClass('on');
}

