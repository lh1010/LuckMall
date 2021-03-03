import request from "../../utils/request.js";
var app = getApp();

Page({
  data: {
    checked: true,
    popup_visible_address: false
  },
  onLoad: function (options) {
    this.getCitys();
  },
  popup_address: function() {
    this.setData({popup_visible_address: true})
  },
  close_popup_address: function() {
    this.setData({popup_visible_address: false})
  },
  createAddress: function(e) {
    let params = e.detail.value;
    params.default_address = this.data.checked ? 1 : 2;
    wx.showLoading({title: '加载中'});
    request.post('wxapi/address/create', params).then((res) => {
      wx.navigateBack();
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
  checkedTap: function() {
    var checked = this.data.checked;
    this.setData({
      checked: !checked
    })
  }
})