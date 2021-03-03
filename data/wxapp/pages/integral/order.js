import request from "../../utils/request.js";

Page({
  onLoad: function (options) {
    this.getOrder(options.id);
  },
  getOrder: function(id) {
    request.post('wxapi/integral/getOrder', {id: id}).then((res) => {
      this.setData({
        order: res
      });
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  }
})