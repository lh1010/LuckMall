import request from "../../utils/request.js";
const app = getApp();

Page({
  onLoad: function (options) {
    this.setData({id: options.id});
    this.getArticle();
  },
  getArticle: function() {
    request.post('wxapi/article/getArticle', {id: this.data.id}).then((res) => {
      if (res.title == undefined) {
        wx.showToast({
          title: '内容不存在',
          icon: 'none',
          duration: 2000,
          success: function () {
            setTimeout(function() {
              wx.navigateBack({
                delta: 1
              });
            }, 2000);
          }
        });
      }
      var content = '<div class="rich_text_content">';
      content += res.content;
      content += '</div>';
      content = content.replace(/style\s*?=\s*?(['"])[\s\S]*?\1/, '');
      content = content.replace(/<img/gi, '<img style="max-width:100%; height:auto;"');
      content = content.replace(/<p/gi, '<p style="margin-bottom: 10px"');
      this.setData({
        article: res,
        content: content
      });
      wx.setNavigationBarTitle({
        title: res.title
      })
    }).catch((message) => {
      wx.showToast({title: message, icon: 'none'});
    });
  }
})