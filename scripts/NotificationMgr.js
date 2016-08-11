
var NotificationMgr = function() {
	var self = this;

	this.notify = function(message, err, callback) {
		if(typeof(err) == "function") {
			callback = err;
			err = undefined;
		}

		if(err) {
			message += '<div><br>Details: <pre style="white-space: pre-wrap;">';
			message += err.route + '\n';
			message += err.textStatus + '\n';
			message += err.errorThrown + '\n';
			if(err.jqXHR && err.jqXHR.responseText) {
				message += err.jqXHR.responseText.substr(0,600) + '\n';
			}
			message += '</pre></div>';
		}

		var options = {};
		if(callback) {
			options.submit = callback;
		}

		$.prompt(message, options);
	};
};
