var CheckOutController = function(riot, checkInSvc) {

	this.render = function() {
		
		console.log('CheckOutController.render()');
		checkInSvc.getAllCurrent(function(error, checkIns) {
			var checkoutRactive = new Ractive({
			  el: 'viewContainer',
			  template: '#checkoutViewTemplate',
			  data: {
			  	checkIns: checkIns
			  }
			});

			checkoutRactive.on("home", function(event) {
				checkoutRactive.teardown(function() {
					riot.route("#home");
				});
			});

			checkoutRactive.on("gotoCheckIn", function(event) {
				checkoutRactive.teardown(function() {
					riot.route("#checkin");
				});
			});
			
			checkoutRactive.on("checkoutUser", function(event) {
				var modal = picoModal({
					"content": "<div id='checkoutUserModal'></div>",
					"closeButton": false,
	  				"overlayClose": false,
	  				"modalStyles": {
	  					"top": "200px",
	  					"margin-left": "-175px",
	  					"width": "350px",
	  					"border-radius": "5px",
	  					"background-color": "white",
	  					"padding": "20px"
	  				}
	  			});

	  			var checkoutUserModalRactive = new Ractive({
					el: 'checkoutUserModal',
					template: '#checkoutUserModalTemplate',
					data: {
						"checkIn": event.context
					}
				});

	  			checkoutUserModalRactive.on('checkoutUserCanceled', function() {
	  				modal.close();
	  			});

	  			checkoutUserModalRactive.on('checkoutUserConfirmed', function(event) {
	  				modal.close();

	  				checkInSvc.checkOut(event.context.checkIn, function(error, checkIn) {
		  				checkoutRactive.teardown(function() {
							riot.route("#home");

							var confirmationRactive = new Ractive({
								el: 'notificationContainer',
								template: '#checkoutConfirmationTemplate',
								data: {
									'checkIn': checkIn
								}
		  				});

	  					setTimeout(function() {
	  						confirmationRactive.teardown();
	  					}, 6000);
						});
					});
	  			});
			});
		});
	};
};