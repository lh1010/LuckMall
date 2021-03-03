function createAddress()
{
	layer.open({
		type: 2,
		skin: 'mylayer-open',
		area: ['530px', '500px'],
		title: '创建地址',
	  	content: '/index/address/createPopup',
	});
}

function updateAddress(id)
{
	layer.open({
		type: 2,
		skin: 'mylayer-open',
		area: ['530px', '500px'],
		title: '修改地址',
	  	content: '/index/address/editPopup?id='+id,
	});
}

function deleteAddress(id)
{
	layer.confirm('确认删除该条地址么？', function() {
		var load = layer.load();
		$.ajax({
			url: '/api/address/delete',
			type: 'post',
			data: {
				id: id
			},
			success: function(data) {
				layer.close(load);
				if (data.code == 401) {
					goLogin();
					return false;
				} else if (data.code == 200) {
					layer.msg('删除成功', {time: 1500}, function() {
						window.location.reload();
					})
					return false;
				} else if (data.code == 400) {
					layer.msg(data.message);
				} else {
					layer.msg('操作失败');
				}
			}
		})
	})
}

function setDefault(id)
{
	var load = layer.load();
	$.ajax({
		url: '/api/address/setDefault?id='+id,
		type: 'post',
		success: function(data) {
			layer.close(load);
			if (data.code == 401) {
				goLogin();
				return false;
			} else if (data.code == 200) {
				layer.msg('设置默认地址成功', {time: 1500}, function() {
					window.location.reload();
				})
				return false;
			} else if (data.code == 400) {
				layer.msg(data.message);
			} else {
				layer.msg('操作失败');
			}
		}
	})
}