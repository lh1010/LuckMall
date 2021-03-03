import request from "../../utils/request.js";
import util from "../../utils/util.js";

Page({
  data: {
    is_login: false
  },
  onShow: function() {
    util.checkLogin().then(is_login => {
      this.setData({is_login: is_login});
      if (!is_login) return false;
      this.getGaneralData();
    });
    this.getProducts();
  },
  getGaneralData: function() {
    request.post('wxapi/integral/getGaneralData').then((res) => {
      this.setData({
        data: res
      })
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  getProducts: function() {
    var params = {page_size: 12};
    params.page = this.data.page ? this.data.page : 1;
    request.post('wxapi/integral/getProductsPaginate', params).then((res) => {
      this.setData({res_total: res.total});
      if (res.total == 0) return false;
      this.setData({finished: true});
      if (res.current_page >= res.last_page) {
        this.setData({
          finished: false,
          loading: false,
          loaded: true
        });
      }
      if (params.page == 1) {
        this.setData({products: res.data});
      } else {
        this.setData({products: this.data.products.concat(res.data)});
      }
      this.setData({page: parseInt(res.current_page) + parseInt(1)});
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  onReachBottom: function () {
    if (this.data.finished == true) {
      this.setData({loading: true});
      this.getProducts();
    }
  },
  jumpPage: function(e) {
    var url = e.currentTarget.dataset.url;
    wx.navigateTo({
      url: url
    })
  }
})