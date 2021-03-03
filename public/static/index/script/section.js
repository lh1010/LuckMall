function getTodayRecommentd()
{
	$('#today_recommend').html('<div class="loading2"></div>');
	$.ajax({
		url: '/api/product/getSection',
		type: 'post',
		data: {
			section_id: 1
		},
		success: function(res) {
			var str = '';
			if (res.code == 200 && res.data.id) {
				var values = res.data.values;
				for (var i = 0; i < values.length; i++) {
					str += '<a class="item" target="_blank" href="/product/'+values[i].sku+'.html">';
					str += '<div class="item-box">';
					str += '<div class="tag"></div>';
					str += '<img class="thumbnail" src="'+values[i].image+'">';
					str += '<div class="title">'+values[i].name+'</div>';
					str += '<div class="price">￥'+values[i].sale_price+'</div>';
					str += '</div>';
					str += '</a>';
				}
				$('#today_recommend').html(str);
			}
			if (str == '') $('#today_recommend').parents('.products').remove();
		}
	})
}
