var TokenStore = function(sessionStorage) {
	var _tokenName = "AccessToken";

	this.get = function() {
		return sessionStorage.getItem(_tokenName);
	};

	this.set = function(newToken) {
		sessionStorage.setItem(_tokenName, newToken);
	};

	this.clear = function() {
		sessionStorage.removeItem(_tokenName);
	};
};