<?php

/**
 * LuckMall
 * ============================================================================
 * 版权所有 乐浩科技，并保留所有权利
 * ----------------------------------------------------------------------------
 * Admin Order Controller
 * ============================================================================
 * Author: Jasper
 */

namespace app\admin\controller;

use think\Request;
use think\Db;
use app\repository\admin\OrderRepository;
use app\repository\admin\PaymentRepository;
use app\repository\admin\UserRepository;
use app\repository\admin\ShippingMarkRepository;

class Order extends Base
{
    public function index(Request $request)
    {
        $params = $request->get();
        if (isset($params['start_time']) && !empty($params['start_time'])) $params['start_time'] = $params['start_time'] . ' 00:00:00';
        if (isset($params['end_time']) && !empty($params['end_time'])) $params['end_time'] = $params['end_time'] . ' 23:59:59';
        $orders = app(OrderRepository::class)->getOrdersPaginate($params);
        $this->assign('orders', $orders);
        $this->assign('statusArray', app(OrderRepository::class)->statusArray);
        $this->assign('payments', Config('payment.'));
        return $this->fetch();
    }

    public function show(Request $request)
    {
        $order = app(OrderRepository::class)->getOrder($request->param('id'));
        $user = app(UserRepository::class)->getUser(['id' => $order['user_id']]);
        $this->assign('order', $order);
        $this->assign('user', $user);
        return $this->fetch();
    }

    public function delete(Request $request)
    {
        Db::name('order')->where('id', $request->id)->update(['status' => 99]);
        return jsonSuccess();
    }

    public function setOrderStatus(Request $request)
    {
        $order_id = $request->param('order_id');
        $status = $request->param('status');
        $res = app(OrderRepository::class)->setOrderStatus($request->param('order_id'), $request->param('status'));
        return json($res);
    }

    public function shipment(Request $request)
    {
        if ($request->isPost()) {
            $res = $this->validate($request->post(), 'Order.updateShipment');
            if ($res !== true) return jsonFailed($res);
            $res = app(OrderRepository::class)->shipment($request->post(), $request->post('order_id'));
            return json($res);
        }
        $order = app(OrderRepository::class)->getOrder($request->get('order_id'));
        if (empty($order)) abort(404);
        $this->assign('order', $order);
        $shipping_marks = app(ShippingMarkRepository::class)->getMarks(['status' => 1]);
        $this->assign('shipping_marks', $shipping_marks);
        return $this->fetch();
    }
}