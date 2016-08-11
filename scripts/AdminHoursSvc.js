
var AdminHoursSvc = function(adminHoursDiv) {
	var self = this;
	var interestAreas;

	this.populateForm = function(volunteers, interestAreas, hours) {
		var hoursList = adminHoursDiv.find("#adminHoursList");

		hoursList.empty();

		if(hours.length === 0) {
			$("#adminHoursTotal").text("");
			hoursList.append("<tr><th>No hours were found with this status.</th></tr>");
		}
		else {
			//Construct a lookup for volunteer by id
			var volunteersById = {};
			for(var i=0; i<volunteers.length; i++) {
				volunteersById[volunteers[i].id] = volunteers[i];
			}

			var totalHours = 0;
			hoursList.append("<tr><th>Family Number</th><th>Name</th><th>Date</th><th>Interest Area</th><th>Hours</th><th>Status</th>th>Description</th></tr>");
			for(var i=0; i<hours.length; i++) {
				var hoursSubmission = hours[i];
				totalHours += parseFloat(hoursSubmission.nbrOfHours);

				//Find the volunteer for these hours
				var volunteer = volunteersById[hoursSubmission.volunteerId];
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
				$(eventObject.target).prop('disabled', true);
				self.updateStatus(volunteerId, hoursId, status, function() {
					$(eventObject.target).prop('disabled', false);
				});
			});
		}
	};

	this.getApprovedTotals = function(callback) {
		var url = "api/hours/approvedTotals";
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

	this.getHoursByStatus = function(status, callback) {
		var url = "api/hours/" + status;
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

	this.updateStatus = function(volunteerId, hoursId, status, callback) {
		var url = "api/volunteers/" + volunteerId + "/hours/" + hoursId + "/status";
		$.ajax(url, {
			type: "PUT",
			data: status,
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				callback({"route": "PUT " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
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

		var interestsList = adminHoursDiv.find("#adminHoursInterestArea");
		for(var i=0; i<interests.length; i++) {
			var interest = interests[i];
			interestsList.append("<option value=\"" + interest.interestAreaId + "\">" + interest.name + "</option>");
		}
	};

};
