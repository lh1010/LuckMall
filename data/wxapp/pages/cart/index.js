import request from "../../utils/request.js";
import util from "../../utils/util.js";
var app = getApp();

Page({
  data: {
    is_login: false,
    manage_cart_ident: false
  },
  onShow: function() {
    this.init();
  },
  init: function() {
    this.checkLogin();
    this.setData({
      manage_cart_ident: false
    });
  },
  checkLogin: function() {
    util.checkLogin().then(is_login => {
      this.setData({is_login: is_login});
      if (!is_login) return false;
      this.getCart();
    })
  },
  getCart: function() {
    if (!this.data.is_login) return false;
    wx.showLoading({title: '加载中'});
    request.post('wxapi/cart/getCart').then((res) => {
      if (res.cart_products.length != undefined && res.cart_products.length > 0) {
        let all_selected = res.selected_count == res.cart_products.length;
        this.setData({
          cart_products: res.cart_products,
          selected_count: res.selected_count,
          selected_total_price: res.selected_total_price,
          all_selected: all_selected
        });
      } else {
        this.setData({
          cart_products: false,
          selected_count: 0,
          selected_total_price: 0,
          all_selected: 0
        });
      }
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  setCountTab: function(e) {
    let type = e.currentTarget.dataset.type;
    let sku = e.currentTarget.dataset.sku;
    if (type == 'minus' && e.currentTarget.dataset.current_count <= 1) return false;
    if (type == 'plus' && e.currentTarget.dataset.current_count >= e.currentTarget.dataset.stock) return false;
    wx.showLoading({title: '加载中'});
    var url = 'wxapi/cart/addCart';
    if (type == 'minus') {
      url = 'wxapi/cart/minusCart';
    }
    request.post(url, {sku: sku}).then((res) => {
      this.getCart();
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  setSelected: function(e) {
    wx.showLoading({title: '加载中'});
    let sku = e.currentTarget.dataset.sku;
    let selected = e.currentTarget.dataset.selected == 1 ? 0 : 1;
    request.post('wxapi/cart/setSelected', {sku: sku, selected: selected}).then((res) => {
      this.getCart();
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  allSelecedTap: function() {
    wx.showLoading({title: '加载中'});
    let type = this.data.all_selected ? 'cancel' : '';
    request.post('wxapi/cart/setAllSelected', {type: type}).then((res) => {
      this.getCart();
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  deleteCartTap: function() {
    var that = this;
    if (that.data.selected_count == 0) {
      wx.showToast({title: '请选择需要删除的产品', icon: 'none'});
      return false;
    }
    wx.showModal({
      title: '提示',
      content: '确认删除选中的产品？',
      success (res) {
        if (res.confirm) {
          wx.showLoading({title: '加载中'});
          request.post('wxapi/cart/deleteCart').then((res) => {
            that.getCart();
          }).catch((message) => {
            wx.showToast({title: message, icon: 'none'});
          });
        }
      }
    })
  },
  manageCartTap: function() {
    let manage_cart_ident = this.data.manage_cart_ident ? false : true;
    this.setData({manage_cart_ident: manage_cart_ident});
  },
  toCheckoutTap: function() {
    if (this.data.selected_count == 0) {
      wx.showToast({title: '请选择需要结算的产品', icon: 'none'});
      return false;
    }
    wx.navigateTo({
      url: "/pages/checkout/index"
    })
  },
  toIndexTap: function() {
    wx.switchTab({
      url: "/pages/index/index"
    })
  },
  toProductTap: function(e) {
    wx.navigateTo({
      url: "/pages/product_show/index?sku=" + e.currentTarget.dataset.sku
    })
  },
  loginTap: function(e) {
    wx.navigateTo({
      url: "/pages/account/auth_login"
    })
  }
})