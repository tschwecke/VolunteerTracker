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
						"startTime": formatTimeForDisplay(rawCheckIns[i].checkInTime)
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
			'interestAreaId': interestArea.id,
			'classroom': interestArea.classroom
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

	var formatTimeForDisplay = function(dateString) {
		var date = new Date(dateString);
		var amPm = "am";
		var hours = date.getHours();
		//Set PM if needed
		if(hours > 11) {
			amPm = "pm";
		}
		//Convert from 24 hour to 12 hour display
		if(hours > 12) {
			hours -= 12;
		}
		//Handle midnight
		if(hours === 0) {
			hours = "12";
		}
		var minutes = date.getMinutes();
		if(minutes < 10) {
			minutes = '0' + minutes;
		}
		var timeString = hours + ':' + minutes + ' ' + amPm;
		return timeString;
	};
};