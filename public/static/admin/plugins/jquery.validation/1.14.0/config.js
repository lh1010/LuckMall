$.validator.setDefaults({
	ignore: [],
	onfocusout: false,
	onkeyup: false,
	onclick: false,
	focusInvalid: false,
	errorLabelContainer: ".error-info", 
	wrapper: "li",
	showErrors:function(errorMap, errorList) {
		this.defaultShowErrors();
		if(errorList.length > 0) {
			$(".error-info").show().delay(1500).hide(300);
		}
	},
})