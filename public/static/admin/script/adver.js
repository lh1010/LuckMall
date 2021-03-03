/* 幻灯片设置 */
function appendImage()
{
	var str = '';
	str += '<div class="col-md-3 image-box">'
	str += '<a class="del" href="javascript:void(0);" onclick="removeImage()">X</a>'
    str += '<table class="table table-bordered adver_table">'
    str += '<tbody>'
    str += '<tr><th>广告图</th></tr>'
    str += '<tr>'
    str += '<td><div class="fmr" data-name="images[]"></div></td>'
    str += '</tr>'
    str += '<tr>'
    str += '<td><input class="form-control" name="sorts[]" placeholder="排序值"></td>'
    str += '</tr>'
    str += '<tr>'
    str += '<td><input class="form-control" name="links[]" placeholder="页面链接"></td>'
    str += '</tr>'
    str += '<tr>'
    str += '<td>'
    str += '<select class="form-control color-999" name="link_idents[]">'
    str += '<option value="1">内部页面 / 当前页打开</option>'
    str += '<option value="2">外部链接 / 新窗口打开</option>'
    str += '</select>'
    str += '</td>'
    str += '</tr>'
    str += '</tbody>'
    str += '</table>'
    str += '</div>'
    $('#image').append(str);
}

function removeImage()
{
	$(event.target).parents('.image-box').remove();
}