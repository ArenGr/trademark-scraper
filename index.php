<?php
declare(strict_types=1);

define('APP_ROOT', __DIR__);

require_once APP_ROOT.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

require_once APP_ROOT.'/src/http.php';
require_once APP_ROOT.'/src/parser.php';
require_once APP_ROOT.'/src/store.php';
require_once APP_ROOT.'/src/print.php';

if ($argc !== 2) {
	echo "Usage: php index.php <word>\n";
	exit(1);
}

$word = $argv[1];

$url = buildUrl(['q' => $word]);
$result = get($url);

if (empty($result)) {
	echo "Error fetching the search results.\n";
	exit(1);
}

$data = getData($result);
store($data, 'output.json');

$nextPageUrls = getNextPagesUrls($result);
foreach ($nextPageUrls as $url) {
	$nextPage = get($url);

	if ($nextPage === false) {
		echo "Error fetching the next page: $url\n";
		continue;
	}

	$nextPageData = getData($nextPage);
	store($nextPageData, 'output.json');
}

displayResult('output.json');
