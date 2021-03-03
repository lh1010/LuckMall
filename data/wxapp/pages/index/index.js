import request from "../../utils/request.js";
import system from "../../api/system.js";
import adver from "../../api/adver.js";
const app = getApp();

Page({
  data: {},
  onLoad: function() {
    this.setAppName();
    this.getSlideshows();
    this.getSudokus();
    this.getSections();
  },
  // 获取项目名
  setAppName: function() {
    system.getConfig().then((res) => {
      wx.setNavigationBarTitle({
        title: res.app_name
      })
    })
  },
  // 获取轮播图
  getSlideshows: function() {
    adver.getAdver('index').then((res) => {
      if (res.values) {
        this.setData({
          slideshows: res.values
        });
      }
    });
  },
  // 获取九宫格内容
  getSudokus: function() {
    request.post('wxapi/index/getSudokus').then((res) => {
      this.setData({
        sudokus: res
      });
    });
  },
  // 获取版块商品
  getSections: function() {
    request.post('wxapi/product/getSections?site=index').then((res) => {
      this.setData({
        sections: res
      })
    });
  },
  toProductTap: function(e) {
    wx.navigateTo({
      url: "/pages/product_show/index?sku=" + e.currentTarget.dataset.sku
    })
  },
  toProductsTab: function(e) {
    wx.navigateTo({
      url: "/pages/product_list/index"
    })
  },
  jumpPage: function(e) {
    var page = e.currentTarget.dataset.url;
    if (page == '') return false;
    wx.navigateTo({
      url: page
    })
  }
})
