<?php

namespace MediaWiki\Extension\ArticleSummaries\Tests;

use MediaWiki\Extension\ArticleSummaries\Hooks;
use MediaWikiUnitTestCase;
use OutputPage;
use HashConfig;
use Message;

/**
 * @coversDefaultClass \MediaWiki\Extension\ArticleSummaries\Hooks
 * Just a test to confirm that phpunit is working
 */
class HooksTest extends MediaWikiUnitTestCase {
	/**
	 * @covers ::onOutputPageBeforeHTML
	 */
	public function testOnOutputPageBeforeHTML() {
		$config = new HashConfig( [
			'ArticleSummariesEnabled' => true
		] );

		$outputPageMock = $this->getMockBuilder( OutputPage::class )
			->disableOriginalConstructor()
			->getMock();

		$outputPageMock->method( 'getConfig' )
			->willReturn( $config );

		$outputPageMock->expects( $this->once() )
			->method( 'addModules' )
			->with( 'ext.articleSummaries' );

		$text = 'Original content';
		Hooks::onOutputPageBeforeHTML( $outputPageMock, $text );

		$expectedText = '<div class="simpleSummary" style="border:solid 1px red; background:yellow; color: red; padding: 10px;">Summaries</div>' . 
			'Original content<button id="sumEnable">Enable summaries experiment</button>';
		$this->assertSame( $expectedText, $text );
	}

	/**
	 * @covers ::onOutputPageBeforeHTML
	 */
	public function testOnOutputPageBeforeHTMLWhenDisabled() {
		$config = new HashConfig( [
			'ArticleSummariesEnabled' => false
		] );

		$outputPageMock = $this->getMockBuilder( OutputPage::class )
			->disableOriginalConstructor()
			->getMock();

		$outputPageMock->method( 'getConfig' )
			->willReturn( $config );

		$outputPageMock->expects( $this->never() )
			->method( 'addModules' );

		$text = 'Original content';
		$originalText = $text;
		Hooks::onOutputPageBeforeHTML( $outputPageMock, $text );

		$this->assertSame( $originalText, $text );
	}
}
