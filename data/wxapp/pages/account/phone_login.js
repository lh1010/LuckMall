import request from "../../utils/request.js";
const app = getApp();

Page({
  onLoad: function (options) {
    this.check();
  },
  check: function() {
    wx.showLoading({title: '加载中'});
    var userInfo = wx.getStorageSync('userInfo');
    var code2seesion = wx.getStorageSync('code2seesion');
    if (!userInfo || !code2seesion) {
      wx.navigateBack();
      return false;
    }
    this.setData({
      userInfo: userInfo,
      code2seesion: code2seesion
    });
    wx.hideLoading();
  },
  wxapp_phone_login: function(e) {
    var that = this;
    // 用户取消授权
    if (e.detail.errMsg == 'getPhoneNumber:fail user deny'){
      wx.showToast({title: '取消授权', icon: 'none'});
      return false;
    }
    wx.showLoading({title: '登录中'});
    var params = {
      iv: e.detail.iv,
      encryptedData: e.detail.encryptedData,
      code2seesion: that.data.code2seesion,
      user_info: JSON.stringify(that.data.userInfo)
    };
    request.post('wxapi/account/wxapp_login_phone', params).then((res) => {
      try {
        wx.setStorageSync('_token', res);
        app.globalData._token = res;
      } catch (e) {
        console.log(e);
      }
      wx.navigateBack({
        delta: 2
      });
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  phoneLoginTap: function() {
    wx.showToast({title: '暂不支持其他手机号登录。请使用绑定微信手机号登录。', icon: 'none'});
  },
  jumpPage: function(e) {
    var url = e.currentTarget.dataset.url;
    wx.navigateTo({
      url: url
    })
  }
})