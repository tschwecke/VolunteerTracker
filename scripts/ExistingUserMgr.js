

var ExistingUserMgr = function() {
	var currentVolunteer = null;
	var allVolunteers = null;
	var allInterestAreas = null;

	this.init = function() {
		//make the calls to get all of the data
		volunteerSvc.get(sessionMgr.getVolunteerId(), function (err, volunteer) {
			if(err) return notificationMgr.notify("An error occurred while retrieving your volunteer account. Please refresh the page and try again.", err);

			currentVolunteer = volunteer;
			volunteerSvc.populateForm(volunteer);

			volunteerHoursSvc.getHoursByVolunteer(sessionMgr.getVolunteerId(), function (err, hours) {
				if(err) return notificationMgr.notify("An error occurred while retrieving your volunteer hours. Please refresh the page and try again.", err);
				volunteerHoursSvc.populateForm(sessionMgr.getVolunteerId(), hours);
			});

			rightSvc.getByRoleId(volunteer.roleId, function(err, rights) {
				if(err) return notificationMgr.notify("An error occurred while retrieving your volunteer account. Please refresh the page and try again.", err);

				var canViewAdminTab = false;

				for(var i=0; i<rights.length; i++) {
					if(rights[i].code === "ViewAdminTab") {
						canViewAdminTab = true;
					}
				}

				if(canViewAdminTab) {
					adminVolunteerSvc.getAll(function (err, volunteers) {
						if(err) return notificationMgr.notify("An error occurred while retrieving the list of volunteers. Please refresh the page and try again.", err);

						allVolunteers = volunteers;
						adminHoursSvc.getApprovedTotals(function(err, hourTotals) {
							if(err) return notificationMgr.notify("An error occurred while retrieving approved hours. Please refresh the page and try again.", err);

							adminVolunteerSvc.populateForm(allVolunteers, hourTotals);
						});

						adminHoursSvc.getHoursByStatus('Pending', function(err, pendingHours) {
							if(err) return notificationMgr.notify("An error occurred while retrieving pending hours. Please refresh the page and try again.", err);

							adminHoursSvc.populateForm(allVolunteers, allInterestAreas, pendingHours);
						});
					});
					$("#adminTabLI").removeClass("hiddenTab");
				}
			});
		});

		interestsSvc.getByUser(sessionMgr.getVolunteerId(), function (err, interests) {
			if(err) return notificationMgr.notify("An error occurred while retrieving your volunteer interests. Please refresh the page and try again.", err);

			allInterestAreas = interests;
			interestsSvc.populateForm(interests);
			volunteerHoursSvc.populateInterestAreas(interests);
			adminReportsSvc.populateInterestAreas(interests);
			adminHoursSvc.populateInterestAreas(interests);
		});

		classroomSvc.getClassrooms(function(err, classrooms) {
			if(err) return notificationMgr.notify("An error occurred while retrieving the list of classrooms. Please refresh the page and try again.", err);

			volunteerHoursSvc.populateClassrooms(classrooms);
			adminHoursSvc.populateClassrooms(classrooms);
		});

		$("#login").hide();
		$("#tabs").show();
		$("#admin").show();
	};

	this.saveProfile = function() {
		var volunteer = volunteerSvc.getFromForm();

		var errors = volunteerSvc.validate(volunteer, currentVolunteer);

		if (errors.length === 0) {
			$("#profileSaveButton").button("disable");
			profileErrorSvc.hideErrors();
			volunteer.id = sessionMgr.getVolunteerId();
			volunteerSvc.update(volunteer, function(err, data) {
				if(err) return notificationMgr.notify("An error occurred while updating your volunteer account. Please refresh the page and try again.", err);

				currentVolunteer = volunteer;
				notificationMgr.notify("Your volunteer profile has been saved.");
				$("#profileSaveButton").button("enable");
			});
		}
		else {
			profileErrorSvc.showErrors(errors);
		}
	};

	this.saveInterests = function() {
		$("#interestsSaveButton").button("disable");
		var interests = interestsSvc.getFromForm();
		interestsSvc.save(sessionMgr.getVolunteerId(), interests, function (err) {
			if(err) return notificationMgr.notify("An error occurred while updating your interests. Please refresh the page and try again.", err);

				notificationMgr.notify("Your interests have been saved.");
			$("#interestsSaveButton").button("enable");
		});
	};

	this.saveHours = function() {
		var hours = volunteerHoursSvc.getFromForm();
		var errors = volunteerHoursSvc.validate(hours);
		if (errors.length === 0) {
			$("#submitHoursButton").button("disable");
			volunteerHoursErrorSvc.hideErrors();
			volunteerHoursSvc.save(sessionMgr.getVolunteerId(), hours, function (err) {
				if(err) return notificationMgr.notify("An error occurred while saving your hours. Please refresh the page and try again.", err);

				notificationMgr.notify("Your hours have been saved.");
				$("#submitHoursButton").button("enable");
				volunteerHoursSvc.clearForm();

				//Update the total hours for the user
				volunteerHoursSvc.getHoursByVolunteer(sessionMgr.getVolunteerId(), function (err, hours) {
					if(err) return notificationMgr.notify("An error occurred while updating the display of your hours. Please refresh the page and try again.", err);

					volunteerHoursSvc.populateForm(sessionMgr.getVolunteerId(), hours);
				});
			});
		}
		else {
			volunteerHoursErrorSvc.showErrors(errors);
		}

	};

	this.displayAdminHoursByStatusInterestAreaAndClassroom = function(status, interestAreaId, classroom) {
		adminHoursSvc.getHoursByStatus(status, function(err, hours){
			if(err) return notificationMgr.notify("An error occurred while retrieving hours. Please refresh the page and try again.", err);

			var filteredHours = [];
			if(interestAreaId) {
				for(var i=0; i<hours.length; i++) {
					if(hours[i].interestAreaId == interestAreaId) {
						filteredHours.push(hours[i]);
					}
				}
				hours = filteredHours;
				filteredHours = [];
			}
			if(classroom) {
				for(var i=0; i<hours.length; i++) {
					if(hours[i].classroom == classroom) {
						filteredHours.push(hours[i]);
					}
				}
				hours = filteredHours;
			}
			adminHoursSvc.populateForm(allVolunteers, allInterestAreas, hours);
		});
	};

	this.runInterestAreaReport = function(interestAreaId) {
		adminReportsSvc.getVolunteersByInterestAreaId(interestAreaId, function(err, volunteers){
			if(err) return notificationMgr.notify("An error occurred while retrieving the volunteers. Please refresh the page and try again.", err);

			adminReportsSvc.populateForm(volunteers);
		});
	};
};
