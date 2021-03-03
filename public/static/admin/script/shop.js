/* 幻灯片设置 */
function appendImage()
{
	var str = '';
	str += '<div class="col-md-3 image-box">'
	str += '<a class="del" href="javascript:void(0);" onclick="removeImage()">X</a>'
    str += '<table class="table table-bordered">'
    str += '<tbody>'
    str += '<tr><th>轮播图</th></tr>'
    str += '<tr>'
    str += '<td>'
    str += '<div class="luckFU height-100" >'
    str += '<div class="image" data-url="/seller/upload.html?type=shop" data-name="slides[]" onclick="luckFU()"></div>'
    str += '</div>'
    str += '</td>'
    str += '</tr>'
    str += '<tr>'
    str += '<td><input class="form-control" name="sorts[]" placeholder="请输入排序值"></td>'
    str += '</tr>'
    str += '<tr>'
    str += '<td><input class="form-control" name="links[]" placeholder="请输入链接"></td>'
    str += '</tr>'
    str += '</tbody>'
    str += '</table>'
    str += '</div>'
    $('#images').append(str);
}

$('.image-box .del').click(function() {
	removeImage();
})

function removeImage()
{
	$(event.target).parents('.image-box').remove();
}

/* 选择地址 */
$(".select-city").change(function() {
    var name = $(this).attr('name');
    if (name == 'district_id') return false;
    var id = $(this).val();
    initSelectCity(name);
    if ($(this).val() == '') return false;
    var str = '';
    $.ajax({
        url: '/api/city/getCitys',
        type: 'post',
        data: {
            parent_id: $(this).val()
        },
        success: function(data) {
            if (data.code == 200) {
                for (var i = data.data.length - 1; i >= 0; i--) {
                    str += '<option value="'+data.data[i].id+'">'+data.data[i].name+'</option>';
                }
                if (name == 'province_id') {
                    $("select[name='city_id']").show();
                    $("select[name='city_id']").append(str);
                }
                if (name == 'city_id') {
                    $("select[name='district_id']").show();
                    $("select[name='district_id']").append(str);
                }
            } else {
                layer.msg('操作异常');
                return false;
            }
        }
    })
})

function initSelectCity(name = '')
{
    if (name == 'province_id') {
        $("select[name='city_id']").hide();
        $("select[name='city_id']").html('<option value="">请选择</option>');
        $("select[name='district_id']").hide();
        $("select[name='district_id']").html('<option value="">请选择</option>');
    }
    if (name == 'city_id') {
        $("select[name='district_id']").hide();
        $("select[name='district_id']").html('<option value="">请选择</option>');
    }
}

$('#renew_year').change(function() {initRenew();})

function initRenew()
{
    $('#renew_price').html($("#renew_year option:selected").attr("data-price"));
}