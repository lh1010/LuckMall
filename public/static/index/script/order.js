function deleteOrder(order_id)
{
	layer.confirm('确认删除该订单？', function() {
		var load = layer.load();
		$.ajax({
			url: '/api/order/delete?order_id='+order_id,
			type: 'post',
			success: function(data) {
				layer.close(load);
				if (data.code == 401) goLogin();
				if (data.code == 200) {
	 				layer.msg(data.message, {time: 1500}, function() {
	 					window.location.reload();
	 				});
	 				return false;
				} else if (data.code == 400) {
					layer.msg(data.message);
					return false;
				}
			}
		})
	})
}

function cancelOrder(order_id)
{
	layer.confirm('确认取消该订单？', function() {
		var load = layer.load();
		$.ajax({
			url: '/api/order/cancel?order_id='+order_id,
			type: 'post',
			success: function(data) {
				layer.close(load);
				if (data.code == 401) goLogin();
				if (data.code == 200) {
	 				layer.msg(data.message, {time: 1500}, function() {
	 					window.location.reload();
	 				});
	 				return false;
				} else if (data.code == 400) {
					layer.msg(data.message);
					return false;
				}
			}
		})
	})
}

function confirmOrder(order_id)
{
	layer.confirm('确认收货？', function() {
		var load = layer.load();
		$.ajax({
			url: '/api/order/confirm?order_id='+order_id,
			type: 'post',
			success: function(data) {
				layer.close(load);
				if (data.code == 401) goLogin();
				if (data.code == 200) {
	 				layer.msg(data.message, {time: 1500}, function() {
	 					window.location.reload();
	 				});
	 				return false;
				} else if (data.code == 400) {
					layer.msg(data.message);
					return false;
				}
			}
		})
	})
}

function destoreOrder(order_id)
{
	layer.confirm('确认彻底删除该订单？', function() {
		var load = layer.load();
		$.ajax({
			url: '/api/order/destore?order_id='+order_id,
			type: 'post',
			success: function(data) {
				layer.close(load);
				if (data.code == 401) {
					goLogin();
					return false;
				} else if (data.code == 200) {
	 				layer.msg(data.message, {time: 1500}, function() {
	 					window.location.reload();
	 				});
	 				return false;
				} else if (data.code == 400) {
					layer.msg(data.message);
					return false;
				}
			}
		})
	})
}