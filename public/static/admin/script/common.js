$(document).ready(function() {
	fileManager();
})

function setLayout()
{
	var width = document.documentElement.clientWidth - document.getElementById('sidebar').offsetWidth;
	var height = document.documentElement.clientHeight - document.getElementById('head').offsetHeight;
	if(width >= 0) document.getElementById('main').style.width = width + 'px';
	if(height >= 0) {
		document.getElementById('sidebar').style.height = height + 'px';
		document.getElementById('main').style.height = height + 'px';
	}
}

function switchMenu()
{
	$("#sidebar .box").eq(0).show();
	$("#menu li").click(function() {
		$("#menu li").removeClass('active');
		$(this).addClass('active');
		var thisText = $(this).text();
		$("#menu li").each(function(index, element){
			if($(this).text() == thisText) {
				$("#sidebar .box").hide();
				$("#sidebar .box").eq(index).show();
			}
		});
	})
}
switchMenu();

/**
 * 头部tabs切换
 * 使用方法：选择使用 data-id="#general" data-id="#seo" 
 * 对应视图模块 <div id="gengal"> <div id="seo"> 
 * 默认显示一个视图模块，其余隐藏
 */
function switchTabs()
{
	$(".tab-box").eq(0).show();
	$("#tabs li a").click(function() {
		$("#tabs li").removeClass('active');
		$(this).parent().addClass('active');
	    $(".tab-box").hide();
	    $($(this).attr('data-id')).show();
	})
}
switchTabs();

/**
 * Bootstrap Config 
 */
function bootstrapConfig()
{
	if ($('[data-toggle="tooltip"]').length > 0) $('[data-toggle="tooltip"]').tooltip();
}
bootstrapConfig();

/* 回到顶部 */
function goTop()
{
	$('html, body').animate({scrollTop: 0}, 'slow');
}

/**
 * logout admin
 */
function logout_admin()
{
	layer.confirm('确认退出？', function() {
		layer.closeAll();
		var load = layer.load(3, {shade: [0.1,'#fff']});
		$.ajax({
			url:'/admin/account/logout',
			type:'post',
			data:{},
			success:function(data) {
				layer.close(load);
				if(data.code == 400) {
					layer.msg(data.message, {time : 1000}, function() {
						return false;
					});
				}
				if(data.code == 200) {
					layer.msg('已安全退出...', {time: 1000}, function() {
						window.location.href = '/admin/login.html';
						return false;
					});
				}
			}
		})
	})
}

function jump(url = '')
{
	window.location.href = url;
}

function goLogin(type = 'admin')
{
	if (type = 'admin') {
		layer.msg('请先登录', {time: 1500}, function() {window.location.href = '/admin/login.html';})
	}
	if (type == 'seller') {
		layer.msg('请先登录', {time: 1500}, function() {window.location.href = '/login.html';})
	}
}

function layerOpen(url = '', title = '信息', width = '90%', height = '90%') {
	layer.open({
	  	type: 2, 
	  	title: title,
	  	area: [width, height],
	  	maxmin: true,
	  	content: url,
	});
}

/**
 * 表格全选/取消全选
 */
if ($('.table tr input:checkbox').length > 0) {
	checkAll();
	$('.table tr input:checkbox').eq(0).click(function() {
		if ($(this).prop('checked') == true) {
			$('.table tr input:checkbox').prop('checked', true);
		} else {
			$('.table tr input:checkbox').prop('checked', false);
		}
	})
	$('.table tr input:checkbox').click(function() {
		checkAll();
	})
	function checkAll()
	{
		var ident = 1;
		$('.table tr input:checkbox').each(function(i) {
			if (i > 0) {
				if ($(this).prop('checked') == false) ident = 0;
			}
		})
		if (ident == 1) {
			$('.table tr input:checkbox').eq(0).prop('checked', true);
		} else {
			$('.table tr input:checkbox').eq(0).prop('checked', false);
		}
	}
}

/**
 * 检测数组中是否存在某个字符串
 * @param string search 
 * @param array array
 * @return boolean
 */
function in_array(search, array)
{
	for (var i in array) {
		if (array[i] == search) {
			return true;
		}
	}
	return false;
}

// 图片空间
function fileManager()
{
	$('body').on('click', '.fmr_remove', function(){
		$(this).parent('.fmr').removeClass('uploaded');
	    $(this).parent('.fmr').html('');
		return false;
	})

	$('body').on('click', '.fmr', function() {
		$('.fmr_current_ident').removeClass('fmr_current_ident');
		$(this).addClass('fmr_current_ident');
		layer.open({
			type: 2,
			title: '图片空间',
			area: ['937px', '480px'],
			maxmin: true,
			content: '/admin/file/fileManager',
		});
	})
}

