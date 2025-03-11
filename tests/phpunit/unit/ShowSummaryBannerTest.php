<?php

namespace MediaWiki\Extension\ArticleSummaries\Tests;

use MediaWiki\Extension\ArticleSummaries\ShowSummaryBanner;
use MediaWikiUnitTestCase;

/**
 * @coversDefaultClass \MediaWiki\Extension\ArticleSummaries\ShowSummaryBanner
 */
class ShowSummaryBannerTest extends MediaWikiUnitTestCase {

	/**
	 * @covers ::getHTML
	 */
	public function testGetHTML() {
		$heading = 'Test Heading';
		$text = 'Test Text';

		$html = ShowSummaryBanner::getHTML( $heading, $text );

		// Test that the HTML contains our content
		$this->assertStringContainsString( $heading, $html );
		$this->assertStringContainsString( $text, $html );

		// Test that it has the required classes
		$this->assertStringContainsString( 'class="ext-article-summary-banner cdx-button"', $html );
		$this->assertStringContainsString( 'class="ext-article-summary-text"', $html );
		$this->assertStringContainsString( 'class="ext-article-summary-small"', $html );
		$this->assertStringContainsString( 'class="cdx-icon-next"', $html );
	}
}
