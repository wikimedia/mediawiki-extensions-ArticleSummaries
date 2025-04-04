<?php
namespace MediaWiki\Extension\ArticleSummaries;

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Output\OutputPage;

class Hooks {
	/**
	 * @param \OutputPage $out
	 * @param string &$text
	 */
	public static function onOutputPageBeforeHTML( OutputPage $out, &$text ) {
		if ( !$out->getConfig()->get( 'ArticleSummariesEnabled' ) ) {
			return;
		}

		// Only show on Minerva skin
		if ( $out->getSkin()->getSkinName() !== 'minerva' ) {
			return;
		}

		// Only show for anonymous users
		if ( !$out->getUser()->isAnon() ) {
			return;
		}

		// Check if page has hatnotes or amboxes
		if ( strpos( $text, 'hatnote' ) !== false || strpos( $text, 'ambox' ) !== false ) {
			$logger = LoggerFactory::getInstance( 'ArticleSummaries' );
			$logger->warning( 'ArticleSummaries not supported on this page.' );
			return;
		}

		$title = $out->getTitle();

		// Check if page has simple summaries available
		if ( !SimpleSummaryChecker::hasSimpleSummaries( $title ) ) {
			return;
		}

		// Add modules for JavaScript and styles functionality
		$out->addModules( 'ext.articleSummaries' );
		$out->addModuleStyles( 'ext.articleSummaries.styles' );

		$date = 'mm/dd/yyyy';

		$bannerHeading = $out->msg( 'articlesummaries-show-summary-text' )->text();
		$bannerText = $out->msg( 'articlesummaries-show-summary-small' )->params( $date )->text();

		// Add the banner to the page content
		$text = ShowSummaryBanner::getHTML( $bannerHeading, $bannerText ) . $text;
	}

	public static function onBeforePageDisplay( OutputPage $out ) {
		$out->addHtmlClasses( 'ext-summaries-clientpref-0' );
	}
}
