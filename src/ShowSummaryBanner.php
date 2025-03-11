<?php

namespace MediaWiki\Extension\ArticleSummaries;

class ShowSummaryBanner {
	/**
	 * Generate the banner HTML
	 * @param string $heading The banner heading text
	 * @param string $text The banner small text
	 * @return string The generated HTML
	 */
	public static function getHTML( string $heading, string $text ): string {
		return '<button class="ext-article-summary-banner cdx-button" type="button">
				<div class="ext-article-summary-text-wrapper">
					<div class="ext-article-summary-text">
						' . $heading . '
					</div>
					<small class="ext-article-summary-small">
						' . $text . '
					</small>
				</div>
				<span class="cdx-icon-next"></span>
			</button>';
	}
}
