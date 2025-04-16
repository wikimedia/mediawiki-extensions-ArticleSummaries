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
			<h3 class="ext-article-summary-overlay-footer-header">
				{{ footerHeader }}
			</h3>
			<!-- eslint-disable-next-line vue/no-v-html -->
			<p class="ext-article-summary-overlay-footer-text" v-html="footerText"></p>
			<cdx-button
				class="ext-article-summary-overlay-opt-out-button"
				@click="handleOptOut">
				<cdx-icon :icon="cdxIconClose"></cdx-icon>
				{{ optOutButton }}
			</cdx-button>
		</template>
	</cdx-dialog>
	<opt-out-modal v-model:is-opt-out-open="isOptOutOpen"></opt-out-modal>
</template>

<script>
const { defineComponent, ref, provide } = require( 'vue' );
const { CdxDialog, CdxButton, CdxIcon, CdxInfoChip } = require( '@wikimedia/codex' );
const { cdxIconCheck, cdxIconClose } = require( './icons.json' );
const OptOutModal = require( '../optOut/optOutModal.vue' );

module.exports = defineComponent( {
	components: { CdxDialog, CdxButton, CdxInfoChip, CdxIcon, OptOutModal },
	setup() {
		const isDialogOpen = ref( true );
		const isOptOutOpen = ref( false );
		const footerHeader = mw.message( 'articlesummaries-summary-overlay-footer-header' ).text();
		const footerText = mw.message( 'articlesummaries-summary-overlay-footer-text-content' ).parse();
		const notice = mw.message( 'articlesummaries-summary-overlay-notice' ).text();
		const warning = mw.message( 'articlesummaries-summary-overlay-warning' ).text();
		const feedbackQuestion = mw.message( 'articlesummaries-summary-overlay-footer-feedback-question' ).text();
		const feedbackOptionYes = mw.message( 'articlesummaries-summary-overlay-footer-feedback-options-yes' ).text();
		const feedbackOptionNo = mw.message( 'articlesummaries-summary-overlay-footer-feedback-options-no' ).text();
		const optOutButton = mw.message( 'articlesummaries-summary-overlay-footer-opt-out-button' ).text();

		const showOverlay = () => {
			isDialogOpen.value = true;
		};

		provide( 'showSummaryOverlay', showOverlay );

		const handleOptOut = () => {
			isDialogOpen.value = false;
			isOptOutOpen.value = true;
		};

		return {
			isDialogOpen,
			isOptOutOpen,
			footerHeader,
			footerText,
			notice,
			warning,
			feedbackQuestion,
			feedbackOptionYes,
			feedbackOptionNo,
			optOutButton,
			cdxIconCheck,
			cdxIconClose,
			handleOptOut
		};
	}
} );
</script>
