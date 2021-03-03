import request from "../../utils/request.js";
var app = getApp();

Page({
  data: {
    manage_ident: false,
    selected_skus: []
  },
  onShow: function() {
    this.getCollectProducts();
  },
  getCollectProducts: function() {
    wx.showLoading({title: '加载中'});
    request.post('wxapi/product/getCollectProducts').then((res) => {
      this.setData({
        product_skus: res
      })
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  removeCollectProductsTap: function() {
    let selected_skus = this.data.selected_skus;
    if (selected_skus.length < 1) {
      wx.showToast({title: '请选择要移除的商品', icon: 'none'});
      return false;
    }
    wx.showLoading({title: '加载中'});
    request.post('wxapi/product/removeCollectProducts', {skus: selected_skus}).then((res) => {
      this.getCollectProducts();
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  manageTap: function() {
    let manage_ident = this.data.manage_ident ? false : true;
    this.setData({manage_ident: manage_ident});
  },
  // 选中
  selectTap: function(e) {
    let selected_skus = this.data.selected_skus;
    let current_sku = e.currentTarget.dataset.sku;
    let index = selected_skus.indexOf(current_sku);
    if (index < 0) {
      selected_skus.push(current_sku);
    } else {
      selected_skus.splice(index, 1);
    }
    this.setData({
      selected_skus: selected_skus
    });
  },
  // 全选
  allSelectTap: function() {
    let selected_skus = [];
    if (this.data.selected_skus.length < this.data.product_skus.length) {
      let product_skus = this.data.product_skus;
      for (let i = 0; i < product_skus.length; i++) {
        selected_skus.push(product_skus[i].sku);
      }
    }
    this.setData({
      selected_skus: selected_skus
    })
  },
  toProductTap: function(e) {
    wx.navigateTo({
      url: "/pages/product_show/index?sku=" + e.currentTarget.dataset.sku
    })
  },
  toIndexTap: function() {
    wx.switchTab({
      url: "/pages/index/index"
    })
  }
})