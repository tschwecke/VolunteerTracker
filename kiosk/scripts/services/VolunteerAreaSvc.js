var VolunteerAreaSvc = function(authSvc, restMgr) {

	this.getAll = function(callback) {
		restMgr.get('interestAreas', function(error, interestAreas) {
      if(error) {
        return callback(error);
      }

      restMgr.get('classrooms', function(error, classrooms) {
        if(error) {
          return callback(error);
        }

        //Find the 'Classroom' interest area
        for(var i=0; i<interestAreas.length; i++) {
          if(interestAreas[i].name === 'Classroom') {
            var classroomInterestAreas = createClassroomInterestAreas(interestAreas[i], classrooms);
            //Remove the generic Classroom interest area and add the newly created ones
            interestAreas.splice(i, 1);
            interestAreas = interestAreas.concat(classroomInterestAreas);
            break;
          }
        }

        interestAreas.sort(function compare(a, b) {
          if (a.name < b.name)
             return -1;
          if (a.name > b.name)
             return 1;
          return 0;
        });

        return callback(null, interestAreas);
      });
    });
	};

  var createClassroomInterestAreas = function(classroomInterestArea, classrooms) {
    var classroomInterestAreas = [];
    for(var i=0; i<classrooms.length; i++) {
      for(var j=0; j<classrooms[i].teachers.length; j++) {

        classroomInterestAreas.push({
          'id': classroomInterestArea.id,
          'name': classroomInterestArea.name + ' - ' + classrooms[i].teachers[j].displayName,
          'classroom': classrooms[i].teachers[j].lastName
        });
      }
    }

    return classroomInterestAreas;
  };
};