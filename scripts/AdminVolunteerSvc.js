
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
		var url = "api/volunteers";
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

	this.updateRole = function(volunteerId, roleId, callback) {
		var url = "api/volunteers/" + volunteerId + "/role";
		$.ajax(url, {
			type: "PUT",
			data: JSON.stringify({"id": roleId}),
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				callback({"route": "PUT " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
			}
		});
	};

	var getStatusDropdown = function(volunteer) {
		var statusList = [{'id': 1, 'name': "Pending"},
						{'id': 2, 'name': "Active"},
						{'id': 3, 'name': "Administator"},
						{'id': 4, 'name': "Inactive"},
						{'id': 5, 'name': "Kiosk Admin"}];
		var statusDropdownHtml = "<select class=\"volunteerRoleSelect\" data-volunteerId=\"" + volunteer.id + "\">";

		for(var i=0; i<statusList.length; i++) {
			statusDropdownHtml += "<option value=\"" + statusList[i].id + "\" " + (volunteer.roleId == statusList[i].id ? "selected" : "")+ ">" + statusList[i].name + "</option>";
		}
		statusDropdownHtml += "</select>";
		return statusDropdownHtml;
	};

};
