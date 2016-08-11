
//Error Codes
var ValidationErrorCodes = {
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
