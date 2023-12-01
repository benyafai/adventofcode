<?php

$sum = 0;
$handle = fopen("./input.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // Regex to remove non-digits.
        $filtered = preg_replace('/[^0-9]/', '$1', $line);
        // Sum the first character and last character
        $sum += (int) (substr($filtered, 0, 1) . substr($filtered, -1));
    }
    fclose($handle);
}
echo $sum;
