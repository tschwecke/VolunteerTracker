var CheckOutController = function(router, checkInSvc) {

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

	  				checkInSvc.checkOut(event.context.checkIn, function(error) {
		  				checkoutRactive.teardown(function() {
							router.setRoute("home");

							var confirmationRactive = new Ractive({
								el: 'notificationContainer',
								template: '#checkoutConfirmationTemplate',
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