import request from "../../utils/request.js";
var app = getApp();

Page({
  data: {},
  onShow: function() {
    this.getAddresses();
  },
  getAddresses:function() {
    wx.showLoading({title: '加载中'});
    request.post('wxapi/address/getAddresses').then((res) => {
      let addresses_total = res.length > 0 ? res.length : 0;
      this.setData({
        addresses_total: addresses_total,
        addresses: res
      });
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  editAddressTap: function(e) {
    wx.navigateTo({
      url: '/pages/account_address/edit?id=' + e.currentTarget.dataset.id
    })
  },
  createAddressTap: function() {
    wx.navigateTo({
      url: '/pages/account_address/create'
    })
  },
  setDefaultTap: function(e) {
    wx.showLoading({title: '加载中'});
    request.post('wxapi/address/setDefault', {id: e.currentTarget.dataset.id}).then((res) => {
      this.onShow();
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  deleteAddressTap: function(e) {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认删除该地址？',
      success (res) {
        if (res.confirm) {
          wx.showLoading({title: '加载中'});
          request.post('wxapi/address/delete', {id: e.currentTarget.dataset.id}).then((res) => {
            that.onShow();
          }).catch((message) => {
            wx.showToast({title: message, icon: 'none'});
          });
        }
      }
    })
  }
})