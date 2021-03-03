import request from "../../utils/request.js";
import payment from "../../api/payment.js";
const app = getApp();

Page({
  data: {
    popup_visible_select_payment: false,
  },
  onLoad: function (options) {
    this.setData({
      id: options.id
    });
    this.getOrder();
    // 初始化父页面数据
    var pages = getCurrentPages(); var prevPage = pages[pages.length - 2];
    prevPage.setData({
      changeStatus_order_id: '',
      changeStatus_status: ''
    })
  },
  getOrder: function() {
    wx.showLoading({title: '加载中'});
    let id = this.data.id;
    request.post('wxapi/order/getOrder', {id: id}).then((res) => {
      if (res.id == undefined) {
        wx.showToast({
          title: '订单不存在',
          icon: 'none',
          duration: 1500,
          success: function () {
            setTimeout(function() {
              wx.navigateBack({
                delta: 1
              });
            }, 1500);
          }
        });
      }
      this.setData({
        order: res
      });
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    })
  },
  // 确认收货
  confirmReceiptTap: function() {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认收货？',
      success (res) {
        if (res.confirm) {
          wx.showLoading({title: '加载中'});
          request.post('wxapi/order/confirmReceipt', {id: that.data.id}).then((res) => {
            that.getOrder();
            var pages = getCurrentPages(); var prevPage = pages[pages.length - 2];
            prevPage.setData({
              changeStatus_order_id: that.data.id,
              changeStatus_status: 40
            })
          }).catch((message) => {
            wx.showToast({title: message, icon: 'none'});
          });
        }
      }
    })
  },
  // 取消订单
  cancelOrderTap: function() {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认取消该订单？',
      success (res) {
        if (res.confirm) {
          wx.showLoading({title: '加载中'});
          request.post('wxapi/order/cancelOrder', {id: that.data.id}).then((res) => {
            that.getOrder();
            var pages = getCurrentPages(); var prevPage = pages[pages.length - 2];
            prevPage.setData({
              changeStatus_order_id: that.data.id,
              changeStatus_status: '-10'
            })
          }).catch((message) => {
            wx.showToast({title: message, icon: 'none'});
          });
        }
      }
    })
  },
  // 删除订单
  deleteOrderTap: function() {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认删除该订单？',
      success (res) {
        if (res.confirm) {
          wx.showLoading({title: '加载中'});
          request.post('wxapi/order/deleteOrder', {id: that.data.id}).then((res) => {
            wx.showToast({
              title: '该订单已删除',
              icon: 'none',
              duration: 1500,
              success: function () {
                setTimeout(function() {
                  var pages = getCurrentPages(); var prevPage = pages[pages.length - 2];
                  prevPage.setData({
                    changeStatus_order_id: that.data.id,
                    changeStatus_status: 99
                  })
                  wx.navigateBack({
                    delta: 1
                  });
                }, 1500);
              }
            });
          }).catch((message) => {
            wx.showToast({title: message, icon: 'none'});
          });
        }
      }
    })
  },
  pay: function() {
    var that = this;
    if (that.data.payment_id == undefined) return false;
    this.setData({
      popup_visible_select_payment: false
    })
    wx.showLoading({title: '加载中'});
    request.post('wxapi/payment', {order_id: that.data.id, payment_id: that.data.payment_id}).then((res) => {
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
              that.payResultFunction('支付成功');
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
        that.payResultFunction('支付成功');
      }
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  payResultFunction: function(title) {
    var that = this;
    wx.showToast({
      title: title,
      icon: 'none',
      duration: 1500,
      success: function () {
        setTimeout(function() {
          var pages = getCurrentPages(); var prevPage = pages[pages.length - 2];
          prevPage.setData({
            changeStatus_order_id: that.data.id,
            changeStatus_status: '20'
          })
          that.getOrder();
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
  showSelectPaymentTap: function() {
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