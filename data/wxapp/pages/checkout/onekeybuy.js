import request from "../../utils/request.js";
import payment from "../../api/payment.js";
var app = getApp();

Page({
  data: {
    popup_visible_address: false,
    is_click: true,
    message: ''
  },
  onLoad: function(options) {
    this.setData({
      sku: options.sku,
      count: options.count
    });
  },
  onShow: function() {
    if (this.data.is_click) this.initData();
  },
  initData: function() {
    wx.showLoading({title: '加载中'});
    var params = {sku: this.data.sku, count: this.data.count};
    if (this.data.address_id != undefined) params.address_id = this.data.address_id;
    request.post('wxapi/checkout/onekeybuy', params).then((res) => {
      if (res != '') {
        this.setData({
          product: res.product,
          product_count: res.product_count,
          product_total_price: res.product_total_price,
          shipping_freight_total_price: res.shipping_freight_total_price,
          total_price: res.total_price,
          address: res.address,
          address_id: res.address ? res.address.id : undefined
        });
      } else {
        wx.navigateBack();
        return false;
      }
      payment.getPayments().then((res) => {
        if (res == '{}') {
          wx.showToast({title: '无可用的支付方式', icon: 'none'});
          return false;
        }
        this.setData({
          payments: res,
          payment_id: res[3].id
        });
      }).catch((message) => {
        wx.showToast({title: '获取支付方式失败', icon: 'none'});
      });
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  // 选择支付方式
  selectPaymentTap: function(e) {
    this.setData({
      payment_id: e.currentTarget.dataset.id
    });
  },
  // 选择地址
  selectAddressPopupTap: function(e) {
    wx.showLoading({title: '加载中'});
    request.post('wxapi/address/getAddresses').then((res) => {
      this.setData({
        addresses: res,
        popup_visible_address: true
      });
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  selectAddressTap: function(e) {
    this.setData({
      address_id: e.currentTarget.dataset.id
    });
    this.initData();
    this.close_popup_address();
  },
  close_popup_address: function() {
    this.setData({popup_visible_address: false});
  },
  toProductTap: function(e) {
    wx.navigateTo({
      url: "/pages/product_show/index?sku=" + e.currentTarget.dataset.sku
    })
  },
  toAccountAddressTap: function(e) {
    this.setData({
      popup_visible_address: false
    })
    wx.navigateTo({
      url: "/pages/account_address/index"
    })
  },
  // 提交订单
  createOrderTap: function() {
    var that = this;
    that.setData({is_click: false});
    wx.showLoading({title: '加载中'});
    let payment_id = that.data.payment_id;
    let address_id = that.data.address_id;
    let sku = that.data.sku;
    let count = that.data.count;
    let message = that.data.message;
    let params = {
      type: 'onekeybuy',
      payment_id: payment_id,
      address_id: address_id,
      sku: sku,
      count: count,
      message: message
    };
    request.post('wxapi/order/create', params).then((res) => {
      // 微信支付
      if (payment_id == 3) {
        let title = '支付成功';
        wx.requestPayment({
          timeStamp: res.jsApiParams.timeStamp,
          nonceStr: res.jsApiParams.nonceStr,
          package: res.jsApiParams.package,
          signType: 'MD5',
          paySign: res.jsApiParams.paySign,
          success (wx_res) {
            if (wx_res.errMsg == 'requestPayment:ok') {
              that.toOrderShow_delay(res.order_id, '支付成功');
            } else {
              that.toOrderShow_delay(res.order_id, '微信支付失败');
            }
          },
          fail (wx_res) {
            if (wx_res.errMsg == 'requestPayment:fail cancel') {
              that.toOrderShow_delay(res.order_id, '取消支付');
            } else {
              that.toOrderShow_delay(res.order_id, '微信支付失败');
            }
          }
        })
      } else {
        // 其它支付方式
        that.toOrderShow(res.order_id);
      }
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
      that.setData({is_click: true});
    })
  },
  toOrderShow: function(order_id) {
    this.setData({is_click: true});
    wx.navigateTo({
      url: "/pages/order_show/index?id=" + order_id
    })
    return false;
  },
  toOrderShow_delay: function(order_id, title) {
    var that = this;
    wx.showToast({
      title: title,
      icon: 'none',
      duration: 1500,
      success: function () {
        setTimeout(function() {
          that.toOrderShow(order_id);
        }, 1500);
      }
    });
  },
  setMessageValue: function(e) {
    this.setData({
      message: e.detail.value
    })
  }
})