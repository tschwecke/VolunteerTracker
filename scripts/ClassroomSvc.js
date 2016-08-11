
var ClassroomSvc = function() {
	this.getClassrooms = function(callback) {
		var url = "scripts/classrooms.json"
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
};
