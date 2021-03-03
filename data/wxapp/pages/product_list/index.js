import request from "../../utils/request.js";
const app = getApp();

Page({
  data: {
    cut_ident: true,
    order_ident: 'default',
    finished: false,
    loading: true,
    loaded: true,
  },
  onLoad: function (options) {
    this.setData({
      category_id: options.category_id,
      keyword: options.keyword
    });
    this.getProducts();
  },
  switchOrderTap: function(e) {
    var order_ident = e.currentTarget.dataset.ident;
    this.setData({order_ident: order_ident, page: 1, loading: true, loaded: true, finished: false});
    this.getProducts();
    wx.pageScrollTo({
      scrollTop: 0,
      duration: 100
    })
  },
  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    if (this.data.finished == true) {
      this.setData({loading: false});
      this.getProducts();
    }
  },
  cutTap: function(e) {
    var cut_ident = this.data.cut_ident ? false : true;
    this.setData({
      cut_ident: cut_ident
    });
  },
  toProductTap: function(e) {
    wx.navigateTo({
      url: "/pages/product_show/index?sku=" + e.currentTarget.dataset.sku
    })
  },
  async getProducts() {
    wx.showLoading({title: '加载中'});
    var params = {page_size: 10};
    params.page = this.data.page ? this.data.page : 1;
    if (this.data.category_id) params.category_id = this.data.category_id;
    if (this.data.keyword) params.k = this.data.keyword;
    if (this.data.order_ident) params.sort = this.data.order_ident;
    request.post('wxapi/product/getProductsPaginate', params).then((res) => {
      this.setData({res_total: res.total});
      if (res.total == 0) return false;
      if (res.current_page < res.last_page) {
        this.setData({finished: true});
      } else {
        this.setData({finished: false, loading: true, loaded: false});
      }
      if (params.page == 1) {
        this.setData({products: res.data});
      } else {
        this.setData({products: this.data.products.concat(res.data)})
      }
      this.setData({page: parseInt(res.current_page) + parseInt(1)});
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
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