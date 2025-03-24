// Create and mount the Vue application
let activeApp;
mw.hook( 'wikipage.content' ).add( () => {
	const openDialog = () => {
		const { createApp } = require( 'vue' );
		const { SummaryOverlay } = require( 'ext.articleSummaries.overlay' );
		if ( activeApp ) {
			activeApp.unmount();
		}
		const app = createApp( SummaryOverlay );
		app.mount( '#article-summaries-overlay' );
		activeApp = app;
	};

	const loadDialog = () => {
		mw.loader.using( 'ext.articleSummaries.overlay' ).then( () => {
			openDialog();
		} );
	};

	const button = document.getElementById( 'dialog-button' );
	if ( button ) {
		button.addEventListener( 'click', loadDialog );
	}
} );
