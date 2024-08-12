<?php

/**
 * Parses an HTML string and returns a DOMXPath object for querying.
 *
 * @param string $html
 * @return DOMXPath
 */
function parseHtml(string $html): DOMXPath {
	$dom = new DOMDocument();
	$dom->preserveWhiteSpace = false;
	$dom->loadHTML($html, LIBXML_NOERROR);
	return new DOMXPath($dom);
}

/**
 * Extracts data from an HTML string using XPath queries and returns an array.
 *
 * @param string $html
 * @return array
 */
function getData(string $html): array {
	$xpath = parseHtml($html);

	$xpaths = [
		'id' => '//tbody/@data-mark-id',
		'number' => '//td[@class="number"]/a/text()',
		'url_logo' => '//td[@class="trademark image"]/img/@src',
		'name' => '//td[@class="trademark words"]/text()',
		'class' => '//td[contains(@class, "classes")]/text()',
		'status' => '//td[@class="status"]/div/span/text()',
		'url_details_page' => '//td[@class="number"]/a/@href',
	];

	$data = [];
	$ids = $xpath->query($xpaths['id']);
	$numbers = $xpath->query($xpaths['number']);
	$urlLogos = $xpath->query($xpaths['url_logo']);
	$names = $xpath->query($xpaths['name']);
	$classes = $xpath->query($xpaths['class']);
	$statuses = $xpath->query($xpaths['status']);
	$urlDetailsPages = $xpath->query($xpaths['url_details_page']);

	foreach ($ids as $index => $id) {
		$data[] = [
			'number' => $numbers->item($index) ? trim($numbers->item($index)->nodeValue) : null,
			'url_logo' => $urlLogos->item($index) ? $urlLogos->item($index)->nodeValue : null,
			'name' => $names->item($index) ? trim($names->item($index)->nodeValue) : null,
			'class' => $classes->item($index) ? str_replace(["\n", " "], "", $classes->item($index)->nodeValue) : null,
			'status' => $statuses->item($index) ? trim($statuses->item($index)->nodeValue) : null,
			'url_details_page' => $urlDetailsPages->item($index) ? trim($urlDetailsPages->item($index)->nodeValue) : null,
		];
	}
	return $data;
}

/**
 * Generates URLs for paginated pages.
 *
 * @param string $html
 * @return array
 */
function getNextPagesUrls(string $html): array {
	$xpath = parseHtml($html);
	$lastPageNumberNodes = $xpath->query('//a[contains(@class, "goto-last-page")]/@href');

	if ($lastPageNumberNodes->length === 0) {
		return [];
	}

	$lastPageNumber = $lastPageNumberNodes->item(0)->nodeValue;
	$parsedUrl = parse_url($lastPageNumber);

	if (!isset($parsedUrl['query'])) {
		return [];
	}

	parse_str($parsedUrl['query'], $queryParams);
	$baseUrl = $_ENV['BASE_URL'];
	$path = $parsedUrl['path'];

	$urls = [];
	$totalPages = (int)$queryParams['p'];

	for ($count = 1; $count <= $totalPages; $count++) {
		$queryParams['p'] = $count;
		$urls[] = sprintf('%s%s?%s', $baseUrl, $path, http_build_query($queryParams));
	}

	return $urls;
}
