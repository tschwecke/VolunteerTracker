
var UserMgrFactory = {
	newUserMgr: null,
	existingUserMgr: null,

	getUserMgr: function() {
		if(typeof UserMgrFactory.isExistingUser == "undefined") {
			UserMgrFactory.isExistingUser = sessionMgr.isAuthenticated();
		}

		if(UserMgrFactory.isExistingUser) {
			if(!this.existingUserMgr) {
				this.existingUserMgr = new ExistingUserMgr();
			}
			return this.existingUserMgr;
		}
		else {
			if(!this.newUserMgr) {
				this.newUserMgr = new NewUserMgr();
			}
			return this.newUserMgr;
		}
	}
};
