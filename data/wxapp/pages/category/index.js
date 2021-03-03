import request from "../../utils/request.js";
const app = getApp();

Page({
  data: {
    current_category_id: 0
  },
  onLoad: function (options) {
    this.getCategorys();
  },
  async getCategorys() {
    request.post('wxapi/product/getCategorys').then((res) => {
      if (res.length > 0) this.setData({current_category_id: res[0].id});
      this.setData({categorys: res});
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  switchTap: function(e) {
    this.setData({current_category_id: e.currentTarget.dataset.id});
  },
  toProductListTap: function(e) {
    wx.navigateTo({
      url: "/pages/product_list/index?category_id=" + e.currentTarget.dataset.id
    })
  },
  toSearchTap: function(e) {
    if (this.data.keyword) {
      wx.navigateBack({
        delta: 1
      });
    } else {
      wx.navigateTo({
        url: '/pages/search/index'
      })
    }
  },
})