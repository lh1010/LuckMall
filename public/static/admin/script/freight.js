$(document).ready(function() {
	$('input[name="type"]').click(function() {
		initFreight();
	})

	$('input[name="default_type"]').click(function() {
		useDefaultFreight($(this).val());
	})
})

function useDefaultFreight(value)
{
	checkFreight();
	var type = $('input[name="type"]:checked').val();
	var default_freight_length = $('#freight_box').find('.default_freight').length;
	if (value == 1) {
		if (default_freight_length == 0) $('#freight_box').prepend(getDefaultFreightStr(type));
	} else {
		if (default_freight_length > 0) $('.default_freight').remove();
	}
}

function initFreight()
{
	checkFreight();
	var type = $('input[name="type"]:checked').val();
	var default_type = $('input[name="default_type"]:checked').val();
	$('#freight').show();
	var ident = '件';
	if (type == 1) ident = '件';
	if (type == 2) ident = '重量';
	if (type == 3) ident = '体积';
	var str = '';
	str += '<thead>';
	str += '<tr>';
	str += '<th class="width-100"></th>';
	str += '<th class="width-200">配送区域</th>';
	str += '<th class="width-200">首'+ident+'</th>';
	str += '<th class="width-200">运费</th>';
	str += '<th class="width-200">续'+ident+'</th>';
	str += '<th class="width-200">运费</th>';
	str += '<th class="width-100">操作</th>';
	str += '</tr>';
	str += '</thead>';
	str += '<tbody>';
	str += '<tr>';
	str += '<td colspan="7" class="text-left">';
	str += '<a class="btn btn-success btn-xs" href="javascript:void(0);" onclick="appendFreight('+type+')">新增自定义区域 +</a>';
	str += '</td>';
	str += '</tr>';
	str += '</tbody>';
	str += '<tbody class="border-none" id="freight_box">';
	if (default_type == 1) {
		str += getDefaultFreightStr(type);
	}
	str += '</tbody>';
	$('#freight').html(str);
}

function checkFreight()
{
	var type = $('input[name="type"]:checked').val();
	if (type == undefined) return false;
}

function appendFreight(type)
{
	var ident = '件';
	if (type == 1) ident = '件';
	if (type == 2) ident = '克';
	if (type == 3) ident = '立方米';

	var str = '';
	str += '<tr>';
	str += '<td></td>';
	str += '<td><input class="form-control input_ship_area" readonly onclick="selectAreas()"><input type="hidden" name="ship_area[]" /></td>';
	str += '<td><div class="input-group"><input class="form-control" name="first_key[]"><span class="input-group-addon">'+ident+'</span></div></td>';
	str += '<td><div class="input-group"><input class="form-control" name="first_value[]"><span class="input-group-addon">元</span></div></td>';
	str += '<td><div class="input-group"><input class="form-control" name="second_key[]"><span class="input-group-addon">'+ident+'</span></div></td>';
	str += '<td><div class="input-group"><input class="form-control" name="second_value[]"><span class="input-group-addon">元</span></div></td>';
	str += '<td style="vertical-align: middle !important"><a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="deleteFreight()">删除</a></td>';
	str += '</tr>';
    $('#freight_box').append(str);
}

function getDefaultFreightStr(type)
{
	var ident = '件';
	if (type == 1) ident = '件';
	if (type == 2) ident = '克';
	if (type == 3) ident = '立方米';
	var str = '';
	str += '<tr class="default_freight">';
	str += '<td style="vertical-align: middle !important;">默认模板</td>';
	str += '<td><input class="form-control input_ship_area" readonly value="全国"><input type="hidden" name="ship_area[]" value="0" /></td>';
	str += '<td><div class="input-group"><input class="form-control" name="first_key[]"><span class="input-group-addon">'+ident+'</span></div></td>';
	str += '<td><div class="input-group"><input class="form-control" name="first_value[]"><span class="input-group-addon">元</span></div></td>';
	str += '<td><div class="input-group"><input class="form-control" name="second_key[]"><span class="input-group-addon">'+ident+'</span></div></td>';
	str += '<td><div class="input-group"><input class="form-control" name="second_value[]"><span class="input-group-addon">元</span></div></td>';
	str += '</tr>';
	return str;
}

