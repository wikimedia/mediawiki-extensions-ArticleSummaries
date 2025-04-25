<template>
	<cdx-dialog
		v-model:open="isDialogOpen"
		:title="summaryTitle"
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
			{{ summaryText }}
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
const { defineComponent, ref, provide, onMounted } = require( 'vue' );
const { CdxDialog, CdxButton, CdxIcon, CdxInfoChip } = require( '@wikimedia/codex' );
const { cdxIconCheck, cdxIconClose } = require( './icons.json' );
const OptOutModal = require( '../optOut/optOutModal.vue' );

module.exports = defineComponent( {
	components: { CdxDialog, CdxButton, CdxInfoChip, CdxIcon, OptOutModal },
	setup() {
		const isDialogOpen = ref( true );
		const isOptOutOpen = ref( false );
		const generationDateFallback = '10/25/2024';
		const footerHeader = mw.message( 'articlesummaries-summary-overlay-footer-header' ).text();
		const footerText = mw.message( 'articlesummaries-summary-overlay-footer-text-content', generationDateFallback ).parse();
		const notice = mw.message( 'articlesummaries-summary-overlay-notice' ).text();
		const warning = mw.message( 'articlesummaries-summary-overlay-warning' ).text();
		const feedbackQuestion = mw.message( 'articlesummaries-summary-overlay-footer-feedback-question' ).text();
		const feedbackOptionYes = mw.message( 'articlesummaries-summary-overlay-footer-feedback-options-yes' ).text();
		const feedbackOptionNo = mw.message( 'articlesummaries-summary-overlay-footer-feedback-options-no' ).text();
		const optOutButton = mw.message( 'articlesummaries-summary-overlay-footer-opt-out-button' ).text();
		const summaryText = ref( '' );
		const summaryTitle = ref( '' );

		function fetchSummary() {
			fetch( mw.config.get( 'wgArticleSummaryResourceUrl' ) )
				.then( ( response ) => {
					if ( response.ok ) {
						return response.json();
					}
					throw new Error( 'Network response was not ok.' );

				} )
				.then( ( summaryJSON ) => {
					summaryText.value = summaryJSON.summary;
					summaryTitle.value = summaryJSON.title;
					if ( summaryJSON.created ) {
						footerText.value = mw.message(
							'articlesummaries-summary-overlay-footer-text-content',
							summaryJSON.created
						).parse();
					}
				} )
				.catch( () => {
					// TODO: Convert to message if deploying to more than enwiki.
					summaryText.value = 'There was an error fetching summary text';
				} );
		}

		const showOverlay = () => {
			isDialogOpen.value = true;
		};

		provide( 'showSummaryOverlay', showOverlay );

		const handleOptOut = () => {
			isDialogOpen.value = false;
			isOptOutOpen.value = true;
		};

		onMounted( () => {
			fetchSummary();
		} );

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
			handleOptOut,
			summaryText,
			summaryTitle
		};
	}
} );
</script>
