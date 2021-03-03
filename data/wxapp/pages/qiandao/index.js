import request from "../../utils/request.js";
import util from "../../utils/util.js";

Page({
  data: {},
  onShow: function() {
    this.initData();
    util.checkLogin().then(is_login => {
      this.setData({
        is_login: is_login
      });
    });
  },
  initData: function() {
    wx.showLoading({title: '加载中'});
    request.post('wxapi/account/getQiandaoData').then((res) => {
      this.setData({
        today_already: res.today_already,
        continuous_count: res.continuous_count,
        year_month: res.year_month,
        weeks: res.weeks,
        days: res.days
      });
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  qiandaoTap: function() {
    wx.showLoading({title: '加载中'});
    request.post('wxapi/account/qiandao').then((res) => {
      this.initData();
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  ruleTap: function() {
    wx.showToast({title: '这里是规则介绍', icon: 'none'});
  },
  jumpPage: function(e) {
    var url = e.currentTarget.dataset.url;
    wx.navigateTo({
      url: url
    })
  }
})