var NewUserMgr = function() {
	this.init = function() {

		$("#profileSaveButton").button({ label: "Continue" });
		$("#tabs").tabs("disable", "interestsTab");
		$("#tabs").tabs("disable", "hoursTab");
		$("#login").hide();
		$("#tabs").show();

		$("#pendingTotalHours").text("0.0");
		$("#personalTotalHours").text("0.0");
		$("#familyTotalHours").text("0.0");

		volunteerSvc.setFocusOnFirstName();
	};

	this.saveProfile = function() {
		var volunteer = volunteerSvc.getFromForm();

		var errors = volunteerSvc.validate(volunteer);

		if (errors.length === 0) {
			$("#profileSaveButton").button("disable");
			profileErrorSvc.hideErrors();
			volunteerSvc.save(volunteer, function(err, data) {
				if(err) {
					if(err.message == 'Email taken') {
						notificationMgr.notify('There is already an account with that email address.');
					}
					else {
						notificationMgr.notify("An error occurred while retrieving your volunteer account. Please refresh the page and try again.", err);
					}

					$("#profileSaveButton").button("enable");
				}
				else {
					//This is a weird timing issue, but we need to pause momentarily to let the global XHR success handler set the access token
					setTimeout(function() {
						interestsSvc.getByUser(sessionMgr.getVolunteerId(), function (err, interests) {
							if(err) return notificationMgr.notify("An error occurred while retrieving the lsit of interest areas. Please refresh the page and try again.", err);

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

						$("#tabs").tabs("enable", "interestsTab");
						$("#tabs").tabs("select", "interestsTab");
						$("#profileSaveButton").button({ label: "Save" });
						$("#profileSaveButton").button("enable");
					}, 0);
				}
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
			if(err) return notificationMgr.notify("An error occurred while saving your interests. Please refresh the page and try again.", err);

			notificationMgr.notify('Your profile has been created. You may now submit any volunteer hours you have completed.');
			$("#interestsSaveButton").button("enable");

			$("#tabs").tabs("enable", "hoursTab");
			$("#tabs").tabs("select", "hoursTab");
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

				notificationMgr.notify("Your <strong>hours</strong> have been saved.");
				$("#submitHoursButton").button("enable");
				volunteerHoursSvc.clearForm();

				//Update the total hours for the user
				volunteerHoursSvc.getHoursByVolunteer(sessionMgr.getVolunteerId(), function (err, hours) {
					volunteerHoursSvc.populateForm(sessionMgr.getVolunteerId(), hours);
				});
			});
		}
		else {
			volunteerHoursErrorSvc.showErrors(errors);
		}

	};
};