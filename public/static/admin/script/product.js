// product model
$("select[name=product_model_id]").change(function() {
	var product_model_id = $(this).val();
	var product_id = $('input[name=id]').val() == undefined ? 0 : $('input[name=id]').val();
	initProductGS();
	if (product_model_id == '') return false;
	var load = layer.load();
	$.ajax({
		url: '/admin/product/getProductModel',
		type: 'post',
		data: {
			product_model_id: product_model_id,
		},
		success: function(res) {
			layer.close(load);
			if (res.code == 401) {
                goLogin('admin'); return false;
            }
			if (res.code == 400) {
				layer.msg(res.message);
				return false;
			}
			if (res.code == 200) {
				$('#product_gs').show();
				var product_specifications = res.data.product_specifications;
				var product_attributes = res.data.product_attributes;
				// 销售规格
				var str = '';
				if (product_specifications.length > 0) {
					str += '<div class="form-group specification">';
					str += '<span class="col-sm-2 control-label">销售规格：</span>';
					str += '<div class="col-sm-4 form-control-static specification_box">';
					for (var i = 0; i < product_specifications.length; i++) {
						str += '<div class="items" data-id="'+product_specifications[i].id+'" data-name="'+product_specifications[i].name+'">';
						str += '<p class="specification_title">'+product_specifications[i].name+'</p>';
						for (var x = 0; x < product_specifications[i].options.length; x++) {
							str += '<label class="radio-inline iconfont"><input onclick="radioCancelSelected(this)" class="none" type="radio" name="specification_'+product_specifications[i].id+'" data-id="'+product_specifications[i].options[x].id+'" data-value="'+product_specifications[i].options[x].value+'">'+product_specifications[i].options[x].value+'</label>';
						}
						str += '</div>';
					}
					str += '<a class="btn btn-primary btn-sm" onclick="combinationSpecification();">生成组合</a>';
					str += '</div>';
					str += '</div>';
					// 销售规格组合
					str += '<div class="form-group specification none" id="specification_group">';
					str += '<span class="col-sm-2 control-label">销售规格组：</span>';
					str += '<div class="col-sm-6 form-control-static">';
					str += '<div class="row">';
					str += '<table class="table table-bordered">';
					str += '<tbody id="skus"></tbody>';
					str += '</table>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
				} else {
					str = '<span class="col-sm-2"></span><span class="col-sm-6 help-block">该产品模型中暂无销售规格选项！</span>';
				}
				$('#product_specifications').html(str);
				// 产品属性
				var str = '';
				for (var i = 0; i < product_attributes.length; i++) {
					str += '<div class="form-group">';
					str += '<span class="col-sm-2 control-label">'+product_attributes[i].name+'：</span>';
					str += '<div class="col-sm-2 input-group">';
					if (product_attributes[i].type == 'select') {
						str += '<select class="form-control" name="attributes['+product_attributes[i].id+']">';
						str += '<option value="">请选择</option>';
						for (var x = 0; x < product_attributes[i].options.length; x++) {
							str += '<option value="'+product_attributes[i].options[x].value+'">'+product_attributes[i].options[x].value+'</option>';
						}
						str += '</select>';
					} else {
						var value = product_attributes[i].value != undefined ? product_attributes[i].value : '';
						str += '<input class="form-control" name="attributes['+product_attributes[i].id+']" value="">';
					}
					str += '</div>';
					str += '</div>';
				}
				if (product_attributes.length == 0) str = '<span class="col-sm-2"></span><span class="col-sm-6 help-block">该产品模型中暂无产品属性选项！</span>';
				$('#product_attributes').html(str);
			}
		}
	})
})

// 初始化产品规格属性
function initProductGS()
{
	$('#product_gs').hide();
	initSpecification();
	initSkuImage();
	inintAttribute();
}

// 初始化销售规格
function initSpecification()
{
	$('#specification_group').hide();
	$('#skus').html('');
}

// 初始化sku图片
function initSkuImage()
{
	$('#sku_image_switch').hide();
	$('#sku_image_items').html('').hide();
}

// 初始化产品属性
function inintAttribute()
{
	$('#product_attributes').html('');
}

