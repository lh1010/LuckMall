import request from "../../utils/request.js";

Page({
  onShow: function (options) {
    this.initLogsData();
    this.getGaneralData();
    this.getLogs();
  },
  initLogsData: function() {
    this.setData({
      loading: false,
      loaded: false,
      page: 1
    })
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
  getLogs: function() {
    var params = {};
    params.page = this.data.page ? this.data.page : 1;
    request.post('wxapi/integral/getLogsPaginate', params).then((res) => {
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
        this.setData({logs: res.data});
      } else {
        this.setData({logs: this.data.logs.concat(res.data)});
      }
      this.setData({page: parseInt(res.current_page) + parseInt(1)});
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  onReachBottom: function () {
    if (this.data.finished == true) {
      this.setData({loading: true});
      this.getLogs();
    }
  },
  jumpPage: function(e) {
    var url = e.currentTarget.dataset.url;
    wx.navigateTo({
      url: url
    })
  }
})