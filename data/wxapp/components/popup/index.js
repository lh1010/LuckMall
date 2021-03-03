Component({
  options: {
    multipleSlots: true
  },
  properties: {
    popup_visible: {
      type: Boolean,
      value: false
    }
  },
  data: {},
  methods: {
    closePopup: function() {
      this.setData({popup_visible: false});
    }
  }
})