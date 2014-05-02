var AuthSvc = function(restMgr) {

	this.login = function(emailAddress, password, callback) {
		console.log('AuthSvc.login("' + emailAddress + '", "*******")');

		var credentials = {
			'emailAddress': emailAddress,
			'password': password
		};

		restMgr.post('accessToken', credentials, callback);
	};

	this.logout = function() {
		console.log('AuthSvc.logout()');
		_token = null;
	};
};