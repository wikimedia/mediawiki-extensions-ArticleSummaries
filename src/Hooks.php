<?php
namespace MediaWiki\Extension\ArticleSummaries;

use MediaWiki\Hook\BeforePageDisplayHook;
use MediaWiki\Hook\OutputPageBeforeHTMLHook;
use MediaWiki\Output\OutputPage;
use MediaWiki\Skin\Skin;

class Hooks implements OutputPageBeforeHTMLHook, BeforePageDisplayHook {
	/**
	 * For testing purposes
	 * @var SimpleSummaryChecker|null
	 */
	public static $summaryChecker = null;

	/**
	 * @param OutputPage $out
	 * @param string &$text
	 */
	public function onOutputPageBeforeHTML( $out, &$text ) {
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

		$summaryChecker = self::$summaryChecker ?? new SimpleSummaryChecker();
		$title = $out->getTitle();

		// Check if page has simple summaries available
		if ( !$summaryChecker->hasSimpleSummary( $title ) ) {
			return;
		}

		// Add modules for JavaScript and styles functionality
		$out->addModules( 'ext.articleSummaries' );
		$out->addModuleStyles( 'ext.articleSummaries.styles' );

		$summaryContent = $summaryChecker->getSimpleSummaryContent( $title );
		// If the summary JSON does not contain a created date, fall back to the
		// earliest know summary creation date, which is Oct 25 2024, based on the following commit:
		// https://gitlab.wikimedia.org/repos/web/web-experiments-extension/-/commit/88cbaa165dd84dd62054c109603b5bda2c92a11f
		$date = $summaryContent['created'] ?? '10/25/2024';

		$bannerHeading = $out->msg( 'articlesummaries-show-summary-text' )->text();
		$bannerText = $out->msg( 'articlesummaries-show-summary-small' )->params( $date )->text();

		// Add the banner to the page content
		$text = ShowSummaryBanner::getHTML( $bannerHeading, $bannerText ) . $text;
	}

	/**
	 * @param OutputPage $out
	 * @param Skin $skin
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		$summaryChecker = self::$summaryChecker ?? new SimpleSummaryChecker();
		$title = $out->getTitle();

		if ( $summaryChecker->hasSimpleSummary( $title ) ) {
			$summaryResourceUrl = $summaryChecker->getSimpleSummaryResourceUrl( $title );
			$out->addJsConfigVars( [
				'wgArticleSummaryResourceUrl' => $summaryResourceUrl,
			] );
		}
		$out->addHtmlClasses( 'ext-summaries-clientpref-0' );
	}
}
