import request from "../../utils/request.js";
var app = getApp();

Page({
  data: {
    keyword: ''
  },
  onLoad: function() {
    this.getSearchHotWord();
  },
  getSearchHotWord: function() {
    request.get('wxapi/search/getSearchHotWord').then((res) => {
      this.setData({
        search_hot_words: res
      });
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  getKeywordValue: function(e) {
    this.setData({
      keyword: e.detail.value
    })
  },
  setKeywordTap: function(e) {
    this.setData({
      keyword: e.currentTarget.dataset.value
    })
    this.toProductsTab();
  },
  toProductsTab: function(e) {
    if (this.data.keyword == '') {
      wx.showToast({title: '请输入搜索内容', icon: 'none'});
      return false;
    }
    wx.navigateTo({
      url: "/pages/product_list/index?keyword="+this.data.keyword
    })
  },
})