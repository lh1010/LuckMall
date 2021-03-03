import request from "../../utils/request.js";
const app = getApp();

Page({
  // 获取微信用户信息 并授权登录
  bindGetUserInfo: function(e) {
    if (e.detail.userInfo) {
      wx.login({
        success (res) {
          if (res.code) {
            wx.showLoading({title: '登录中'});
            request.post('wxapi/account/wxapp_login', {code: res.code}).then((res) => {
              // 登录成功
              if (res._token != '') {
                wx.setStorageSync('_token', res._token);
                app.globalData._token = res._token;
                wx.navigateBack({
                  delta: 1
                });
              }
              // 登录失败：新账号，绑定手机号
              if (res.code2seesion != '') {
                wx.setStorageSync('userInfo', e.detail.userInfo);
                wx.setStorageSync('code2seesion', res.code2seesion);
                wx.navigateTo({
                  url: "/pages/account/phone_login"
                })
              }
            }).catch((message) => {
              wx.showToast({title: message, icon: 'none'});
            });
          } else {
            wx.showToast({title: '授权失败', icon: 'none'});
          }
        }
      })
    }
  }
})