$.validator.setDefaults({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    focusInvalid: false,
    errorLabelContainer: ".error-info",
    showErrors:function(errorMap, errorList) {
        if(errorList.length > 0) {
        	showError(errorList[0].message);
        }
    },
})

$("#fm_register").validate({
    rules: {
        phone: {
        	required: true,
        	phoneCN: true
        },
        password: {
        	required: true, 
        	maxlength: 20,
        	minlength: 6
        },
        code: {
        	required: true,
        },
        phoneCode: {
        	required: true,
        },
        userAgreement: {
        	required: true
        }
    },
    messages: {
        phone: {
            required: '手机号不能为空',
            phoneCN: '手机号格式不正确',
        },
        password: {
            required: '密码不能为空',
            maxlength: '密码长度必须为6到20位',
            minlength: '密码长度必须为6到20位'
        },
        code: {
            required: '验证码不能为空',
        },
        phoneCode: {
            required: '短信验证码不能为空',
        },
        userAgreement: {
            required: '请同意用户注册协议',
        },
    },
    submitHandler:function(){
    	var load = layer.load();
        $("#fm_register").ajaxSubmit(function(data) {
            layer.close(load);
			if (data.code == 400) showError(data.message);
			if (data.code == 200) window.location.href = '/account.html';
        });
    }
});

$("#fm_login").validate({
    rules: {
        username: {
        	required: true
        },
        password: {
        	required: true, 
        	maxlength: 20,
        	minlength: 6
        },
    },
    messages: {
        username: {
            required: '账户名不能为空',
        },
        password: {
            required: '密码不能为空',
            maxlength: '密码长度必须为6到20位',
            minlength: '密码长度必须为6到20位'
        },
    },
    submitHandler:function(){
    	var load = layer.load();
        $("#fm_login").ajaxSubmit(function(data) {
            layer.close(load);
			if (data.code == 400) showError(data.message);
			if (data.code == 200) window.location.href = '/account.html';
        });
    }
});

$("#fm_login_message").validate({
    rules: {
        phone: {
        	required: true,
        	phoneCN: true
        },
        phoneCode: {
        	required: true,
        },
    },
    messages: {
        phone: {
            required: '手机号不能为空',
            phoneCN: '手机号格式不正确'
        },
        phoneCode: {
            required: '短信验证码不能为空',
        },
    },
    submitHandler:function(){
    	var load = layer.load();
        $("#fm_login_message").ajaxSubmit(function(data) {
            layer.close(load);
			if (data.code == 400) showError(data.message);
			if (data.code == 200) window.location.href = '/account.html';
        });
    }
});

function validatePhone(email = '')
{
	if (email == '') {
		showError('手机号不能为空');
		return true;
	}
	var format_email = /^1[3456789]\d{9}$/;
	if (!format_email.test(email)) {
		showError('手机号格式不正确');
		return true;
	}
}

function validateCode(code = '')
{
	if (code == '') {
		showError('验证码不能为空');
		return true;
	}
}

function showError(message)
{
	scrollTo(".error-info");
	$(".error-info").show().html('<i class="iconfont">&#xe724;</i>'+message+'').delay(1500).hide(300);
}

function sendPhoneCode_register()
{
	if (validatePhone($('input[name="phone"]').val())) return false;
	if (validateCode($('input[name="code"]').val())) return false;
	var load = layer.load();
	$.ajax({
		url: '/api/sms/register',
		type: 'post',
		data: {
			phone: $('input[name="phone"]').val(),
			code: $('input[name="code"]').val()
		},
		success: function(data) {
			layer.close(load);
			if (data.code == 200) {
				afreshSend('phone_register');
				layer.msg('已发送，请注意查收');
			}
			if (data.code == 400) {
				showError(data.message);
			}
		}
	})
}

function sendPhoneCode_login()
{
	if (validatePhone($('input[name="phone"]').val())) return false;
	var load = layer.load();
	$.ajax({
		url: '/api/sms/login',
		type: 'post',
		data: {
			phone: $('input[name="phone"]').val(),
		},
		success: function(data) {
			layer.close(load);
			if (data.code == 200) {
				afreshSend('phone_login');
				layer.msg('已发送，请注意查收');
			}
			if (data.code == 400) {
				showError(data.message);
			}
		}
	})
}

var wait = 60;

function afreshSend(type)
{
	var functionStr = '';
	if (type == 'phone_register') functionStr = 'sendPhoneCode_register();';
	if (type == 'phone_login') functionStr = 'sendPhoneCode_login();';
	if (wait == 0) {
		$(".phonecode").html('获取验证码');
		$(".phonecode").attr('onclick', functionStr);
		wait = 60;
	} else {
		$(".phonecode").removeAttr("onclick");
		$(".phonecode").html('重新发送 '+wait);
		wait--;
		setTimeout(function() {afreshSend(type)}, 1000);
	}
}

function cancelCollectProduct(sku)
{
	layer.confirm('确认移除该收藏商品？', function() {
		var load = layer.load();
		$.ajax({
			url: '/api/product/collect',
			type: 'post',
			data: {
				sku: sku
			},
			success: function(data) {
				layer.closeAll();
				if (data.code == 401) {
					goLogin(); return false;
				} else if (data.code == 200) {
					window.location.reload();
				} else if (data.code == 400) {
					layer.msg(data.message);
					return false;
				} else {
					layer.msg('操作失败');
					return false;
				}
			}
		})
	})
}

$('.login-content-radio li').click(function() {
	$(this).siblings().removeClass('on');
	$(this).addClass('on');
	$('.form').hide();
	$('#'+$(this).attr('data-ident')).show();
})

// 第三方账户解绑
function unbindThirdAccount(type)
{
	layer.confirm('确认解除绑定？', function() {
		var load = layer.load();
		$.ajax({
			url: '/api/user/unbindThirdAccount',
			type: 'post',
			data: {
				type: type
			},
			success: function(data) {
				layer.close(load);
				if (data.code == 401) {
					goLogin(); return false;
				} else if (data.code == 200) {
					layer.msg('解除绑定成功', {time: 1500}, function() {window.location.reload();});
				} else if (data.code == 400) {
					layer.msg(data.message);
					return false;
				} else {
					layer.msg('操作失败');
					return false;
				}
			}
		})
	})
}

