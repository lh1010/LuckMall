import request from "../../utils/request.js";
import util from "../../utils/util.js";

Page({
  onLoad: function(options) {
    this.setData({id: options.id});
    this.getProduct();
  },
  onShow: function() {
    this.setData({
      btn_is_click: true,
      address_id: false
    });
    console.log(this.data.address_id);
  },
  getProduct: function() {
    wx.showLoading({title: '加载中'});
    request.post('wxapi/integral/getProduct', {id: this.data.id}).then((res) => {
      if (res.id == undefined) {
        util.delayBack('内容不存在');
        return false;
      }
      var content = '<div class="rich_text_content">';
      content += res.content;
      content += '</div>';
      content = content.replace(/style\s*?=\s*?(['"])[\s\S]*?\1/, '');
      content = content.replace(/<img/gi, '<img style="max-width:100%; height:auto;"');
      content = content.replace(/<p/gi, '<p style="margin-bottom: 10px"');
      this.setData({
        product: res,
        content: content
      })
      wx.setNavigationBarTitle({
        title: res.name + ' 积分商城'
      })
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  exchangeProduct: function(e) {
    let product_id = e.currentTarget.dataset.product_id;
    let address_id = this.data.address_id;
    if (address_id == undefined || address_id == false) {
      this.selectAddressPopupTap();
      return false;
    }
    this.setData({btn_is_click: false});
    wx.showLoading({title: '加载中'});
    request.post('wxapi/integral/exchangeProduct', {product_id: product_id, address_id: address_id}).then((res) => {
      util.delayJump('/pages/integral/order');
      return false;
    }).catch((message) => {
      this.setData({btn_is_click: true});
      wx.showToast({title: message, icon: 'none'});
    });
  },
  // 选择地址
  selectAddressPopupTap: function(e) {
    wx.showLoading({title: '加载中'});
    request.post('wxapi/address/getAddresses').then((res) => {
      let addresses_total = res.length > 0 ? res.length : 0;
      this.setData({
        addresses_total: addresses_total,
        addresses: res,
        popup_visible_address: true
      });
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  close_popup_address: function() {
    this.setData({popup_visible_address: false});
  },
  selectAddressTap: function(e) {
    this.setData({
      address_id: e.currentTarget.dataset.id
    });
    this.close_popup_address();
  },
  toAccountAddressTap: function(e) {
    this.setData({
      popup_visible_address: false
    })
    wx.navigateTo({
      url: "/pages/account_address/index"
    })
  },
})