// 组合销售规格
function combinationSpecification()
{
	if ($('#product_specifications .items').length == 0) {
		layer.msg('生成失败！请检查销售规格配置是否正确'); return false;
	}
	// 已生成的销售规格ID
	var specification_id_selected = '';
	$('#skus .th .dynamic_add').each(function(index, element) {
		if (index == 0) {
			specification_id_selected += $(this).attr('data-id');
		} else {
			specification_id_selected += '_'+$(this).attr('data-id');
		}
	})
	// 已生成的销售选项ID
	var specification_option_id_selected = [];
	$('#skus .tr').each(function(index, element) {
		specification_option_id_selected.push($(this).attr('data-id'));
	})
	var th_data = [];
	var tr_data = [];
	// 当前选中销售规格ID
	var specification_id_str = '';
	// 当前选中销售规格选项ID
	var specification_option_id_str = '';
	// 当前选中销售规格选项值
	var specification_option_value_str = '';
	$('#product_specifications .items').each(function() {
		if ($(this).find($('input[type="radio"]:checked')).length == 1) {
			data = {};
			data.id = $(this).attr('data-id');
			data.name = $(this).attr('data-name');
			th_data.push(data);
			specification_id_str += $(this).attr('data-id') + '_';
			data = {};
			data.id = $(this).find($('input[type="radio"]:checked')).attr('data-id');
			data.name = $(this).find($('input[type="radio"]:checked')).attr('data-value');
			tr_data.push(data);
			specification_option_id_str += $(this).find($('input[type="radio"]:checked')).attr('data-id')+'_';
			specification_option_value_str += $(this).find($('input[type="radio"]:checked')).attr('data-value') + '_';
		}
	})
	if (specification_id_str != '') specification_id_str = specification_id_str.substr(0, specification_id_str.length - 1);
	if (specification_option_id_str != '') specification_option_id_str = specification_option_id_str.substr(0, specification_option_id_str.length - 1);
	if (specification_option_value_str != '') specification_option_value_str = specification_option_value_str.substr(0, specification_option_value_str.length - 1);
	// 检查销售规格
	if (specification_id_selected != '' && specification_id_selected != specification_id_str) {
		layer.msg('销售规格必须一致'); return false;
	}
	// 检查销售规格选项
	if (specification_option_id_str == '') {
		layer.msg('请选择销售规格'); return false;
	}
	if (in_array(specification_option_id_str, specification_option_id_selected)) {
		layer.msg('当前销售规格组已存在'); return false;
	}
	var tr_str = '';
	tr_str += '<tr class="tr" data-id="' + specification_option_id_str + '" data-name="' + specification_option_value_str + '">';
	for (var i = 0; i < tr_data.length; i++) {
		tr_str += '<td>'+tr_data[i].name+'</td>';
	}
	tr_str += '<td><span class="color-red">*</span> <input type="text" name="specification_option_id[' + specification_option_id_str + '][sale_price]"  class="width-80"></td>';
	tr_str += '<td><input type="text" name="specification_option_id[' + specification_option_id_str + '][stock]" class="width-50"></td>';
	tr_str += '<td><input type="text" class="width-50" name="specification_option_id[' + specification_option_id_str + '][sort]"></td>';
	tr_str += '<td><input type="text" class="width-100" name="specification_option_id[' + specification_option_id_str + '][sku]" placeholder="为空自动生成"></td>';
	tr_str += '<td><a class="btn btn-danger btn-xs" onclick="removeSpecification(this);">移除</a></td>';
	tr_str += '</tr>';
	$('#sku_image_switch').show();
	// 检查是否为二次生成
	if ($('#skus .th').length == 0) {
		var th_str = '<tr class="th">';
		for (var i = 0; i < th_data.length; i++) {
			th_str += '<td class="dynamic_add" data-id="' + th_data[i].id + '">' + th_data[i].name + '</td>';
		}
		th_str += '<td>销售价</td>';
		th_str += '<td>库存</td>';
		th_str += '<td>排序</td>';
		th_str += '<td>商家编码</td>';
		th_str += '<td>操作</td>';
		th_str += '<td><a class="btn btn-primary btn-xs" onclick="fillData();">填充</a> <i class="iconfont iconfont-question" data-toggle="tooltip" data-placement="top" data-original-title="将填充上方产品基础数据中的销售价和库存"></i></td>';
		th_str += '</tr>';
		th_str += tr_str;
		$('#skus').html(th_str);
		$('#specification_group').show();
		bootstrapConfig();
	} else {
		$('#skus').append(tr_str);
	}
}

// 移除单个销售规格组
function removeSpecification(thisNode)
{
	$(thisNode).parents('.tr').remove();
	var data_id = $(thisNode).parents('.tr').attr('data-id');
	$('#sku_image_items .specification_option_id_' + data_id).remove();
	if ($('#skus .tr').length == 0) {
		initSpecification();
		initSkuImage();	
	}
}

// 销售规格组填充基础数据
function fillData()
{
	let sale_price = $('input[name="sale_price"]').val();
	let stock = $('input[name="stock"]').val();
	if ($('#skus .tr').length == 0) return false;
	$('#skus .tr').each(function () {
		$(this).find('input').eq(0).val(sale_price);
		$(this).find('input').eq(1).val(stock);
	})
}

// input radio cancel selected
function radioCancelSelected(thisNode)
{
	$(thisNode).parents('.items').find('.radio-inline').removeClass('on');
	if ($(thisNode).data('waschecked') == true) {
		$(thisNode).prop('checked', false);
		$(thisNode).data('waschecked', false);
	} else {
		$(thisNode).prop('checked', true);
		$(thisNode).data('waschecked', true);
		$(thisNode).parents('.radio-inline').addClass('on');
	}
}

