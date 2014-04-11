var AuthSvc = function(restMgr) {

	this.login = function(emailAddress, password, callback) {
		var credentials = {
			'emailAddress': emailAddress,
			'password': password
		};

		restMgr.post('accessToken', credentials, callback);
	};

	this.logout = function() {
		_token = null;
	};
};