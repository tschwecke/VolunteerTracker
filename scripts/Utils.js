
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

