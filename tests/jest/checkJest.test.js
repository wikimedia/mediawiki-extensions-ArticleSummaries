'use strict';

const checkJest = require( '../../resources/checkJest' );

describe( 'Checking if running Jest works', () => {
	test( 'check if jest is enabled', () => {
		expect( checkJest() ).toBe( 'Jest is enabled' );
	} );
} );