var HomeController = function(router) {

	this.render = function() {
		
		console.log('HomeController.render()');

		var homeRactive = new Ractive({
		  el: 'viewContainer',
		  template: '#homeViewTemplate'
		});

		homeRactive.on("checkin", function() {
			homeRactive.teardown(function() {
				router.setRoute("checkin");
			});
		});

		homeRactive.on("checkout", function() {
			homeRactive.teardown(function() {
				router.setRoute("checkout");
			});
		});

	};
};