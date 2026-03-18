<?php namespace ProcessWire;

/**
 * FormatMicrosoftProtectedLinks module for ProcessWire
 *
 * Hooks page save to find and replaces Microsoft protected links from
 * Outlook/Teams/Office with their original links
 *
 * @author Australian Antarctic Division
 * @copyright 2026 Commonwealth of Australia
 */

use DOMDocument;
libxml_use_internal_errors(true); 

class FormatMicrosoftProtectedLinks extends WireData implements Module, ConfigurableModule {

	public static function getModuleInfo() {
		return [
			'title' => 'Replace Outlook Protected Links',
			'version' => '100',
			'summary' => 'Replace protected links from Outlook/Teams/Microsoft Office with the original link.',
			'author' => 'Australian Antarctic Division',
			'icon' => 'link',
			'autoload' => true
		];
	}

	public function ready() {
		$this->addHookBefore('Pages::save', $this, 'formatLinks');
	}

	public function formatLinks(HookEvent $event) {
		$page = $event->arguments(0);

		// get fields as set in module configuration
		$fieldsToCheck = $this->formatFields;

		// for each set field, run format links and save value back to page
		foreach ($fieldsToCheck as $toCheck) {
			$field = $page->getField($toCheck);
			$str = $page->get("$field");
			
			if (empty($str)) {
				continue;
			}

			$dom = new DOMDocument();
			if ($dom->loadHTML('<?xml encoding="utf-8" ?>' . $str, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD) === false) {
				continue; // error loading markup from text/textarea field, exit here
			}
			$anchors = $dom->getElementsByTagName('a');

			foreach ($anchors as $anchor) {
				$parsedURL = parse_url($anchor->getAttribute('href'));
				if ($parsedURL === false) {
					continue; // malformed URL, skip
				}

				if (preg_match("/safelinks\.(protection\.outlook|office)\.com$/", $parsedURL['host'])) {
					parse_str($parsedURL['query'], $parsedQuery);
					if (($href = $parsedQuery['url'] ?? '') !== '') {
						$anchor->setAttribute('href', $href);
					}
				}
			}
			$str = $dom->saveHtml();
			$page->$field = $str;
		}

		$event->arguments(0, $page);
	}
}