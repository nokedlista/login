<?php 
function getCsvData($fileName) {
	$csvFile = fopen($fileName, 'r');
    $lines = [];
	while (! feof($csvFile)) {
		$line = fgetcsv($csvFile);
		$lines[] = $line;
	}
	fclose($csvFile);

	return $lines;
}