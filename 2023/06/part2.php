<?php
echo "<pre>";

$handle = fopen("./input.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // Parse out our times and distances with regex.
        if (strpos($line, "Time:") !== false) {
            preg_match_all('/(\d+)/', $line, $times);
        }
        if (strpos($line, "Distance:") !== false) {
            preg_match_all('/(\d+)/', $line, $distances);
        }
    }
    fclose($handle);
}

// We are concatenating our numbers into a single number
// Lets implode the numbers, reset the array to a single entry, and we don't have to rewrite anything else.
$tempTime = implode("", $times[0]);
$times = [];
$times[0][0] = $tempTime;
$tempDistance = implode("", $distances[0]);
$distances = [];
$distances[0][0] = $tempDistance;


// Process each time and distance for the number of possibilities
$winCount = [];
for ($race = 0; $race < count($times[0]); $race++) {
    $winCount[$race] = 0;
    for ($seconds = 0; $seconds <= $times[0][$race]; $seconds++) {
        $power = $speed = $seconds;
        $distance = (($times[0][$race] - $power) * $speed);
        if ($distance > $distances[0][$race]) {
            $winCount[$race]++;
        }
    }
}

// Multiply the results from each race
$sum = array_product($winCount);

echo $sum;
exit();
