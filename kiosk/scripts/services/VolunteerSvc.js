var VolunteerSvc = function(authSvc, restMgr) {

	this.getAll = function(callback) {
		restMgr.get('volunteers', callback);
	};
};