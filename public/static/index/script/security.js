var wait = 60;

function afreshSend(type, class_ident, restart = 0)
{
	if (restart == 1) wait = 60;
	var functionStr = '';
	var htmlStr = '';
	if (type == 'email') {
		functionStr = 'sendEmailCode();';
		htmlStr = '获取邮箱验证码';
	}
	if (type == 'phone') {
		functionStr = 'sendPhoneCode();';
		htmlStr = '获取手机验证码';
	}
	if (wait == 0) {
		$("."+class_ident).html('获取验证码');
		$("."+class_ident).attr('onclick', functionStr);
		wait = 60;
	} else {
		$("."+class_ident).removeAttr("onclick");
		$("."+class_ident).html('重新发送 '+wait);
		wait--;
		setTimeout(function() {afreshSend(type, class_ident)}, 1000);
	}
}

function sendEmailCode(type = 'set_email')
{
	var load = layer.load();
	var url = '';
	var data = {};
	if (type == 'set_email') {
		url = '/api/mail/set_email';
		data = {email: $('#email').val()};
	} else if (type == 'security_validate') {
		url = '/api/mail/security_validate';
	}
	var ident = $(event.currentTarget).attr('data-ident');
	$.ajax({
		url: url,
		type: 'post',
		data,
		success: function(data) {
			layer.close(load);
			if (data.code == 401) goLogin();
			if (data.code == 200) {
				afreshSend('email', ident, restart = 1);
				layer.msg('已发送，请注意查收');
			}
			if (data.code == 400) {
				layer.msg(data.message);
			}
		}
	})
}

function sendPhoneCode(type = 'set_phone')
{
	var load = layer.load();
	var url = '';
	var data = {};
	if (type == 'set_phone') {
		url = '/api/sms/set_phone';
		data = {phone: $('#phone').val()};
	} else if (type == 'security_validate') {
		url = '/api/sms/security_validate';
	}
	var ident = $(event.currentTarget).attr('data-ident');
	$.ajax({
		url: url,
		type: 'post',
		data,
		success: function(data) {
			layer.close(load);
			if (data.code == 401) goLogin();
			if (data.code == 200) {
				afreshSend('phone', ident, restart = 1);
				layer.msg('已发送，请注意查收');
			}
			if (data.code == 400) {
				layer.msg(data.message);
			}
		}
	})
}

if ($('#validateSelect').length > 0) {
	showValidateIdent($('#validateSelect').val());
	$('#validateSelect').change(function() {
		showValidateIdent($('#validateSelect').val());
	})
}

function showValidateIdent(value)
{
	$('.validateIdent').hide();
	if (value == 'password') $('.validatePasswordIdent').show(); 
	if (value == 'email') $('.validateEmailIdent').show(); 
	if (value == 'phone') $('.validatePhoneIdent').show();
}

$("#fm_set_password").validate({
    rules: {
        password: {
        	required: true, 
        	maxlength: 20,
        	minlength: 6
        },
        confirm_password: {
        	required: true, 
        	maxlength: 20,
        	minlength: 6,
        	equalTo:"#password"
        },
    },
    messages: {
        password: {
            required: '密码不能为空',
            maxlength: '密码长度必须为6到20位',
            minlength: '密码长度必须为6到20位'
        },
        confirm_password: {
            required: '确认密码不能为空',
            maxlength: '密码长度必须为6到20位',
            minlength: '密码长度必须为6到20位',
            equalTo: '密码输入不一致',
        },

    },
    submitHandler:function(){
    	var load = layer.load();
        $("#fm_set_password").ajaxSubmit(function(data) {
            layer.close(load);
			if (data.code == 401) goLogin();
			if (data.code == 200) {
 				$("#fm_set_password").hide();
 				var str = '<p class="end-info"> <i></i> 新登录密码设置成功！ </p><p>请您牢记新密码！</p><p><a href="/account/security.html">返回账户安全中心</a></p>';
 				$('.safe-con-end').html(str);
 				$('.safe-con-end').show();
 				$('.stepflex .dl3').addClass('doing');
 				return false;
			} else if (data.code == 400) {
				layer.msg(data.message);
				return false;
			}
        });
    }
});

$("#fm_set_email").validate({
    rules: {email: {required: true}},
    messages: {email: {required: '邮箱不能为空'}},
    submitHandler:function(){
    	var load = layer.load();
        $("#fm_set_email").ajaxSubmit(function(data) {
            layer.close(load);
			if (data.code == 401) goLogin();
			if (data.code == 200) {
 				$("#fm_set_email").hide();
 				var str = '<p class="end-info"> <i></i> 邮箱设置成功！ </p><p>请您牢记新邮箱！</p><p><a href="/account/security.html">返回账户安全中心</a></p>';
 				$('.safe-con-end').html(str);
 				$('.safe-con-end').show();
 				$('.stepflex .dl3').addClass('doing');
 				return false;
			} else if (data.code == 400) {
				layer.msg(data.message);
				return false;
			}
        });
    }
});

$("#fm_set_phone").validate({
    rules: {phone: {required: true}},
    messages: {phone: {required: '手机不能为空'}},
    submitHandler:function(){
    	var load = layer.load();
        $("#fm_set_phone").ajaxSubmit(function(data) {
            layer.close(load);
			if (data.code == 401) goLogin();
			if (data.code == 200) {
 				$("#fm_set_phone").hide();
 				var str = '<p class="end-info"> <i></i> 手机设置成功！ </p><p>请您牢记新手机！</p><p><a href="/account/security.html">返回账户安全中心</a></p>';
 				$('.safe-con-end').html(str);
 				$('.safe-con-end').show();
 				$('.stepflex .dl3').addClass('doing');
 				return false;
			} else if (data.code == 400) {
				layer.msg(data.message);
				return false;
			}
        });
    }
});

$("#fm_validate_security").validate({
    submitHandler:function(){
    	var load = layer.load();
        $("#fm_validate_security").ajaxSubmit(function(data) {
            layer.close(load);
			if (data.code == 401) goLogin();
			if (data.code == 200) {
 				$("#fm_validate_security").hide();
 				$('.fm_set_security').show();
 				$('.stepflex dl').eq(1).addClass('doing');
 				return false;
			} else if (data.code == 400) {
				layer.msg(data.message);
				return false;
			}
        });
    }
});