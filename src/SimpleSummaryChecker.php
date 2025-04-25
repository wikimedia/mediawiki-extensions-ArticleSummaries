<?php
namespace MediaWiki\Extension\ArticleSummaries;

use MediaWiki\Config\Config;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\OutputPage;
use MediaWiki\Title\Title;

class SimpleSummaryChecker {

	/** @var Config Extension config */
	private Config $config;

	/** @var string summaryPath */
	private $relativeSummaryDir;

	public function __construct() {
		$this->config = RequestContext::getMain()->getConfig();
		$this->relativeSummaryDir = "/ArticleSummaries/resources/summaries/enwiki/";
	}

	/**
	 * Check if a summary exists for the given title.
	 * @param Title $title The page to check
	 * @return bool Returns true if a summary exists, false otherwise
	 */
	public function hasSimpleSummary( Title $title ) {
		$fullPath = $this->getSimpleSummaryFilePath( $title );
		return is_readable( $fullPath );
	}

	/**
	 * Return the path of a summary with a version hash, if it exists.
	 * @param Title $title The page to check
	 * @return bool|string Returns false if no summary exists, or the path with cache suffix if it does
	 */
	public function getSimpleSummaryResourceUrl( Title $title ) {
		if ( !$this->hasSimpleSummary( $title ) ) {
			return false;
		}

		$titleText = $title->getText();
		$sanitizedTitleText = $this->sanitizeTitle( $titleText );
		$config = RequestContext::getMain()->getConfig();
		$extensionAssetPath = $this->config->get( MainConfigNames::ExtensionAssetsPath );
		$resourcePath = $extensionAssetPath .
			'/ArticleSummaries/resources/summaries/enwiki/' .
			$sanitizedTitleText . '.json';

		return OutputPage::transformResourcePath( $config, $resourcePath );
	}

	/**
	 * Given an article title, check if a summary exists,
	 * and if it does, return the content of that JSON.
	 * If it doesn't, return false.
	 * @param Title $title
	 * @return array Returns an array of the summary content or [].
	 */
	public function getSimpleSummaryContent( Title $title ) {
		if ( !$this->hasSimpleSummary( $title ) ) {
			return [];
		}

		$filePath = $this->getSimpleSummaryFilePath( $title );
		$content = file_get_contents( $filePath );
		if ( $content === false ) {
			return [];
		}
		return json_decode( $content, true );
	}

	/**
	 * Helper method to get the full path of the summary file for a given title.
	 * @param Title $title The page to check
	 * @return string The full path to the summary file
	 */
	private function getSimpleSummaryFilePath( Title $title ) {
		$titleText = $title->getText();
		$sanitizedTitleText = $this->sanitizeTitle( $titleText );
		$extensionDir = $this->config->get( MainConfigNames::ExtensionDirectory );
		return $extensionDir . $this->relativeSummaryDir . $sanitizedTitleText . '.json';
	}

	/**
	 * Convert title to associated file, which is stripped of special character
	 * in order to conform to filename conventions.
	 * Equivalent to the code used to generate the summary files in the following:
	 * https://gitlab.wikimedia.org/repos/web/simple-summary-server/-/blob/main/output_filters/output_to_dir_json.py?ref_type=heads#L54
	 *
	 * @param string $title The title to sanitize
	 * @return string The sanitized title
	 */
	private function sanitizeTitle( string $title ): string {
		return preg_replace( '/[\\\\\/*?:"<>|]/', '', $title );
	}
}
