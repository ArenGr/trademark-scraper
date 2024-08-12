<?php

/**
 * Sends a GET request to the specified URL using cURL.
 *
 * @param string $url
 * @return string|null
 * @throws InvalidArgumentException
 */
function get(string $url): ?string {
	if (filter_var($url, FILTER_VALIDATE_URL) === false) {
		throw new InvalidArgumentException('Invalid URL provided.');
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);

	if ($response === false) {
		$error = 'cURL error: '.curl_error($ch);
		curl_close($ch);
		throw new RuntimeException($error);
	}

	curl_close($ch);
	return $response;
}

/**
 * Builds a URL from the base URL, path, and query parameters.
 *
 * @param array $queryParam
 * @param string $pathWithQuery
 * @return string
 * @throws InvalidArgumentException
 */
function buildUrl(array $queryParam = []): string {
	if (empty($_ENV['BASE_URL'])) {
		throw new InvalidArgumentException('BASE_URL is not set in the environment.');
	}
	if (empty($_ENV['SEARCH_PATH'])) {
		throw new InvalidArgumentException('SEARCH_PATH is not set in the environment.');
	}

	if (!empty($queryParam)) {
		if (!is_array($queryParam)) {
			throw new InvalidArgumentException('queryParam must be an array.');
		}
		$queryString = http_build_query($queryParam);
		return sprintf('%s%s?%s', $_ENV['BASE_URL'], $_ENV['SEARCH_PATH'], $queryString);
	}

	return $_ENV['BASE_URL'];
}
