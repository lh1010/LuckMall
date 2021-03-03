import request from "../../utils/request.js";
import system from "../../api/system.js";
import util from "../../utils/util.js";
var app = getApp();

Page({
  data: {
    popup_visible_address: false,
    popup_visible_specification: false,
    popup_province_ident: true,
    popup_city_ident: true,
    popup_area_ident: true,
    buy_way_ident: 'add_cart',
    buy_count: 1,
  },
  onLoad: function (options) {
    this.setData({sku: options.sku});
  },
  onShow: function() {
    this.getProduct();
    this.getCitys();
    this.getCartCount();
  },
  popup_specification: function() {
    this.setData({popup_visible_specification: true})
  },
  close_popup_specification: function() {
    this.setData({popup_visible_specification: false})
  },
  popup_address: function() {
    this.setData({popup_visible_address: true})
  },
  close_popup_address: function() {
    this.setData({popup_visible_address: false})
  },
  selectSpecificationTab: function(e) {
    if ( e.currentTarget.dataset.sku == undefined) return false;
    this.setData({sku: e.currentTarget.dataset.sku});
    this.getProduct();
  },
  async getProduct() {
    wx.showLoading({title: '加载中'});
    request.get('wxapi/product/getProduct?sku=' + this.data.sku).then((res) => {
      this.setData({product: res});
      var content = res.content;
      // 清除 style 标签
      content = content.replace(/style\s*?=\s*?(['"])[\s\S]*?\1/g, '');
      content = content.replace(/<img/gi, '<img style="max-width:100%; height:auto;"');

      this.setData({
        product: res,
        content: content
      });
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  },
  showHidePopupAddress: function(e) {
    this.setData({popup_address_location: e.currentTarget.dataset.type});
  },
  async getCitys(e) {
    var url = 'wxapi/city/getCitys';
    var popup_address_type = '';
    if (e != undefined) {
      popup_address_type = e.currentTarget.dataset.type;
      url += '?parent_id=' + e.currentTarget.dataset.id;
      if (popup_address_type == 'area') {
        this.setData({
          selected_area_id: e.currentTarget.dataset.id,
          selected_area_name: e.currentTarget.dataset.name,
          region_ids: this.data.selected_province_id + ',' + this.data.selected_city_id + ',' + e.currentTarget.dataset.id,
          region: this.data.selected_province_name + ' ' + this.data.selected_city_name + ' ' + e.currentTarget.dataset.name
        });
        this.close_popup_address();
        return false;
      }
    }
    request.get(url).then((res) => {
      if (popup_address_type == '') {
        this.setData({
          province_data: res,
          popup_address_location: 'province'
        });
      }
      if (popup_address_type == 'province') {
        this.setData({
          city_data: res,
          popup_address_location: 'city',
          selected_province_id: e.currentTarget.dataset.id,
          selected_province_name: e.currentTarget.dataset.name
        });
      }
      if (popup_address_type == 'city') {
        this.setData({
          area_data: res,
          popup_address_location: 'area',
          selected_city_id: e.currentTarget.dataset.id,
          selected_city_name: e.currentTarget.dataset.name
        });
      }
    }).catch((message) => {
      wx.showToast({title: '获取地址信息失败', icon: 'none'});
    });
  },
  buyWayTab: function(e) {
    this.setData({buy_way_ident: e.currentTarget.dataset.type});
    this.popup_specification();
  },
  buyTap: function() {
    util.checkLogin().then(is_login => {
      if (!is_login) {
        util.toLoginPage();
      } else {
        var buy_way_ident = this.data.buy_way_ident;
        var buy_count = this.data.buy_count;
        var sku = this.data.sku;
        var product = this.data.product;
        if (product.stock <= 0 || buy_count > product.stock) return false;
        if (buy_way_ident == 'add_cart') {
          let params = {sku: sku, count: buy_count};
          request.post('wxapi/cart/addCart', params).then((res) => {
            this.toCartTap();
          }).catch((message) => {
            wx.showToast({title: message, icon: 'none'});
          });
        }
        if (buy_way_ident == 'one_key_buy') {
          wx.navigateTo({
            url: "/pages/checkout/onekeybuy?sku=" + sku + "&count=" + buy_count
          })
        }
      }
    });
  },
  setCountTab: function(e) {
    let type = e.currentTarget.dataset.type;
    let product_stock = this.data.product.stock;
    let buy_count = this.data.buy_count;
    if (type == 'jian') {
      if (buy_count <= 1) return false;
      buy_count -= 1;
    }
    if (type == 'jia') {
      if (buy_count >= product_stock) return false;
      buy_count += 1;
    }
    this.setData({buy_count: buy_count});
  },
  getCartCount: function() {
    request.post('wxapi/cart/getCount').then((res) => {
      this.setData({
        cart_count: res
      })
    });
  },
  toCartTap: function(e) {
    wx.switchTab({
      url: "/pages/cart/index"
    })
  },
  setCollect: function(e) {
    util.checkLogin().then(is_login => {
      if (!is_login) {
        util.toLoginPage();
      } else {
        wx.showLoading({title: '加载中'});
        let sku = this.data.product.sku;
        request.post('wxapi/product/collect', {sku: sku}).then((res) => {
          this.getProduct();
        }).catch((message) => {
          wx.showToast({title: message, icon: 'none'});
        });
      }
    })
  },
  onShareAppMessage: function () {
    return {
      title: this.data.product.name
    }
  },
  // 客服 拨打电话
  call: function() {
    system.getConfig().then((res) => {
      if (JSON.stringify(res.phone) == '{}') {
        wx.showToast({title: '未设置联系方式', icon: 'none'});
        return false;
      }
      let phone = res.phone.toString();
      wx.makePhoneCall({
        phoneNumber: phone
      })
    })
  }
})