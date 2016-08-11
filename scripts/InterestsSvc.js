
var InterestsSvc = function(interestsDiv) {
	var _interestAreas = null;

	this.getAll = function(callback) {
		var url = "api/interestAreas";
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

	this.getByUser = function(volunteerId, callback) {
		var url = "api/volunteers/" + volunteerId + "/selectedInterests";
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

	this.save = function(volunteerId, interests, callback) {
		var url = "api/volunteers/" + volunteerId + "/selectedInterests"
		$.ajax(url, {
			type: "PUT",
			data: JSON.stringify(interests),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {

				callback(null);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				callback({"route": "PUT " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
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
				break;
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
