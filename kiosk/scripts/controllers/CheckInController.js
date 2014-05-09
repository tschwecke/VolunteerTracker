var CheckInController = function(router, volunteerSvc, volunteerAreaSvc, checkInSvc, notificationMgr) {

	var _volunteers = [];
	var _volunteerAreas = [];

	this.render = function() {
		
		console.log('CheckInController.render()');

		var checkinRactive = new Ractive({
		  el: 'viewContainer',
		  template: '#checkinViewTemplate',
		  data: {
		  	emailAddress: null,
		  	familyNumber: null,
		  	volunteerArea: null
		  }
		});

		volunteerSvc.getAll(function(error, volunteers) {
			_volunteers = volunteers;

			$('#checkinEmail').typeahead({
				hint: true,
				highlight: true,
				minLength: 3
			},
			{
				displayKey: 'emailAddress',
				source: substrMatcher(volunteers, 'emailAddress', true)
			});

			volunteerAreaSvc.getAll(function(error, volunteerAreas) {
				_volunteerAreas = volunteerAreas;

				$('#checkinVolunteerArea').typeahead({
				  hint: true,
				  highlight: true,
				  minLength: 1
				},
				{
				  displayKey: 'name',
				  source: substrMatcher(volunteerAreas, 'name')
				});

				checkinRactive.on("home", function(event) {
					notificationMgr.clearError();
					checkinRactive.teardown(function() {
						router.setRoute("home");
					});
				});

				checkinRactive.on("volunteerAreaHasFocus", function(event) {
					$('#checkinVolunteerArea').typeahead('open');
				});

				checkinRactive.on("checkin", function(event) {

					event.context.emailAddress = $('#checkinEmail').typeahead('val');
					event.context.volunteerArea = $('#checkinVolunteerArea').typeahead('val');

					if(!event.context.emailAddress) {
						return notificationMgr.showError('Please enter your email address.');
					}
					if(!event.context.familyNumber) {
						return notificationMgr.showError('Please enter your family number.');
					}

					if(!event.context.volunteerArea) {
						return notificationMgr.showError('Please enter the volunteer area.');
					}

					var volunteer = getVolunteer(event.context.emailAddress, event.context.familyNumber);
					var area = getVolunteerArea(event.context.volunteerArea);

					if(!volunteer) {
						return notificationMgr.showError('We could not find a volunteer record with a matching email address and family number. Please correct one or both fields and try again.');
					}

					if(!area) {
						return notificationMgr.showError('The volunteer area you entered is not valid.  Please correct it and try again.');
					}

					checkInSvc.checkIn(volunteer, area, function(error) {
						if(error) {
							if(error.error.code === 'DuplicateCheckIn') {
								return notificationMgr.showError('You have already checked in.');
							}
							else {
								return notificationMgr.showError('There was an error communicating with the server while trying to check you in.  Please try again.');
							}
						}

						checkinRactive.teardown(function() {
							router.setRoute("home");

							var confirmationRactive = new Ractive({
								el: 'notificationContainer',
								template: '#checkinConfirmationTemplate',
							});

							setTimeout(function() {
								confirmationRactive.teardown();
							}, 6000);
						});
					});
				});
			});
		});
	};

	var getVolunteer = function(emailAddress, familyNumber) {
		for(var i=0; i<_volunteers.length; i++) {
			var volunteer = _volunteers[i];
			if(volunteer.emailAddress.toLowerCase() === emailAddress.toLowerCase()
				&& volunteer.familyId == familyNumber) {

				return volunteer;
			}
		}

		return null;
	};

	var getVolunteerArea = function(areaName) {
		for(var i=0; i<_volunteerAreas.length; i++) {
			var volunteerArea = _volunteerAreas[i];
			if(volunteerArea.name.toLowerCase() === areaName.toLowerCase()) {
				return volunteerArea;
			}
		}

		return null;
	};

	var substrMatcher = function(items, key, matchFromBeginning) {
	  return function findMatches(q, cb) {
	    var matches, substringRegex;
	 
	    // an array that will be populated with substring matches
	    matches = [];
	 
	    if(matchFromBeginning) {
	    	q = '^' + q;
	    }

	    // regex used to determine if a string contains the substring `q`
	    substrRegex = new RegExp(q, 'i');
	 
	    // iterate through the of strings and for any string that
	    // contains the substring `q`, add it to the `matches` array
	    $.each(items, function(i, item) {
	      if (substrRegex.test(item[key])) {
	        // the typeahead jQuery plugin expects suggestions to a
	        // JavaScript object, refer to typeahead docs for more info
	        matches.push(item);
	      }
	    });
	 
	    cb(matches);
	  };
	};
};