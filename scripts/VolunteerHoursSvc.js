
var VolunteerHoursSvc = function(hoursDiv) {

	var interestAreas = null;

	this.save = function(volunteerId, hours, callback) {
		//Change the date from a string to a date object
		hours.date = convertStringToDate(hours.date);
		hours.date = formatDateForJson(hours.date);

		var url = "api/volunteers/" + volunteerId + "/hours";
		$.ajax(url, {
			type: "POST",
			data: JSON.stringify(hours),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {

				callback(null, data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				callback({"route": "POST " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
			}
		});
	};

	this.getHoursByVolunteer = function(volunteerId, callback) {
		var url = "api/volunteers/" + volunteerId + "/familyHours";
		$.ajax(url, {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				callback({"route": "GET " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
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

		hoursList.empty();

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

			for(var j=0; j<classroom.teachers.length; j++) {
				var teacher = classroom.teachers[j];
				classroomList.append('<option value="' + teacher.lastName + '">' + teacher.displayName + '</option>');
			}

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
				errors.push(ValidationErrorCodes.DATE_INVALID);
			}
		}
		else {
			errors.push(ValidationErrorCodes.DATE_REQUIRED);
		}
		if(hasValue(hours.nbrOfHours) && hours.nbrOfHours !== 'Select one...') {
			if(!isNumber(hours.nbrOfHours)) {
				errors.push(ValidationErrorCodes.HOURS_INVALID);
			}
		}
		else {
			errors.push(ValidationErrorCodes.HOURS_REQUIRED);
		}
		if(!hasValue(hours.interestAreaId)) errors.push(ValidationErrorCodes.AREA_REQUIRED);

		var interestName = interestsSvc.getInterestNameById(hours.interestAreaId);

		if(interestName === 'Other' && !hasValue(hours.description)) {
			errors.push(ValidationErrorCodes.DESCRIPTION_REQUIRED);
		}

		if(interestName === 'Classroom' && !hasValue(hours.classroom)) {
			errors.push(ValidationErrorCodes.CLASSROOM_REQUIRED);
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
