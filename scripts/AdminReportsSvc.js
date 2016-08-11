
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
			interestsList.append("<option value=\"" + interest.interestAreaId + "\">" + interest.name + "</option>");
		}
	};

	this.getVolunteersByInterestAreaId = function(interestAreaId, callback) {
		var url = "api/interestAreas/" + interestAreaId + "/volunteers";
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
				var td = $("<td />").text(volunteer.firstName + " " + volunteer.lastName + " <" + volunteer.emailAddress + ">;");
				var tr = $("<tr />").append(td);
				reportList.append(tr);
			}
		}
	};

};
