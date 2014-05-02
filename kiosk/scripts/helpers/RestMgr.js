
var RestMgr = function() {

	var _token = null;
	var _prefix = '../restservices/';

	this.get = function(url, callback) {
		$.ajax({
			'type': 'GET',
			'url': resolveUrl(url),
			'headers': {
				'X-Authentication': _token
			}
		})
		.done(successHandler(callback))
		.fail(failureHandler(callback));
	};

	this.post = function(url, data, callback) {
		$.ajax({
			'type': 'POST',
			'url': resolveUrl(url),
			'contentType': 'application/json',
			'data': JSON.stringify(data),
			'headers': {
				'X-Authentication': _token
			}
		})
		.done(successHandler(callback))
		.fail(failureHandler(callback));
	};

	var successHandler = function(callback) {
		return function(data, textStatus, jqXhr) {
			var newAccessToken = jqXhr.getResponseHeader("X-Authentication");
			if(newAccessToken) {
				_token = newAccessToken;
			}
			else if(data.access_token) {
				_token = data.access_token;
			}
			callback(null, data);
		};
	};

	var failureHandler = function(callback) {
		return function(jqXhr, textResult, error) {
			var result = {
				'statusCode': jqXhr.status,
				'error': JSON.parse(jqXhr.responseText)
			};
			callback(result);
		};
	};

	var resolveUrl = function(url) {
		if(url === 'classrooms') {
			return '../scripts/classrooms.json';
		}
		else {
			return _prefix + url;
		}
	};	
};