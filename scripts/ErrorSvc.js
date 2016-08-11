
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
		for(var errorCode in ValidationErrorCodes) {
			if(ValidationErrorCodes.hasOwnProperty(errorCode)) {
				parentDiv.find("#" + ValidationErrorCodes[errorCode].elementId).removeClass("validationFailed");
			}
		}
	};
};
