/**
 * 点击支付后显示弹窗
 * @param string url
 */
function payEndShow(url = '')
{
	var str = '';
	str += '<div class="layer-payment">';
	str += '<div class="content-info">';
	str += '<div class="warning"><i></i><span>请您在新打开页面上完成付款</span></div>';
	str += '<div class="prompt">付款完成前请不要关闭此窗口</div>';
	str += '<div class="prompt">完成付款后请根据您的情况点击下面的按钮</div>';
	str += '<div class="btns"><a href="'+url+'">已完成付款</a><a href="'+url+'">付款遇到问题</a></div>';
	str += '</div>';
	str += '</div>';
	layer.open({
		type: 1,
		skin: 'mylayer-open',
		title: '提示信息',
		area: ['360px'],
		closeBtn: 0,
	  	content: str,
	});
}

