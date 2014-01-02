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
			volunteerSvc.save(volunteer, function(error, data) {
				if(error) {
					var errorMsg = 'An error occurred while trying to create your account. Please try again.';
					if(error.message == 'Email taken') {
						errorMsg = 'There is already an account with that email address.';
					}
					notificationMgr.notify(errorMsg);
			

					$("#profileSaveButton").button("enable");
				}
				else {
					sessionMgr.setAccessToken(data.access_token);

					interestsSvc.getByUser(sessionMgr.getVolunteerId(), function (err, interests) {
						interestsSvc.populateForm(interests);
						volunteerHoursSvc.populateInterestAreas(interests);
						adminReportsSvc.populateInterestAreas(interests);
					});

					classroomSvc.getClassrooms(function(err, classrooms) {
						volunteerHoursSvc.populateClassrooms(classrooms);
						adminHoursSvc.populateClassrooms(classrooms);
					});

					$("#tabs").tabs("enable", "interestsTab");
					$("#tabs").tabs("select", "interestsTab");
					$("#profileSaveButton").button({ label: "Save" });
					$("#profileSaveButton").button("enable");
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
		interestsSvc.save(sessionMgr.getVolunteerId(), interests, function () {
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
			volunteerHoursSvc.save(sessionMgr.getVolunteerId(), hours, function () {
				notificationMgr.notify("Your <strong>hours</strong> have been saved.");
				$("#submitHoursButton").button("enable");
				volunteerHoursSvc.clearForm();
			});
		}
		else {
			volunteerHoursErrorSvc.showErrors(errors);
		}

	};
};

var ExistingUserMgr = function() {
	var currentVolunteer = null;
	var allVolunteers = null;
	var allInterestAreas = null;

	this.init = function() {
		//make the calls to get all of the data
		volunteerSvc.get(sessionMgr.getVolunteerId(), function (err, volunteer) {
			currentVolunteer = volunteer;			
			volunteerSvc.populateForm(volunteer);

			volunteerHoursSvc.getHoursByVolunteer(sessionMgr.getVolunteerId(), function (err, hours) {
				volunteerHoursSvc.populateForm(sessionMgr.getVolunteerId(), hours);
			});

			rightSvc.getByRoleId(volunteer.roleId, function(err, rights) {
				var canViewAdminTab = false;

				for(var i=0; i<rights.length; i++) {
					if(rights[i].code === "ViewAdminTab") {
						canViewAdminTab = true;
					}
				}

				if(canViewAdminTab) {
					adminVolunteerSvc.getAll(function (err, volunteers) {
						allVolunteers = volunteers;
						adminHoursSvc.getApprovedTotals(function(err, hourTotals) {
							adminVolunteerSvc.populateForm(allVolunteers, hourTotals);
						});

						adminHoursSvc.getHoursByStatus('Pending', function(err, pendingHours) {
							adminHoursSvc.populateForm(allVolunteers, allInterestAreas, pendingHours);
						});
					});
					$("#adminTabLI").removeClass("hiddenTab");
				}
			});
		});

		interestsSvc.getByUser(sessionMgr.getVolunteerId(), function (err, interests) {
			allInterestAreas = interests;
			interestsSvc.populateForm(interests);
			volunteerHoursSvc.populateInterestAreas(interests);
			adminReportsSvc.populateInterestAreas(interests);
		});

		classroomSvc.getClassrooms(function(err, classrooms) {
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
			volunteerSvc.update(volunteer, function(error, data) {
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
		interestsSvc.save(sessionMgr.getVolunteerId(), interests, function () {
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
			volunteerHoursSvc.save(sessionMgr.getVolunteerId(), hours, function () {
				notificationMgr.notify("Your hours have been saved.");
				$("#submitHoursButton").button("enable");
				volunteerHoursSvc.clearForm();
			});
		}
		else {
			volunteerHoursErrorSvc.showErrors(errors);
		}

	};

	this.displayAdminHoursByStatusAndClassroom = function(status, classroom) {
		adminHoursSvc.getHoursByStatus(status, function(err, hours){

			var filteredHours = [];
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
		adminReportsSvc.getVolunteersByInterestAreaId(interestAreaId, function(error, volunteers){
			adminReportsSvc.populateForm(volunteers);
		});
	};
};

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

var SessionMgr = function() {
	var self = this;
	var accessToken = null;

	this.isAuthenticated = function() {
		if(self.getAccessToken()) {
			return true;
		}
		else {
			return false;
		}
	};

	this.getAccessToken = function() {
		if(!accessToken) {
			accessToken = sessionStorage["VolunteerAccessToken"];
		}

		return accessToken;
	};

	this.setAccessToken = function(newAccessToken) {
		accessToken = newAccessToken;
		
		if(sessionStorage) {
			sessionStorage["VolunteerAccessToken"] = accessToken;
		}
	};

	this.clearSession = function() {
		accessToken = null;
		
		if(sessionStorage) {
			sessionStorage.removeItem("VolunteerAccessToken");
		}
	};

	this.getVolunteerId = function() {
		var token = self.getAccessToken();
		if(token) {
			var tokenParts = token.split("|");
			if(tokenParts.length === 3) {
				return tokenParts[0];
			}
		}
	};

	//Attach an event handler so that the access token is always added to every request
	$(document).ajaxSend(function(evt, request, settings){
		var token = self.getAccessToken();
		if(token) {
			request.setRequestHeader("X-Authentication", token);
		}
	});

	//Attach an event handler so that we always grab a new access token if it is sent on a response
	$(document).ajaxSuccess(function(evt, response, settings) {
		var newAccessToken = response.getResponseHeader("X-Authentication");
		if(newAccessToken) {
			self.setAccessToken(newAccessToken);
		}
	});

	//Attach an event handler so that if we get a 401 we know the user's sessions has aexpiored and we send them back to the login screen
	$(document).ajaxError(function(evt, response, settings) {
		if(response.status == 401 && self.isAuthenticated()) {
			notificationMgr.notify("You're session has expired.", function() {
				loginSvc.logout();
			});
		}
	});
};

var LoginSvc = function(loginDiv) {
	var self = this;

	this.getCredentialsFromForm = function () {
		var credentials = {
			emailAddress: loginDiv.find("#loginEmailAddress").val(),
			password: loginDiv.find("#loginPassword").val()
		}

		return credentials;
	};

	this.validate = function(credentials) {
		var errors = [];
			
		//Email
		if(hasValue(credentials.emailAddress)) {
			if(!isValidEmail(credentials.emailAddress)) {
				errors.push(validationErrorCodes.LOGIN_EMAIL_INVALID);
			}
		}
		else {
			errors.push(validationErrorCodes.LOGIN_EMAIL_REQUIRED);
		}
			
		//Password
		if(!hasValue(credentials.password)) errors.push(validationErrorCodes.LOGIN_PASSWORD_REQUIRED);

		return errors;
	};

	this.getAccessToken = function(credentials, callback) {
		$.ajax("restservices/accessToken", {
			type: "POST",
			data: JSON.stringify(credentials),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {

				if(ga) {
					ga('send', 'event', 'login', 'success');
				}
				callback(null, data.access_token);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if(ga) {
					ga('send', 'event', 'login', 'failure');
				}
				callback(errorThrown);
			}
		});				
	};

	this.getAccessTokenById = function(volunteerId, callback) {
		$.ajax("restservices/volunteers/" + volunteerId + "/accessToken", {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data.access_token);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				callback(errorThrown);
			}
		});
	};

   this.logout = function() {
		sessionMgr.clearSession();
		document.location.reload();
	};

	this.switchUser = function(volunteerId) {
		self.getAccessTokenById(volunteerId, function(err, token) {

			setTimeout(function(){
				sessionMgr.setAccessToken(token);
				sessionMgr.getVolunteerId();
				document.location.reload();
			}, 1);
		})
	};
};

var VolunteerSvc = function(profileDiv) {
	this.setFocusOnFirstName = function() {
		profileDiv.find("#firstName").focus();
	};

	this.getFromForm = function() {
		var volunteer = {
			emailAddress: profileDiv.find("#email").val(),
			confirmEmailAddress: profileDiv.find("#confirmEmail").val(),
			firstName: profileDiv.find("#firstName").val(),
			lastName: profileDiv.find("#lastName").val(),
			familyId: profileDiv.find("#familyId").val(),
			primaryPhoneNbr: profileDiv.find("#primaryPhoneNbr").val(),
			password: profileDiv.find("#password").val(),
			confirmPassword:  profileDiv.find("#confirmPassword").val()
		};
			
		return volunteer;
	};
		
	this.populateForm = function(volunteer) {
		var UNCHANGED_PASSWORD = "UNCHANGED";
		if(volunteer) {
			profileDiv.find("#email").val(volunteer.emailAddress);
			profileDiv.find("#confirmEmail").val(volunteer.emailAddress);
			profileDiv.find("#firstName").val(volunteer.firstName);
			profileDiv.find("#lastName").val(volunteer.lastName);
			profileDiv.find("#familyId").val(volunteer.familyId);
			profileDiv.find("#primaryPhoneNbr").val(volunteer.primaryPhoneNbr);
			profileDiv.find("#password").val(UNCHANGED_PASSWORD);
			profileDiv.find("#confirmPassword").val(UNCHANGED_PASSWORD);
		}
	};

	this.validate = function(volunteer, currentVolunteer) {
		var errors = [];

		//First Name
		if(!hasValue(volunteer.firstName)) errors.push(validationErrorCodes.FIRST_NAME_REQUIRED);

		//Last Name
		if(!hasValue(volunteer.lastName)) errors.push(validationErrorCodes.LAST_NAME_REQUIRED);

		//Email
		if(hasValue(volunteer.emailAddress)) {
			if(isValidEmail(volunteer.emailAddress)) {
				if(hasValue(volunteer.confirmEmailAddress)) {
					if(volunteer.emailAddress != volunteer.confirmEmailAddress) {
						errors.push(validationErrorCodes.CONFIRM_EMAIL_DOESNT_MATCH);
					}
				}
				else {
					errors.push(validationErrorCodes.CONFIRM_EMAIL_REQUIRED);
				}
			}
			else {
				errors.push(validationErrorCodes.EMAIL_INVALID);
			}
		}
		else {
			errors.push(validationErrorCodes.EMAIL_REQUIRED);
		}
			
		//Password
		if(hasValue(volunteer.password)) {
			if(isValidPassword(volunteer.password)) {
				if(hasValue(volunteer.confirmPassword)) {
					if(volunteer.password != volunteer.confirmPassword) {
						errors.push(validationErrorCodes.CONFIRM_PASSWORD_DOESNT_MATCH);
					}
				}
				else {
					errors.push(validationErrorCodes.CONFIRM_PASSWORD_REQUIRED);
				}
			}
			else {
				errors.push(validationErrorCodes.PASSWORD_INVALID);
			}
		}
		else {
			errors.push(validationErrorCodes.PASSWORD_REQUIRED);
		}
		
		//Family ID
		if(!hasValue(volunteer.familyId)) errors.push(validationErrorCodes.FAMILY_ID_REQUIRED);

		//If the volunteer is an existing volunteer, make sure they aren't changing too many fields at the same time
		if(currentVolunteer) {
			if(volunteer.firstName != currentVolunteer.firstName
				&& volunteer.lastName != currentVolunteer.lastName
				&& volunteer.emailAddress != currentVolunteer.emailAddress) {

				errors.push(validationErrorCodes.CANT_CHANGE_AT_SAME_TIME);
			}
		}

		return errors;
	};

	this.get = function(id, callback) {
		$.ajax("restservices/volunteers/" + id, {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});
	};
		
	this.save = function(volunteer, callback) {
		$.ajax("restservices/volunteers", {
			type: "POST",
			data: JSON.stringify(volunteer),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {
				if(ga) {
					ga('send', 'event', 'profile', 'created');
				}
				callback(null, data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if(jqXHR.status == '409') {
					callback(new Error('Email taken'));
				}
				else {
					callback(new Error(errorThrown));
				}
			}
		});			
	};

	this.update = function(volunteer, callback) {
		$.ajax("restservices/volunteers/" + volunteer.id, {
			type: "PUT",
			data: JSON.stringify(volunteer),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {
				if(ga) {
					ga('send', 'event', 'profile', 'updated');
				}
				callback(null, data);
			}
		});			
	};

};



	
var ErrorSvc = function(parentDiv) {
		
	this.showErrors = function(errors) {
		clearErrors();
		if(errors.length > 0) {
			var errorString = "<ul>";
			for(var i=0; i<errors.length; i++) {
				errorString += "<li>" + errors[i].message + "</li>";
				parentDiv.find("#" + errors[i].elementId).addClass("validationFailed");
			}
			errorString += "</ul>";
			parentDiv.find(".validationErrorBox .validationErrorMsg").html(errorString);
			parentDiv.find(".validationErrorBox").show();
				
			parentDiv.find("#" + errors[0].elementId).focus();
		}
	};
		
	this.hideErrors = function() {
		parentDiv.find(".validationErrorBox").hide();
		parentDiv.find(".validationErrorBox .validationErrorMsg").html("");
		
		clearErrors();
	};
		
	var clearErrors = function() {
		for(var errorCode in validationErrorCodes) {
			if(validationErrorCodes.hasOwnProperty(errorCode)) {
				parentDiv.find("#" + validationErrorCodes[errorCode].elementId).removeClass("validationFailed");	
			}
		}
	};
};


//Error Codes
var validationErrorCodes = {
	EMAIL_REQUIRED: {id:1, elementId: "email", message: "Email address is required."},
	EMAIL_INVALID: {id:2, elementId: "email", message: "Email address is invalid."},
	CONFIRM_EMAIL_REQUIRED: {id:3, elementId: "confirmEmail", message: "The confirm email address is required."},
	CONFIRM_EMAIL_DOESNT_MATCH: {id:4, elementId: "confirmEmail", message: "Email address and confirm email address don't match."},
	FIRST_NAME_REQUIRED: {id:5, elementId: "firstName", message: "First name is required."},
	LAST_NAME_REQUIRED: {id:6, elementId: "lastName", message: "Last name is required."},
	PASSWORD_REQUIRED: {id:14, elementId: "password", message: "Password is required."},
	PASSWORD_INVALID: {id:15, elementId: "password", message: "Password is invalid."},
	CONFIRM_PASSWORD_REQUIRED: {id:16, elementId: "confirmPassword", message: "Confirm password is required."},
	CONFIRM_PASSWORD_DOESNT_MATCH: {id:17, elementId: "confirmPassword", message: "Password and confirm password don't match."},
	DATE_REQUIRED: {id:18, elementId: "hoursDate", message: "Date is required."},
	DATE_INVALID: {id:19, elementId: "hoursDate", message: "Date is invalid. Please enter the date in 'MM/DD/YYYY' format."},
	HOURS_REQUIRED: {id:20, elementId: "hours", message: "Number of hours is required."},
	HOURS_INVALID: {id:21, elementId: "hours", message: "Number of hours is invalid."},
	AREA_REQUIRED: {id:22, elementId: "hoursArea", message: "Area of work is required."},
	DESCRIPTION_REQUIRED: {id:23, elementId: "hoursDescription", message: "A description is required when the type is 'Other'."},
	LOGIN_EMAIL_REQUIRED: {id:24, elementId: "loginEmailAddress", message: "Please enter your email address."},
	LOGIN_EMAIL_INVALID: {id:25, elementId: "loginEmailAddress", message: "Please enter a valid email address."},
	LOGIN_PASSWORD_REQUIRED: {id:26, elementId: "loginPassword", message: "Please enter your password."},
	LOGIN_CREDENTIALS_INCORRECT: {id:27, elementId: "none", message: "Your login credentials are incorrect.  Please try again."},
	CANT_CHANGE_AT_SAME_TIME: {id:28, elementId: "none", message: "You cannot change your First Name, Last Name and Email Address all at the same time.  Please try again."},
	FAMILY_ID_REQUIRED: {id:29, elementId: "familyId", message: "Family ID is required."},
	CLASSROOM_REQUIRED: {id:30, elementId: "hoursClassroom", message: "Classroom is required."}
};
	
var InterestsSvc = function(interestsDiv) {
	var _interestAreas = null;

	this.getAll = function(callback) {
		$.ajax("restservices/interestAreas", {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});
	};

	this.getByUser = function(volunteerId, callback) {
		$.ajax("restservices/volunteers/" + volunteerId + "/selectedInterests", {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});
	};

	this.save = function(volunteerId, interests, callback) {
		$.ajax("restservices/volunteers/" + volunteerId + "/selectedInterests", {
			type: "PUT",
			data: JSON.stringify(interests),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {
				if(ga) {
					ga('send', 'event', 'interests', 'saved');
				}
				callback(null);
			}
		});			
	};

	this.getFromForm = function() {
		var interestsListItems = interestsDiv.find("div.interestsColumn li");
		var interests = [];
		interestsListItems.each(function(index, element) {
			var item = $(element).find(":checkbox");
			var interest = {
				"id": parseInt(item.attr("data-id")),
				"interestAreaId": parseInt(item.attr("data-interestAreaId")),
				"selected": item.attr("checked") === "checked"
			};
			interests.push(interest);
		});

		return interests;
	};

	this.populateForm = function(interests) {
		_interestAreas = interests;

		//Remove the 'Other' option
		var interests=[].concat(interests);
		for(var i=0; i<interests.length; i++) {
			if(interests[i].name === 'Other') {
				interests.splice(i, 1);
			}
		}

		interests.sort(function (a, b) {
			if (a.name > b.name)
			  return 1;
			if (a.name < b.name)
			  return -1;
			// a must be equal to b
			return 0;
		});

		var columnLength = Math.ceil(interests.length / 3);

		var interestsList = interestsDiv.find("#interestsList1");
		for(var i=0; i<columnLength; i++) {
			var interest = interests[i];
			interestsList.append("<li><label><input type=\"checkbox\" data-interestAreaId=\"" + interest.interestAreaId + "\" data-id=\"" + interest.id + "\" " + (interest.selected ? "checked" : "") + ">" + interest.name + "</label></li>");			
		}
		interestsList = interestsDiv.find("#interestsList2");
		for(var i=columnLength; i<(2*columnLength); i++) {
			var interest = interests[i];
			interestsList.append("<li><label><input type=\"checkbox\" data-interestAreaId=\"" + interest.interestAreaId + "\" data-id=\"" + interest.id + "\" " + (interest.selected ? "checked" : "") + ">" + interest.name + "</label></li>");			
		}
		interestsList = interestsDiv.find("#interestsList3");
		for(var i=(2*columnLength); i<interests.length; i++) {
			var interest = interests[i];
			interestsList.append("<li><label><input type=\"checkbox\" data-interestAreaId=\"" + interest.interestAreaId + "\" data-id=\"" + interest.id + "\" " + (interest.selected ? "checked" : "") + ">" + interest.name + "</label></li>");			
		}
	};

	this.getInterestNameById = function(interestAreaId) {
		var name = null;
		for(var i=0; i<_interestAreas.length; i++) {
			if(_interestAreas[i].interestAreaId == interestAreaId) {
				name = _interestAreas[i].name;
			}
		}
		return name;
	};
};

var ClassroomSvc = function() {
	this.getClassrooms = function(callback) {
		$.ajax("scripts/classrooms.json", {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});	
	};
};

var VolunteerHoursSvc = function(hoursDiv) {

	var interestAreas = null;
		
	this.save = function(volunteerId, hours, callback) {
		//Change the date from a string to a date object
		hours.date = convertStringToDate(hours.date);
		hours.date = formatDateForJson(hours.date);

		$.ajax("restservices/volunteers/" + volunteerId + "/hours", {
			type: "POST",
			data: JSON.stringify(hours),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {
				if(ga) {
					ga('send', 'event', 'hours', 'saved');
				}
				callback(null, data);
			}
		});
	};
		
	this.getHoursByVolunteer = function(volunteerId, callback) {
		$.ajax("restservices/volunteers/" + volunteerId + "/familyHours", {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});	
	};
		
	this.populateForm = function(volunteerId, hours, classrooms) {
		var totalFamilyHours = 0,
			totalPersonalHours = 0,
			totalPendingHours = 0,
			hoursList = hoursDiv.find("#hoursList table");

		hours.sort(function (a, b) {
			if (convertJsonDateToDate(a.date) < convertJsonDateToDate(b.date))
			  return 1;
			if (convertJsonDateToDate(a.date) > convertJsonDateToDate(b.date))
			  return -1;
			// a must be equal to b
			return 0;
		});

		//Add the right header for the hours listing
		if(hours.length === 0) {
			hoursList.append("<tr><th>No hours have been submitted yet.</th></tr>");
		}
		else {
			hoursList.append("<tr><th>Date</th><th>Interest Area</th><th>Hours</th><th>Status</th><th>Description</th></tr>");
		}

		//Loop through all of the hours submissions to calculate totals
		for(var i=0; i<hours.length; i++) {
			if(hours[i].status === "Approved") {
				totalFamilyHours += parseFloat(hours[i].nbrOfHours, 10);
				if(hours[i].volunteerId == volunteerId) {
					totalPersonalHours += parseFloat(hours[i].nbrOfHours, 10);
				}
			}
			else if(hours[i].status === "Pending" && hours[i].volunteerId == volunteerId) {
				totalPendingHours += parseFloat(hours[i].nbrOfHours);
			}

			//Add this hours submission to the detailed hours listing
			var date = convertJsonDateToDate(hours[i].date);
			var dateString = formatDateForDisplay(date);
			hoursList.append("<tr><td>" + dateString + "</td><td>" + getInterestArea(interestAreas, hours[i]) + "</td><td>" + hours[i].nbrOfHours + "</td><td>" + hours[i].status + "</td><td>" + hours[i].description + "</td></tr>");
		}

		//Round to the nearest tenth
		totalPersonalHours = Math.round(totalPersonalHours * 10) / 10;
		totalFamilyHours = Math.round(totalFamilyHours * 10) / 10;
		totalPendingHours = Math.round(totalPendingHours * 10) / 10;

		hoursDiv.find("#personalTotalHours").text(totalPersonalHours.toFixed(1));
		hoursDiv.find("#familyTotalHours").text(totalFamilyHours.toFixed(1));
		hoursDiv.find("#pendingTotalHours").text(totalPendingHours.toFixed(1));
	};
		
	this.populateInterestAreas = function(interests) {

		interests.sort(function (a, b) {
			if (a.name > b.name)
			  return 1;
			if (a.name < b.name)
			  return -1;
			// a must be equal to b
			return 0;
		});

		interestAreas = interests;

		var interestsList = hoursDiv.find("#hoursArea");
		for(var i=0; i<interests.length; i++) {
			var interest = interests[i];
			interestsList.append("<option value=\"" + interest.interestAreaId + "\">" + interest.name + "</option>");			
		}
	};

	this.populateClassrooms = function(classrooms) {
		//Populate the classroom dropdown
		var classroomList = hoursDiv.find("#hoursClassroom");
		for(var i=0; i<classrooms.length; i++) {
			var classroom = classrooms[i];
			var optgroup = $('<optgroup/>', { 'label': classroom.name});

			for(var j=0; j<classroom.teachers.length; j++) {
				var teacher = classroom.teachers[j];
				optgroup.append('<option value="' + teacher.lastName + '">' + teacher.displayName + "</option>");
			}

			classroomList.append(optgroup);
		}
	};

	this.getFromForm = function() {
		var hours = {
			"date": hoursDiv.find("#hoursDate").val(),
			"nbrOfHours": hoursDiv.find("#hours").val(),
			"interestAreaId": hoursDiv.find("#hoursArea").val(),
			"classroom": hoursDiv.find("#hoursClassroom").val(),
			"description":hoursDiv.find("#hoursDescription").val()
		}

		return hours;
	};

	this.validate = function(hours) {
		var errors = [];

		if(hasValue(hours.date)) {
			if(!isDate(hours.date)) {
				errors.push(validationErrorCodes.DATE_INVALID);
			}
		}
		else {
			errors.push(validationErrorCodes.DATE_REQUIRED);
		}
		if(hasValue(hours.nbrOfHours)) {
			if(!isNumber(hours.nbrOfHours)) {
				errors.push(validationErrorCodes.HOURS_INVALID);
			}
		}
		else {
			errors.push(validationErrorCodes.HOURS_REQUIRED);			
		}
		if(!hasValue(hours.interestAreaId)) errors.push(validationErrorCodes.AREA_REQUIRED);

		var interestName = interestsSvc.getInterestNameById(hours.interestAreaId);

		if(interestName === 'Other' && !hasValue(hours.description)) {
			errors.push(validationErrorCodes.DESCRIPTION_REQUIRED);
		}

		if(interestName === 'Classroom' && !hasValue(hours.classroom)) {
			errors.push(validationErrorCodes.CLASSROOM_REQUIRED);
		}

		return errors;
	};

	this.clearForm = function() {
		hoursDiv.find("#hoursDate").val("");
		hoursDiv.find("#hours").val("");
		hoursDiv.find("#hoursArea").val("");
		hoursDiv.find("#hoursClassroom").val("").hide();
		hoursDiv.find("#hoursDescription").val("");
	};
};
	
var AdminVolunteerSvc = function(adminVolunteerDiv) {
	var self = this;

	this.populateForm = function(volunteers, hourTotals) {
		var volunteerList = adminVolunteerDiv.find("#adminVolunteerList");
		for(var i=0; i<volunteers.length; i++) {
			var volunteer = volunteers[i];

			//Find the total hours for this volunteer
			var totalHours = 0;
			for(var y=0; y<hourTotals.length; y++) {
				if(hourTotals[y].id == volunteer.id) {
					totalHours = hourTotals[y].hours;
					break;
				}
			}

			volunteerList.append("<tr><td>" + volunteer.lastName + "</td><td>" + volunteer.firstName + "</td><td>" + volunteer.emailAddress + "</td><td>" + getStatusDropdown(volunteer) + "</td><td>" + totalHours + "</td><td><a href=\"javascript:loginSvc.switchUser(" + volunteer.id + ");\" class=\"administrativeLogin\">Login</a></td></tr>");			
		}

		$(".volunteerRoleSelect").change(function(eventObject) {
			var volunteerId = eventObject.target.dataset.volunteerid;
			var roleId = eventObject.target.value;
			self.updateRole(volunteerId, roleId, function() {
				notificationMgr.notify('The volunteer status has been updated.');
			});
		});
	};

	this.getAll = function(callback) {
		$.ajax("restservices/volunteers", {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});
	};

	this.updateRole = function(volunteerId, roleId, callback) {
		$.ajax("restservices/volunteers/" + volunteerId + "/role", {
			type: "PUT",
			data: JSON.stringify({"id": roleId}),
			success: function(data, textStatus, jqXHR) {
			callback(null, data);
			}
		});
	};

	var getStatusDropdown = function(volunteer) {
		var statusList = [{'id': 1, 'name': "Pending"}, 
						{'id': 2, 'name': "Active"}, 
						{'id': 3, 'name': "Administator"}, 
						{'id': 4, 'name': "Inactive"}];
		var statusDropdownHtml = "<select class=\"volunteerRoleSelect\" data-volunteerId=\"" + volunteer.id + "\">";

		for(var i=0; i<statusList.length; i++) {
			statusDropdownHtml += "<option value=\"" + statusList[i].id + "\" " + (volunteer.roleId == statusList[i].id ? "selected" : "")+ ">" + statusList[i].name + "</option>";
		}
		statusDropdownHtml += "</select>";
		return statusDropdownHtml;
	};

};

var AdminHoursSvc = function(adminHoursDiv) {
	var self = this;

	this.populateForm = function(volunteers, interestAreas, hours) {
		var hoursList = adminHoursDiv.find("#adminHoursList");

		hoursList.empty();

		if(hours.length === 0) {
			$("#adminHoursTotal").text("");
			hoursList.append("<tr><th>No hours were found with this status.</th></tr>");
		}
		else {
			var totalHours = 0;
			hoursList.append("<tr><th>Family Number</th><th>Name</th><th>Date</th><th>Interest Area</th><th>Hours</th><th>Status</th>th>Description</th></tr>");
			for(var i=0; i<hours.length; i++) {
				var hoursSubmission = hours[i];
				totalHours += parseFloat(hoursSubmission.nbrOfHours);

				//Find the volunteer for these hours
				var volunteer = null;
				for(var y=0; y<volunteers.length; y++) {
					if(volunteers[y].id == hoursSubmission.volunteerId) {
						volunteer = volunteers[y];
						break;
					}
				}
				var date = convertJsonDateToDate(hoursSubmission.date);
				var dateString = formatDateForDisplay(date);
				var interestArea = getInterestArea(interestAreas, hoursSubmission)

				hoursList.append("<tr><td>" + volunteer.familyId + "</td><td>" + volunteer.firstName + " " + volunteer.lastName + "</td><td>" + dateString + "</td><td>" + interestArea + "</td><td>" + hoursSubmission.nbrOfHours + "</td><td>" + getStatusDropdown(hoursSubmission) + "</td><td>" + hoursSubmission.description + "</td></tr>");			
			}

			$("#adminHoursTotal").text("Total number of hours: " + totalHours.toFixed(2));

			$(".pendingStatusSelect").change(function(eventObject) {
				var volunteerId = eventObject.target.dataset.volunteerid;
				var hoursId = eventObject.target.dataset.hoursid;
				var status = eventObject.target.value;
				self.updateStatus(volunteerId, hoursId, status, function() {
					notificationMgr.notify('The hours status has been updated.');
				});
			});
		}
	};

	this.getApprovedTotals = function(callback) {
		$.ajax("restservices/hours/approvedTotals", {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});
	};

	this.getHoursByStatus = function(status, callback) {
		$.ajax("restservices/hours/" + status, {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});
	};

	this.updateStatus = function(volunteerId, hoursId, status, callback) {
		$.ajax("restservices/volunteers/" + volunteerId + "/hours/" + hoursId + "/status", {
			type: "PUT",
			data: status,
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});
	};


	var getStatusDropdown = function(pending) {
		var statusList = ["Pending", "Approved", "Declined"];
		var statusDropdownHtml = "<select class=\"pendingStatusSelect\" data-volunteerId=\"" + pending.volunteerId + "\" data-hoursId=\"" + pending.id + "\">";

		for(var i=0; i<statusList.length; i++) {
			statusDropdownHtml += "<option " + (pending.status == statusList[i] ? "selected" : "")+ ">" + statusList[i] + "</option>";
		}
		statusDropdownHtml += "</select>";
		return statusDropdownHtml;
	};

	this.populateClassrooms = function(classrooms) {
		//Populate the classroom dropdown
		var classroomList = $("#adminHoursClassroom");
		for(var i=0; i<classrooms.length; i++) {
			var classroom = classrooms[i];
			var optgroup = $('<optgroup/>', { 'label': classroom.name});

			for(var j=0; j<classroom.teachers.length; j++) {
				var teacher = classroom.teachers[j];
				optgroup.append('<option value="' + teacher.lastName + '">' + teacher.displayName + "</option>");
			}

			classroomList.append(optgroup);
		}
	};

};

var AdminReportsSvc = function(reportsDiv) {

	this.populateInterestAreas = function(interests) {

		interests.sort(function (a, b) {
			if (a.name > b.name)
			  return 1;
			if (a.name < b.name)
			  return -1;
			// a must be equal to b
			return 0;
		});

		interestAreas = interests;

		var interestsList = reportsDiv.find("#reportInterestArea");
		for(var i=0; i<interests.length; i++) {
			var interest = interests[i];
			if(interest.name != 'Other') {
				interestsList.append("<option value=\"" + interest.interestAreaId + "\">" + interest.name + "</option>");			
			}
		}
	};

	this.getVolunteersByInterestAreaId = function(interestAreaId, callback) {
		$.ajax("restservices/interestAreas/" + interestAreaId + "/volunteers", {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});	
	};

	this.populateForm = function(volunteers) {
		volunteers.sort(function (a, b) {
			if (a.lastName > b.lastName)
			  return 1;
			if (a.lastName < b.lastName)
			  return -1;
			// a.lastName must be equal to b.lastName
			if (a.firstName > b.firstName)
			  return 1;
			if (a.firstName < b.firstName)
			  return -1;
			// a.firstName must be equal to b.firstName
			return 0;
		});

		var reportList = reportsDiv.find("#adminReportList");
		reportList.empty();

		if(volunteers.length === 0) {
			reportList.append("<tr><th>No volunteers found for this interest area.</th></tr>");
		}
		else {
			reportList.append("<tr><th>Email</th></tr>");
			for(var i=0; i<volunteers.length; i++) {
				var volunteer = volunteers[i];
				var td = $("<td />").text("<" + volunteer.firstName + " " + volunteer.lastName + "> " + volunteer.emailAddress + ";");
				var tr = $("<tr />").append(td);
				reportList.append(tr);			
			}
		}
	};

};

var RightSvc = function() {
	this.getByRoleId = function(roleId, callback) {
		$.ajax("restservices/roles/" + roleId + "/rights", {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			}
		});
	};
};

var NotificationMgr = function() {
	var self = this;

	this.notify = function(message, callback) {
		var options = {};
		if(callback) {
			options.submit = callback;
		}

		$.prompt(message, options);
	};
};

var hasValue = function(value) {
	return (value && value != "");
};
	
var isValidEmail = function(emailAddress) {
	var emailPattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return emailPattern.test(emailAddress);
};
	
var isValidPassword = function(password) {
	return (password && password.length >= 6);
};
	
var isNumber = function(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
};
	
var isDate = function(date) {
	var datePattern = /^\d{1,2}\/\d{1,2}\/(\d{2}|\d{4})$/;
	return datePattern.test(date);
};

var convertStringToDate = function(dateString) {
	var dateParts = dateString.split("/");

	//Make sure it is a four digit year
	if(dateParts[2].length == 2) {
		dateParts[2] = '20' + dateParts[2];
	}

	var date = new Date(dateParts[2], (dateParts[0] - 1), dateParts[1]);
	return date;
};

var convertJsonDateToDate = function(jsonDateString) {
	//The date will be in the format yyyy-mm-dd, which we can just pass to the date constructor
	var dateParts = jsonDateString.split('-');
	var date = new Date(dateParts[0], (dateParts[1] - 1), dateParts[2]);
	return date;
};

var formatDateForDisplay = function(date) {
	return (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear(); 
};
	
var formatDateForJson = function(date) {
	var milliseconds = date.getTime();
	var formattedString = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();

	return formattedString;
};
	
var getParameterByName = function( name )
{
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	if( results == null )
	return "";
	else
	return decodeURIComponent(results[1].replace(/\+/g, " "));
};

var getInterestArea = function(interestAreas, hoursSubmission) {
	interestAreaName = 'Unknown';
	for(var i=0; i<interestAreas.length; i++) {
		if(interestAreas[i].interestAreaId == hoursSubmission.interestAreaId) {
			interestAreaName = interestAreas[i].name;
		}
	}

	return interestAreaName;
};

