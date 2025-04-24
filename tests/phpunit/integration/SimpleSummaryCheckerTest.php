<?php

namespace MediaWiki\Extension\ArticleSummaries\Tests;

use MediaWiki\Extension\ArticleSummaries\SimpleSummaryChecker;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\ArticleSummaries\SimpleSummaryChecker
 * @group ArticleSummaries
 */
class SimpleSummaryCheckerTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Extension\ArticleSummaries\SimpleSummaryChecker::hasSimpleSummary
	 */
	public function testHasSimpleSummary() {
		$titles = [
			// Titles with periods and parentheses
			Title::makeTitle( NS_MAIN, '...And Justice for All (album)' ),
			// Exlamation marks
			Title::makeTitle( NS_MAIN, 'Yu-Gi-Oh!' ),
			// Titles with colons
			Title::makeTitle( NS_MAIN, 'X-Men: The Last Stand' ),
		];
		$checker = new SimpleSummaryChecker();

		foreach ( $titles as $title ) {
			$hasSummary = $checker->hasSimpleSummary( $title );
			$this->assertTrue( $hasSummary, '$title should have have a summary JSON file' );
		}
	}
}
