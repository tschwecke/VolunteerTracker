var AuthSvc = function(restMgr, tokenStore) {

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
		tokenStore.clear();
	};

	this.stayLoggedInHack = function() {
		var intervalId = setInterval(function() {
			restMgr.get('dummyRouteToStayLoggedIn');
		}, 30*60*1000);

		setTimeout(function() {
			clearInterval(intervalId);
		}, 13*60*60*1000)
	};
};