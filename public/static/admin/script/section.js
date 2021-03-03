function getProducts()
{
    if ($("#cl_category_id").val() == '' && $("#cl_product_name").val() == '') {
        layer.msg('请输入搜索内容');
        return false;
    }
    var load = layer.load();
    var exclude_product_ids = '';
    $("input[name='productIds[]'").each(function() {
        exclude_product_ids += $(this).val()+',';
    })
    $.ajax({
        url: '/admin/product/getProducts',
        type: 'post',
        data: {
            category_id: $("#cl_category_id").val(),
            keyword: $("#cl_product_name").val(),
            exclude_product_ids: exclude_product_ids
        },
        success: function(data) {
            layer.close(load);
            if (data.code == 401) {
                goLogin();
            } else if (data.code == 200) {
                var str = '';
                if (data.data.length > 0) {
                    $("#correlation-box-message").hide();
                    for (var i = 0; i < data.data.length; i++) {
                        console.log(data.data[i].brand_name);
                        if ($("input[name=id]").val() != undefined) {
                            str += '<tr class="bg-info text-white">';
                        } else {
                            str += '<tr>';
                        }
                        str += '<td><input type="checkbox" name="productIds[]" value="'+data.data[i].id+'"></td>'
                        str += '<td>' + data.data[i].id + '</td>';
                        str += '<td>' + data.data[i].name + '</td>';
                        str += '<td>' + data.data[i].full_category_name + '</td>';
                        str += '<td>' + (data.data[i].brand_name ? data.data[i].brand_name : '无') + '</td>';
                        if (data.data[i].status == 1) {
                            str += '<td><span class="label label-success">上架</span></td>';
                        } else {
                            str += '<td><span class="label label-danger radius">下架</span></td>';
                        }
                        str += '</tr>';
                    }
                    $("#correlation-box").append(str);
                } else {
                    layer.msg('查询结果为空');
                }
            } else if (data.code == 400) {
                layer.msg(data.message);
            } else {
                layer.msg('操作失败');
            }
        }
    })
}