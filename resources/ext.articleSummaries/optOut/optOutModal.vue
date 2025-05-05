<template>
	<cdx-dialog
		v-model:open="dialogOpen"
		:title="title"
		:use-close-button="true"
		class="ext-article-summary-opt-out"
		@default="handleClose"
	>
		<p class="ext-article-summary-opt-out-text">
			{{ text }}
		</p>

		<div class="ext-article-summary-opt-out-buttons">
			<cdx-button
				class="ext-article-summary-opt-out-button-back"
				@click="handleBack"
			>
				{{ buttonBack }}
			</cdx-button>
			<cdx-button
				class="ext-article-summary-opt-out-button-remove"
				action="destructive"
				weight="primary"
				@click="handleRemove"
			>
				{{ buttonRemove }}
			</cdx-button>
		</div>
	</cdx-dialog>
</template>

<script>
const { defineComponent, computed } = require( 'vue' );
const { CdxDialog, CdxButton } = require( '@wikimedia/codex' );
module.exports = defineComponent( {
	components: { CdxDialog, CdxButton },
	props: {
		isOptOutOpen: {
			type: Boolean,
			required: true
		}
	},
	emits: [ 'update:isOptOutOpen' ],
	setup( props, { emit } ) {
		const title = mw.message( 'articlesummaries-opt-out-title' ).text();
		const text = mw.message( 'articlesummaries-opt-out-text' ).text();
		const buttonBack = mw.message( 'articlesummaries-opt-out-button-back' ).text();
		const buttonRemove = mw.message( 'articlesummaries-opt-out-button-remove' ).text();

		const dialogOpen = computed( {
			get: () => props.isOptOutOpen,
			set: ( value ) => emit( 'update:isOptOutOpen', value )
		} );

		const handleClose = () => {
			emit( 'update:isOptOutOpen', false );
		};

		const handleBack = () => {
			emit( 'update:isOptOutOpen', false );
		};

		const handleRemove = () => {
			emit( 'update:isOptOutOpen', false );
			mw.user.clientPrefs.set( 'ext-summaries', '0' );
		};

		return {
			title,
			text,
			buttonBack,
			buttonRemove,
			dialogOpen,
			handleClose,
			handleBack,
			handleRemove
		};
	}
} );
</script>
