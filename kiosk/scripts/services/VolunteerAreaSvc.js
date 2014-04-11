var VolunteerAreaSvc = function(authSvc, restMgr) {

	this.getAll = function(callback) {
		restMgr.get('interestAreas', callback);
	};
};