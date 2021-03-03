import request from "../../utils/request.js";
import util from "../../utils/util.js";
var app = getApp();

Page({
  data: {
    is_login: false
  },
  onShow: function() {
    this.getUser();
    this.getArticles();
  },
  getUser: function() {
    util.checkLogin().then(is_login => {
      this.setData({is_login: is_login});
      if (!is_login) return false;
      request.post('wxapi/account/getUser').then((res) => {
        this.setData({user: res});
      });
    });
  },
  getArticles: function() {
    request.post('wxapi/article/getArticles').then((res) => {
      this.setData({articles: res});
    });
  },
  jumpPage: function(e) {
    var url = e.currentTarget.dataset.url;
    wx.navigateTo({
      url: url
    })
  }
})