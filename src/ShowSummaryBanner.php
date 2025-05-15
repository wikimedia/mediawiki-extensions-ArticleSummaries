<?php

namespace MediaWiki\Extension\ArticleSummaries;

class ShowSummaryBanner {
	/**
	 * Generate the banner HTML
	 * @param string $headingText The banner heading text
	 * @param string $text The banner small text
	 * @return string The generated HTML
	 */
	public static function getHTML( string $headingText, string $text ): string {
		return '<div class="ext-article-summaries-container">
				<button id="dialog-button" class="ext-article-summary-banner cdx-button" type="button">
					<div class="ext-article-summary-text-wrapper">
						<div class="ext-article-summary-text">
							' . htmlspecialchars( $headingText ) . '
						</div>
						<small class="ext-article-summary-small">
							' . htmlspecialchars( $text ) . '
						</small>
					</div>
					<span class="cdx-icon-next"></span>
				</button>
				<div id="article-summaries-overlay"></div>
				<div id="article-summaries-cta"></div>
				<div id="article-summaries-opt-out"></div>
			</div>';
	}
}
