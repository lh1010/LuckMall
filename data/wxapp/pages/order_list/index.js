import request from "../../utils/request.js";
import payment from "../../api/payment.js";
var app = getApp();

Page({
  data: {
    orders: '',
    order_nav_ident: 'all',
    finished: false,
    loading: true,
    loaded: true,
    changeStatus_order_id: '',
    changeStatus_status: '',
    popup_visible_select_payment: false
  },
  onLoad: function (options) {
    if (options.status != undefined) {
      this.setData({
        order_nav_ident: options.status
      });
    }
    this.getOrders();
  },
  onShow: function() {
    // order detail page update status, change current page orders data
    var changeStatus_order_id = this.data.changeStatus_order_id;
    var changeStatus_status = this.data.changeStatus_status;
    if (changeStatus_order_id != '' && changeStatus_status != '') {
      this.changeStatus(changeStatus_order_id, changeStatus_status);
    }
  },
  initData: function() {
    this.setData({
      page: 1,
      loading: true,
      loaded: true,
      finished: false
    });
  },
  switchOrderNavTap: function(e) {
    var order_nav_ident = e.currentTarget.dataset.ident;
    this.setData({
      order_nav_ident: order_nav_ident,
    });
    this.initData();
    this.getOrders();
    wx.pageScrollTo({
      scrollTop: 0,
      duration: 100
    })
  },
  getOrders: function() {
    wx.showLoading({title: '加载中'});
    var params = {page_size: 6};
    params.page = this.data.page ? this.data.page : 1;
    params.status = this.data.order_nav_ident;
    request.post('wxapi/order/getOrdersPaginate', params).then((res) => {
      if (res.current_page < res.last_page) {
        this.setData({finished: true});
      } else {
        this.setData({finished: false, loading: true, loaded: false});
      }
      if (params.page == 1) {
        this.setData({orders: res.data});
      } else {
        this.setData({orders: this.data.orders.concat(res.data)})
      }
      this.setData({page: parseInt(res.current_page) + parseInt(1)});
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    })
  },
  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    if (this.data.finished == true) {
      this.setData({loading: false});
      this.getOrders();
    }
  },
  toOrderShowTap: function(e) {
    wx.navigateTo({
      url: "/pages/order_show/index?id=" + e.currentTarget.dataset.id
    })
  },
  // 确认收货
  confirmReceiptTap: function(e) {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认收货？',
      success (res) {
        if (res.confirm) {
          wx.showLoading({title: '加载中'});
          request.post('wxapi/order/confirmReceipt', {id: e.currentTarget.dataset.id}).then((res) => {
            that.changeStatus(e.currentTarget.dataset.id, 40);
          }).catch((message) => {
            wx.showToast({title: message, icon: 'none'});
          });
        }
      }
    })
  },
  // 取消订单
  cancelOrderTap: function(e) {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认取消订单？',
      success (res) {
        if (res.confirm) {
          wx.showLoading({title: '加载中'});
          request.post('wxapi/order/cancelOrder', {id: e.currentTarget.dataset.id}).then((res) => {
            that.changeStatus(e.currentTarget.dataset.id, '-10');
          }).catch((message) => {
            wx.showToast({title: message, icon: 'none'});
          });
        }
      }
    })
  },
  // 删除订单
  deleteOrderTap: function(e) {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认删除该订单？',
      success (res) {
        if (res.confirm) {
          wx.showLoading({title: '加载中'});
          request.post('wxapi/order/deleteOrder', {id: e.currentTarget.dataset.id}).then((res) => {
            wx.showToast({
              title: '该订单已删除',
              icon: 'none',
              duration: 1000,
              success: function () {
                setTimeout(function() {
                  that.changeStatus(e.currentTarget.dataset.id, 99);
                }, 1000);
              }
            });
          }).catch((message) => {
            wx.showToast({title: message, icon: 'none'});
          });
        }
      }
    })
  },
  changeStatus: function(order_id, status) {
    var orders = this.data.orders;
    if (!orders.length > 0) return false;
    for (var i = 0; i < orders.length; i++) {
      if (orders[i].id == order_id) {
        if (status == 99) {
          orders.splice(i, 1);
        } else {
          orders[i].status = status;
        }
      }
    }
    this.setData({
      orders: orders
    });
  },
  pay: function() {
    var that = this;
    if (that.data.payment_id == undefined) return false;
    if (that.data.pay_order_id == undefined) return false;
    this.setData({
      popup_visible_select_payment: false
    })
    wx.showLoading({title: '加载中'});
    request.post('wxapi/payment', {order_id: that.data.pay_order_id, payment_id: that.data.payment_id}).then((res) => {
      if (that.data.payment_id == 3) {
        let title = '支付成功';
        wx.requestPayment({
          timeStamp: res.jsApiParams.timeStamp,
          nonceStr: res.jsApiParams.nonceStr,
          package: res.jsApiParams.package,
          signType: 'MD5',
          paySign: res.jsApiParams.paySign,
          success (wx_res) {
            if (wx_res.errMsg == 'requestPayment:ok') {
              that.payResultFunction('支付成功', that.data.pay_order_id);
            } else {
              that.payResultFunction('微信支付失败');
            }
          },
          fail (wx_res) {
            if (wx_res.errMsg == 'requestPayment:fail cancel') {
              wx.showToast({title: '取消支付', icon: 'none'});
            } else {
              wx.showToast({title: '微信支付失败', icon: 'none'});
            }
          }
        })
      } else {
        // 其它支付方式
        that.payResultFunction('支付成功', that.data.pay_order_id);
      }
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  payResultFunction: function(title, order_id) {
    var that = this;
    wx.showToast({
      title: title,
      icon: 'none',
      duration: 1500,
      success: function () {
        setTimeout(function() {
          that.changeStatus(order_id, '20');
        }, 1500);
      }
    });
  },
  selectPaymentTap: function(e) {
    this.setData({
      payment_id: e.currentTarget.dataset.id
    });
    this.pay();
  },
  showSelectPaymentTap: function(e) {
    this.setData({
      pay_order_id: e.currentTarget.dataset.id
    })
    if (this.data.payments == undefined) {
      payment.getPayments().then((res) => {
        if (res == '{}') {
          wx.showToast({title: '无可用的支付方式', icon: 'none'});
          return false;
        }
        this.setData({
          payments: res,
        })
        this.setData({
          popup_visible_select_payment: true
        })
      }).catch((message) => {
        wx.showToast({title: '获取支付方式失败', icon: 'none'});
      });
    } else {
      this.setData({
        popup_visible_select_payment: true
      })
    }
  },
  closeSelectPaymentTap: function() {
    this.setData({
      popup_visible_select_payment: false
    })
  }
})