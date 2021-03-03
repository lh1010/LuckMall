import request from "../../utils/request.js";
var app = getApp();

Page({
  data: {
    avatar: ''
  },
  onLoad: function() {
    this.getUser();
  },
  getUser: function() {
    wx.showLoading({title: '加载中'});
    request.post('wxapi/account/getUser').then((res) => {
      this.setData({user: res});
    });
  },
  updateUser: function(e) {
    let params = e.detail.value;
    wx.showLoading({title: '加载中'});
    request.post('wxapi/account/updateUser', params).then((res) => {
      this.getUser();
      wx.showToast({title: '保存成功', icon: 'success', duration: 1000});
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  uploadAvatar: function() {
    var that = this;
    wx.chooseImage({
      count: 1,
      sizeType: ['original', 'compressed'],
      sourceType: ['album', 'camera'],
      success (res) {
        let tempFilePaths = res.tempFilePaths;
        let url = app.globalData.host + '/' + 'wxapi/upload/upload_user_avatar'
        wx.uploadFile({
          url: url,
          filePath: tempFilePaths[0],
          name: 'user_avatar',
          formData: {
            '_token': app.globalData._token
          },
          success: function(res) {
            var res = JSON.parse(res.data);
            if (res.code == 200) {
              that.setData({
                avatar: res.data
              })
            } else {
              wx.showToast({title: res.message, icon: 'none'});
            }
          }
        })
      }
    })
  },
  logoutTap: function() {
    wx.showModal({
      title: '提示',
      content: '确认退出登录？',
      success (res) {
        if (res.confirm) {
          wx.showLoading({title: '加载中'});
          request.post('wxapi/account/logout').then((res) => {
            wx.removeStorageSync('_token');
            app.globalData._token = '';
            wx.navigateBack({
              delta: 1
            });
          }).catch((message) => {
            wx.showToast({title: message, icon: 'none'});
          });
        }
      }
    })
  }
})