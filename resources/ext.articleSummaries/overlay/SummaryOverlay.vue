<template>
	<cdx-dialog
		v-model:open="isDialogOpen"
		title="Article Title Here"
		:use-close-button="true"
		class="ext-article-summary-overlay"
		@default="open = false"
	>
		<div class="ext-article-summary-overlay-notice">
			<p>{{ notice }}</p>
			<cdx-info-chip status="warning">
				{{ warning }}
			</cdx-info-chip>
		</div>
		<p class="ext-article-summary-overlay-text">
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
			incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
			exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
			irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
			pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui
			officia deserunt mollit anim id est laborum.
		</p>
		<div class="ext-article-summary-overlay-feedback-question">
			{{ feedbackQuestion }}
		</div>
		<div class="ext-article-summary-overlay-feedback-options">
			<cdx-button class="ext-article-summary-overlay-yes-button">
				<cdx-icon :icon="cdxIconCheck"></cdx-icon>
				{{ feedbackOptionYes }}
			</cdx-button>
			<cdx-button class="ext-article-summary-overlay-no-button">
				<cdx-icon :icon="cdxIconClose"></cdx-icon>
				{{ feedbackOptionNo }}
			</cdx-button>
		</div>
		<template #footer-text>
			<h3>{{ footerHeader }}</h3>
			<p>{{ footerText }}</p>
		</template>
	</cdx-dialog>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { CdxDialog, CdxButton, CdxIcon, CdxInfoChip } = require( '@wikimedia/codex' );
const { cdxIconCheck, cdxIconClose } = require( './icons.json' );

module.exports = defineComponent( {
	components: { CdxDialog, CdxButton, CdxInfoChip, CdxIcon },
	setup() {
		const isDialogOpen = ref( true );
		const footerHeader = mw.message( 'articlesummaries-summary-overlay-footer-header' ).text();
		const footerText = mw.message( 'articlesummaries-summary-overlay-footer-text-content' ).text();
		const notice = mw.message( 'articlesummaries-summary-overlay-notice' ).text();
		const warning = mw.message( 'articlesummaries-summary-overlay-warning' ).text();
		const feedbackQuestion = mw.message( 'articlesummaries-summary-overlay-footer-feedback-question' ).text();
		const feedbackOptionYes = mw.message( 'articlesummaries-summary-overlay-footer-feedback-options-yes' ).text();
		const feedbackOptionNo = mw.message( 'articlesummaries-summary-overlay-footer-feedback-options-no' ).text();

		return {
			isDialogOpen,
			footerHeader,
			footerText,
			notice,
			warning,
			feedbackQuestion,
			feedbackOptionYes,
			feedbackOptionNo,
			cdxIconCheck,
			cdxIconClose
		};
	}
} );
</script>
