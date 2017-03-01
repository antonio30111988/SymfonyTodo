<?php

require 'vendor/autoload';

// 2. Instantiate Guzzle HTTP client
$client = new \GuzzleHttp\Client();
// 3. Open and iterate CSV
$csv = new \League\Csv\Reader($argv[1]);
foreach ($csv as $csvRow) {
try {
// 4. Send HTTP OPTIONS request
$httpResponse = $client->options($csvRow[0]);
// 5. Inspect HTTP response status code
if ($httpResponse->getStatusCode() >= 400) {
throw new \Exception();
}
} catch (\Exception $e) {
	// 6. Send bad URLs to standard out
echo $csvRow[0] . PHP_EOL;
}
}