// 配置规格图
function setSkuImage()
{
	if ($('#skus .tr').length == 0) return false;
	var already = 0;
	var str = '';
	$('#skus .tr').each(function() {
		var data_name = $(this).attr('data-name').replace(/_/g, "/");
		var data_id = $(this).attr('data-id');
		if ($('#sku_image_items .specification_option_id_'+data_id).length != 1) {
			str += '<div class="form-group specification_option_id_'+data_id+'">';
			str += '<span class="col-sm-2 control-label">'+data_name+'：</span>';
			str += '<div class="col-sm-10 input-group">';
			str += '<div class="product_images">';
			str += '<div class="product_image fmr wh-80x80" data-name="images['+data_id+'][999]"></div>';
			str += '<div class="product_image sld fmr wh-80x80" data-name="images['+data_id+'][]"></div>';
			str += '<div class="product_image sld fmr wh-80x80" data-name="images['+data_id+'][]"></div>';
			str += '<div class="product_image sld fmr wh-80x80" data-name="images['+data_id+'][]"></div>';
			str += '<div class="product_image sld fmr wh-80x80" data-name="images['+data_id+'][]"></div>';
			str += '<div class="product_image sld fmr wh-80x80" data-name="images['+data_id+'][]"></div>';
			str += '<a class="btn btn-primary btn-xs margin-t-30" onclick="useProductImage(this);">使用商品图片</a>';
			str += '</div>';
			str += '</div>';
			str += '</div>';
		} else {
			already = 1;
		}
	})
	already == 1 ? $('#sku_image_items').append(str) : $('#sku_image_items').html(str).show();
}

function useProductImage(thisNode)
{
	var image_data = [];
	$('#product_image .product_image').each(function() {
		var current_image = $(this).find("input[type=hidden]").val() != undefined ? $(this).find("input[type=hidden]").val() : '';
		image_data.push(current_image);
	})
	var current_images_node = $(thisNode).parents('.product_images');
	$(thisNode).parents('.product_images').find('.product_image').each(function(index, element) {
		$(this).removeClass('uploaded');
		var str = '';
		if (image_data[index] != '') {
			$(this).addClass('uploaded');
			str += '<input name="'+$(this).attr('data-name')+'" type="hidden" value="'+image_data[index]+'">';
			str += '<img src="'+image_data[index]+'">';
			str += '<i class="iconfont fmr_remove"></i>';
		}
		$(this).html(str);
	})
}

// 选择产品分类
$('#spc1 li').click(function() {
	resetSpc(1);
	$(this).addClass('on');
	$('input[name="last_category_id"]').val($(this).attr('data-id'));
	setSpcMessage();
	$.ajax({
		url: '/admin/product/getCategorys',
		type: 'post',
		data: {
			parent_id: $(this).attr('data-id')
		},
		success: function(data) {
			var str = '<ul>';
			for (var i = data.data.length - 1; i >= 0; i--) {
				str += '<li class="iconfont" data-id="'+data.data[i].id+'">'+data.data[i].name+'</li>';
			}
			str += '</ul>';
			$('#spc2').html(str);
			$('#spc2').removeClass('on');
		}
	})
})
$('#spc2').on('click', 'li', function() {
	resetSpc(2);
	$(this).addClass('on');
	$('input[name="last_category_id"]').val($(this).attr('data-id'));
	setSpcMessage();
	$.ajax({
		url: '/admin/product/getCategorys',
		type: 'post',
		data: {
			parent_id: $(this).attr('data-id')
		},
		success: function(data) {
			var str = '<ul>';
			for (var i = data.data.length - 1; i >= 0; i--) {
				str += '<li class="iconfont" data-id="'+data.data[i].id+'">'+data.data[i].name+'</li>';
			}
			str += '</ul>';
			$('#spc3').html(str);
			$('#spc3').removeClass('on');
		}
	})
})
$('#spc3').on('click', 'li', function() {
	resetSpc(3);
	$(this).addClass('on');
	setSpcMessage();
	$('input[name="last_category_id"]').val($(this).attr('data-id'));
})
function resetSpc(ident)
{
	if (ident == 1) {
		$('#spc1 li').removeClass('on');
		$('#spc2').html('');
		$('#spc3').html('');
		$('#spc3').addClass('on');
		$('.spc-btn').removeClass('on');
		$('.spc-btn').attr('onClick', 'createSelectCategory()');
	}
	if (ident == 2) {
		$('#spc2 li').removeClass('on');
		$('#spc3').html('');
	}
	if (ident == 3) {
		$('#spc3 li').removeClass('on');
	}
}

function setSpcMessage()
{
	var str = '';
	str += '您当前选择的产品分类是：'+$('#spc1 li.on').html();
	if ($('#spc2 li.on').html() != undefined) str += ' > '+$('#spc2 li.on').html();
	if ($('#spc3 li.on').html() != undefined) str += ' > '+$('#spc3 li.on').html();
	$('.spc-message').html(str);
}

function createSelectCategory()
{
	window.location.href = '/admin/product/create.html?category_id='+$('input[name="last_category_id"]').val();
}

$("select[name='freight']").change(function() {
	if ($(this).val() == 1) $(".select-freight").hide();
	if ($(this).val() == 2) $(".select-freight").show();
})
