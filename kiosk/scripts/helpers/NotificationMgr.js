
var NotificationMgr = function() {

	var errorRactive = null;

	this.showError = function(message) {
		errorRactive = new Ractive({
		  el: 'notificationContainer',
		  template: '#checkinErrorTemplate',
		  data: {
		  	message: message
		  }
		});
	};

	this.clearError = function() {
		if(errorRactive) {
			errorRactive.teardown();
		}
	};
};