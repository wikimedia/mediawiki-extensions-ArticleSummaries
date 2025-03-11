<?php

namespace MediaWiki\Extension\ArticleSummaries\Tests;

use HashConfig;
use Language;
use MediaWiki\Extension\ArticleSummaries\Hooks;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;
use Message;
use OutputPage;
use Skin;

/**
 * @coversDefaultClass \MediaWiki\Extension\ArticleSummaries\Hooks
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

		$skinMock = $this->createMock( Skin::class );
		$skinMock->method( 'getSkinName' )
			->willReturn( 'minerva' );

		$outputPageMock->method( 'getSkin' )
			->willReturn( $skinMock );

		$titleMock = $this->createMock( Title::class );
		$outputPageMock->method( 'getTitle' )
			->willReturn( $titleMock );

		$languageMock = $this->createMock( Language::class );
		$languageMock->method( 'sprintfDate' )
			->willReturn( '03/14/24' );

		$outputPageMock->method( 'getLanguage' )
			->willReturn( $languageMock );

		// Mock message objects
		$showSummaryTextMsg = $this->createMock( Message::class );
		$showSummaryTextMsg->method( 'text' )
			->willReturn( 'Show summary' );

		$showSummarySmallMsg = $this->createMock( Message::class );
		$showSummarySmallMsg->method( 'params' )
			->willReturn( $showSummarySmallMsg );
		$showSummarySmallMsg->method( 'text' )
			->willReturn( 'Last updated 03/14/24' );

		// Mock msg() method to return appropriate message objects
		$outputPageMock->method( 'msg' )
			->willReturnCallback( static function ( $key ) use ( $showSummaryTextMsg, $showSummarySmallMsg ) {
				if ( $key === 'show-summary-text' ) {
					return $showSummaryTextMsg;
				}
				if ( $key === 'show-summary-small' ) {
					return $showSummarySmallMsg;
				}
				return null;
			} );

		// Test that modules are added
		$outputPageMock->expects( $this->once() )
			->method( 'addModules' )
			->with( 'ext.articleSummaries' );

		$outputPageMock->expects( $this->once() )
			->method( 'addModuleStyles' )
			->with( 'ext.articleSummaries.styles' );

		$text = 'Original content';
		Hooks::onOutputPageBeforeHTML( $outputPageMock, $text );

		// Test that original content is preserved and banner is added
		$this->assertStringContainsString( 'Original content', $text );
		$this->assertStringContainsString( 'Show summary', $text );
		$this->assertStringContainsString( 'Last updated 03/14/24', $text );
	}

	/**
	 * @covers ::onOutputPageBeforeHTML
	 */
	public function testOnOutputPageBeforeHTMLWhenDisabled() {
		$config = new HashConfig( [
			'ArticleSummariesEnabled' => false
		] );

		$out = $this->createMock( OutputPage::class );
		$out->method( 'getConfig' )
			->willReturn( $config );

		$text = 'Original content';
		$originalText = $text;

		Hooks::onOutputPageBeforeHTML( $out, $text );

		$this->assertSame( $originalText, $text );
	}

	/**
	 * @covers ::onOutputPageBeforeHTML
	 */
	public function testOnOutputPageBeforeHTMLWithHatnote() {
		$config = new HashConfig( [
			'ArticleSummariesEnabled' => true
		] );

		$out = $this->createMock( OutputPage::class );
		$out->method( 'getConfig' )
			->willReturn( $config );
		$out->method( 'getSkin' )
			->willReturn( $this->createSkinMock( 'minerva' ) );

		$text = 'Content with hatnote';
		$originalText = $text;

		Hooks::onOutputPageBeforeHTML( $out, $text );

		$this->assertSame( $originalText, $text );
	}

	private function createSkinMock( string $name ) {
		$skin = $this->createMock( Skin::class );
		$skin->method( 'getSkinName' )
			->willReturn( $name );
		return $skin;
	}
}
