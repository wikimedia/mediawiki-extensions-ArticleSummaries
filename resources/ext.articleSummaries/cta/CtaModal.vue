<template>
	<cdx-dialog
		v-model:open="isCtaOpen"
		:title="title"
		:use-close-button="true"
		class="ext-article-summary-cta"
		@default="open = false"
	>
		<div class="ext-article-summary-cta-logo-container">
			<div class="ext-article-summary-cta-logo"></div>
		</div>
		<h3 class="ext-article-summary-cta-subtitle">
			{{ subtitle }}
		</h3>
		<p class="ext-article-summary-cta-text">
			{{ text }}
		</p>
		<a href="https://www.mediawiki.org/wiki/Extension:ArticleSummaries" class="ext-article-summary-cta-link">
			<span class="ext-article-summary-cta-link-text">{{ link }}</span>
			<span class="cdx-icon-link-external"></span>
		</a>

		<div class="ext-article-summary-cta-buttons">
			<cdx-button
				class="ext-article-summary-cta-button-decline"
				@click="isCtaOpen = false"
			>
				{{ buttonDecline }}
			</cdx-button>
			<cdx-button class="ext-article-summary-cta-button-enable">
				{{ buttonEnable }}
			</cdx-button>
		</div>
	</cdx-dialog>
</template>

<script>
const { defineComponent, ref } = require( 'vue' );
const { CdxDialog, CdxButton } = require( '@wikimedia/codex' );
module.exports = defineComponent( {
	components: { CdxDialog, CdxButton },
	setup() {
		const isCtaOpen = ref( true );
		const title = mw.message( 'articlesummaries-summary-cta-title' ).text();
		const subtitle = mw.message( 'articlesummaries-summary-cta-subtitle' ).text();
		const text = mw.message( 'articlesummaries-summary-cta-text' ).text();
		const link = mw.message( 'articlesummaries-summary-cta-link' ).text();
		const buttonEnable = mw.message( 'articlesummaries-summary-cta-button-enable' ).text();
		const buttonDecline = mw.message( 'articlesummaries-summary-cta-button-decline' ).text();

		return {
			isCtaOpen,
			title,
			subtitle,
			text,
			link,
			buttonEnable,
			buttonDecline
		};
	}
} );
</script>
