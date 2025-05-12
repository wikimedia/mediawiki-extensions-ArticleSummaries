// Create and mount the Vue application
let activeApp;
mw.hook( 'wikipage.content' ).add( () => {
	const createSummaryOverlay = () => {
		const { createApp } = require( 'vue' );
		const { SummaryOverlay } = require( 'ext.articleSummaries.overlay' );
		if ( activeApp ) {
			activeApp.unmount();
		}
		const app = createApp( SummaryOverlay );
		app.mount( '#article-summaries-overlay' );
		activeApp = app;
	};

	const loadSummaryOverlay = () => {
		mw.loader.using( 'ext.articleSummaries.overlay' ).then( () => {
			createSummaryOverlay();
		} );
	};

	const button = document.getElementById( 'dialog-button' );
	if ( button ) {
		button.addEventListener( 'click', () => {
			loadSummaryOverlay();
			mw.hook( 'ext.articleSummaries.summary.opened' ).fire();
		} );
	}

	// define the cta modal hook
	mw.hook( 'ext.articleSummaries.cta.open' ).add( () => {
		mw.loader.using( 'ext.articleSummaries.cta' ).then( () => {
			const { createApp } = require( 'vue' );
			const { CtaModal } = require( 'ext.articleSummaries.cta' );
			if ( activeApp ) {
				activeApp.unmount();
			}
			const app = createApp( CtaModal );
			app.mount( '#article-summaries-cta' );
			activeApp = app;
		} );
	} );
} );
