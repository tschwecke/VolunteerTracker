var HomeController = function(riot) {

	this.render = function() {
		
		console.log('HomeController.render()');

		var homeRactive = new Ractive({
		  el: 'viewContainer',
		  template: '#homeViewTemplate'
		});

		homeRactive.on("checkin", function() {
			homeRactive.teardown(function() {
				riot.route("#checkin");
			});
		});

		homeRactive.on("checkout", function() {
			homeRactive.teardown(function() {
				riot.route("#checkout");
			});
		});

	};
};