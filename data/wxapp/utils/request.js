const app = getApp();

export default function request(url, method, data)
{
  var promise = new Promise((resolve, reject) => {
    var apiUrl = app.globalData.host + '/' + url;
    var apiParams = data ? data : {};
    apiParams['_token'] = app.globalData._token ? app.globalData._token : '';
    wx.request({
      url: apiUrl,
      method: method,
      data: apiParams, 
      header: {'content-type': 'application/x-www-form-urlencoded'},
      success: function(res) {
        wx.hideLoading();
        if (res.data.code == 200) {
          resolve(res.data.data);
        } else if (res.data.code == 401) {
          wx.navigateTo({
            url: "/pages/account/auth_login"
          })
          return false;
        } else {
          reject(res.data.message);
        }
      },
      error: function (e) {
        reject('网络异常');
      }
    })
  });
  return promise;
}

['options', 'get', 'post', 'put', 'head', 'delete', 'trace', 'connect'].forEach((method) => {
  request[method] = (url, data, opt) => request(url, method, data, opt || {})
});