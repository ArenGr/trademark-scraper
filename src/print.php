<?php

/**
 * Reads a JSON file and displays its contents on the CLI.
 *
 * @param string $filePath
 * @return void
 */
function displayResult(string $filePath): void {
	if (!file_exists($filePath)) {
		die("File not found: $filePath\n");
	}

	$jsonData = file_get_contents($filePath);
	$data = json_decode($jsonData, true);

	echo "Results: " . count($data) . "\n";

	foreach ($data as $index => $item) {
		echo ($index + 1) . ". {\n";
		echo "  \"number\": \"" . $item['number'] . "\",\n";
		echo "  \"url_logo\": \"" . $item['url_logo'] . "\",\n";
		echo "  \"name\": \"" . $item['name'] . "\",\n";
		echo "  \"class\": \"" . $item['class'] . "\",\n";
		echo "  \"status\": \"" . $item['status'] . "\",\n";
		echo "  \"url_details_page\": \"" . $item['url_details_page'] . "\"\n";
		echo "},\n";
	}
}
