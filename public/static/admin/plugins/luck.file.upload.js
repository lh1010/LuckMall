var default_upload_img = '/static/seller/images/upload-button.jpg';

$('.luckFU .image').click(function() {
	luckFU();
})

function luckFU()
{
	var thisNode = $(event.target).parents('.luckFU').find('.image');
	var url = thisNode.attr('data-url');
	var name = thisNode.attr('data-name') == undefined ? 'file' : thisNode.attr('data-name');
    var str = '';
	str += '<form action="'+url+'" method="post" id="luckFU_form" enctype="multipart/form-data" style="display:none" >'
	str += '	<input type="file" name="file" id="luckFU_file" />'
	str += '</form>'
	$(document.body).append(str);
	$("#luckFU_file").click();

	$("#luckFU_file").change(function() {
		$("#luckFU_form").ajaxSubmit(function(data) {
			if (data.code == 401) {
	            goLogin();
	        } else if (data.code == 200) {
	        	$("#luckFU_form").remove();
	        	var str = '';
	        	str += '<a class="del" href="javascript:void(0);" onclick="luckFU_delImage()">X</a>';
        		str += '<img src="'+data.data+'" />';
        		str += '<input type="hidden" name="'+name+'" value="'+data.data+'" />';
        		thisNode.html(str);
        		thisNode.addClass('uploaded');
	        } else if (data.code == 400) {
	            layer.msg(data.message);
	        } else {
	            layer.msg('上传失败');
	        }
		});
	})
}

$('.luckFU .del').click(function() {
	luckFU_delImage();
})

function luckFU_delImage()
{
	var str = '';
	var thisNode = $(event.target);
	if (thisNode.parent().find("input[type=hidden]").length > 0) {
		str += '<input type="hidden" name="'+thisNode.parent().find("input[type=hidden]").attr('name')+'" value=""/>';
	}
	thisNode.parent().removeClass('uploaded');
	thisNode.parent().html(str);
	event.stopPropagation();
	return false;
}

