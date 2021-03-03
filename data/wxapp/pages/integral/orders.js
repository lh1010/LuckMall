import request from "../../utils/request.js";

Page({
  onShow: function() {
    this.getOrders();
  },
  getOrders: function() {
    var params = {page_size: 10};
    params.page = this.data.page ? this.data.page : 1;
    request.post('wxapi/integral/getOrdersPaginate', params).then((res) => {
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
        this.setData({orders: res.data});
      } else {
        this.setData({orders: this.data.orders.concat(res.data)});
      }
      this.setData({page: parseInt(res.current_page) + parseInt(1)});
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  onReachBottom: function () {
    if (this.data.finished == true) {
      this.setData({loading: true});
      this.getOrders();
    }
  },
  jumpPage: function(e) {
    var url = e.currentTarget.dataset.url;
    wx.navigateTo({
      url: url
    })
  }
})