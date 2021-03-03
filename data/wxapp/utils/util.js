import request from "./request.js";
const app = getApp();

function checkLogin() {
  var promise = new Promise((resolve, reject) => {
    var apiUrl = app.globalData.host + '/wxapi/account/checkLogin';
    var apiParams = {}; apiParams['_token'] = app.globalData._token ? app.globalData._token : '';
    wx.request({
      url: apiUrl,
      method: 'POST',
      data: apiParams, 
      header: {'content-type': 'application/x-www-form-urlencoded'},
      success: function(res) {
        if (res.data.code == 200) {
          var is_login = true;
        } else {
          var is_login = false;
        }
        resolve(is_login);
      }
    })
  });
  return promise;
}

function toLoginPage() {
  wx.navigateTo({
    url: '/pages/account/auth_login'
  })
}

/**
 * 提示信息，延迟跳转
 * @param url 
 * @param message 
 * @param duration 
 */
function delayJump(url, message = '操作成功', duration = 1500) {
  wx.showToast({
    title: message,
    icon: 'none',
    duration: duration,
    success: function () {
      setTimeout(function() {
        wx.navigateTo({
          url: url
        })
      }, duration);
    }
  });
}

/**
 * 提示信息，延迟回退
 * @param message 
 * @param delta 回退多少步 
 * @param duration 
 */
function delayBack(message = '操作成功', delta = 1, duration = 1500) {
  wx.showToast({
    title: message,
    icon: 'none',
    duration: duration,
    success: function () {
      setTimeout(function() {
        wx.navigateBack({
          delta: delta
        });
      }, duration);
    }
  });
}

module.exports = {
  checkLogin: checkLogin,
  toLoginPage: toLoginPage,
  delayJump: delayJump,
  delayBack: delayBack
}