function deleteFreight()
{
	var thisNode = $(event.target);
	thisNode.parent().parent().remove();
}

function selectAreas()
{
	resetShipArea();
	$('.input_ship_area').removeClass('input_ship_area_clicked');
	$(event.target).addClass('input_ship_area_clicked');
	$('.ship-area-selected .checkbox').html('');
	layer.open({
	  	type: 1, 
	  	title: '选择地址',
	  	area: ['600px', '400px'],
	  	maxmin: true,
	  	content: $('.ship-area'),
	});
}

function getCistys(type = 'city')
{
	var thisNode = $(event.target);
	var parent_id = thisNode.val();
	var str = '';
	$('select[name="area"]').hide();
	$('select[name="area"]').html('');
	if (type == 'city') {
		$('select[name="city"]').hide();
		$('select[name="city"]').html('');
		str += '<option value="">选择城市</option>';
	} 
	if (type == 'area') {
		str += '<option value="">选择区域</option>';
	}
	if (parent_id == '' || parent_id == 0) {
		return false;
	}
	var load = layer.load();
	$.ajax({
		url: '/admin/city/getCitys?parent_id='+parent_id,
		type: 'get',
		success: function(data) {
			layer.close(load);
			if (data.code != 200) {layer.msg('api 异常');}
			
			for (var i = 0; i < data.data.length; i++) {
				str += '<option value="'+data.data[i].id+'">'+data.data[i].name+'</option>';
			}
			$('select[name="'+type+'"]').show();
			$('select[name="'+type+'"]').html(str);
		}
	})
}

function addCity()
{
	var str = '';
	var input_value = '';
	var input_text = '';
	var area_id = $('select[name="area"]').val();
	var city_id = $('select[name="city"]').val();
	var province_id = $('select[name="province"]').val();
	if (province_id != null && province_id != '') {
		input_value = province_id;
		input_text = $('select[name="province"] option:selected').text();
		if (city_id != null && city_id != '') {
			input_value = city_id;
			input_text = $('select[name="city"] option:selected').text();
			if (area_id != null && area_id != '') {
				input_value = area_id;
				input_text = $('select[name="area"] option:selected').text();
			}
		}
	}
	if (input_value == '' || input_text == '') {
		layer.msg('请选择地址');
		return false;
	}
	str = '<label><input type="checkbox" checked="true" value="'+input_value+'">'+input_text+'</label>';

	if ($('.ship-area-selected .checkbox').html() != '') {
		if ($('.ship-area-selected .checkbox').html().search(str) != -1) {
			layer.msg('该地址已存在');
			return false;
		}
	}

	$('.ship-area-selected').show();
	$('.ship-area-selected .checkbox').append(str);
}

function affirmCity()
{
	if ($('.ship-area-selected input').length <= 0) {
		layer.msg('请添加地址');
		return false;
	}
	var text = '';
	var ids = '';
	$('.ship-area-selected input').each(function() {
		if ($(this).prop('checked')) {
			ids += $(this).val()+',';
			text += $(this).parent().text()+',';
		}
	})

	ids = ids.substr(0, ids.length - 1);
	text = text.substr(0, text.length - 1);
	$('.input_ship_area_clicked').val(text);
	$('.input_ship_area_clicked').next().val(ids);
	layer.closeAll();
}

function resetShipArea()
{
	$('select[name="city"]').hide();
	$('select[name="city"]').html('');
	$('select[name="area"]').hide();
	$('select[name="area"]').html('');
	$('.ship-area-selected .checkbox').html('');
}  