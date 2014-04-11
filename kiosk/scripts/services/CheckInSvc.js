var CheckInSvc = function(authSvc, volunteerSvc, volunteerAreaSvc, restMgr) {

	this.getAllCurrent = function(callback) {

		restMgr.get('kioskCheckIns/active', function(error, rawCheckIns) {
			if(error) {
				return callback(error);
			}

			getLookupData(function(error, volunteers, areas) {
				if(error) {
					return callback(error);
				}

				var checkIns = [];

				for(var i=0; i<rawCheckIns.length; i++) {
					var checkin = {
						"id": rawCheckIns[i].id,
						"volunteer": null,
						"volunteerArea": null,
						"startTime": rawCheckIns[i].startTime
					};

					//Find the volunteer and area
					checkin.volunteer = find(volunteers, 'id', rawCheckIns[i].volunteerId);
					checkin.volunteerArea = find(areas, 'id', rawCheckIns[i].interestAreaId);

					checkIns.push(checkin);
				}

				callback(null, checkIns);
			});
		});
	};

	this.checkIn = function(volunteer, interestArea, callback) {

		var data = {
			'volunteerId': volunteer.id,
			'interestAreaId': interestArea.id
		};
		restMgr.post('kioskCheckIns', data, callback);
	};

	this.checkOut = function(checkIn, callback) {

		restMgr.post('kioskCheckIns/' + checkIn.id + '/checkOut', null, callback);
	};

	var find = function(collection, property, desiredValue) {
		var returnValue = null;
		for(var i=0; i<collection.length; i++) {
			if(collection[i][property] === desiredValue) {
				returnValue = collection[i];
				break;
			}
		}

		return returnValue;
	};


	var getLookupData = function(callback) {
		volunteerSvc.getAll(function(error, volunteers) {
			if(error) {
				return callback(error);
			}

			volunteerAreaSvc.getAll(function(error, areas) {
				if(error) {
					return callback(error);
				}

				callback(null, volunteers, areas);
			});
		});
	};
};