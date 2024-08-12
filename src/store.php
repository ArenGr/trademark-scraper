<?php

function store(array $data, string $filePath): void {
	if (file_exists($filePath)) {
		$jsonData = file_get_contents($filePath);
		$dataArray = json_decode($jsonData, true) ?? [];
		$dataArray = array_merge($dataArray, $data);
	} else {
		$dataArray = array($data);
	}

	$jsonContent = json_encode($dataArray, JSON_PRETTY_PRINT);

	if (file_put_contents($filePath, $jsonContent) === false) {
		die("Failed to save data to JSON file.");
	}
	echo "Data successfully saved to $filePath \n";
}
