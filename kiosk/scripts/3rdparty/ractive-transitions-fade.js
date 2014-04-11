(function ( global, factory ) {

	'use strict';

	// Common JS (i.e. browserify) environment
	if ( typeof module !== 'undefined' && module.exports && typeof require === 'function' ) {
		factory( require( 'Ractive' ) );
	}

	// AMD?
	else if ( typeof define === 'function' && define.amd ) {
		define([ 'Ractive' ], factory );
	}

	// browser global
	else if ( global.Ractive ) {
		factory( global.Ractive );
	}

	else {
		throw new Error( 'Could not find Ractive! It must be loaded before the Ractive-transitions-fade plugin' );
	}

}( typeof window !== 'undefined' ? window : this, function ( Ractive ) {

	'use strict';

	var fade, defaults;

	defaults = {
		delay: 0,
		duration: 300,
		easing: 'linear'
	};

	fade = function ( t, params ) {
		var targetOpacity;

		params = t.processParams( params, defaults );

		if ( t.isIntro ) {
			targetOpacity = t.getStyle( 'opacity' );
			t.setStyle( 'opacity', 0 );
		} else {
			targetOpacity = 0;
		}

		t.animateStyle( 'opacity', targetOpacity, params, t.complete );
	};

	Ractive.transitions.fade = fade;

}));