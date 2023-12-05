<?php
print("<pre>");
$startTime = microtime(true);
$handle = fopen("./input.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, "seeds: ") !== false) {
            preg_match_all('/(\d+)/', $line, $seeds);
        } else {
            preg_match('/(\w+)-to-(\w+) map:/', $line, $map);
            if (isset($map[1]) && isset($map[2])) {
                $source = $map[1];
                $maps[$source]["target"] = $map[2];
            }
            preg_match('/(\d+) (\d+) (\d+)/', $line, $values);
            if (isset($values[1]) && isset($values[2]) && isset($values[3])) {
                $maps[$source]["values"][$values[2]]["map"] = $values[1];
                $maps[$source]["values"][$values[2]]["range"] = $values[3];
            }
        }
    }
    fclose($handle);
}

// Now to process our seeds and maps
foreach ($seeds[0] as $seed) {
    $source = "seed";
    echo "$source = $seed\r\n";
    foreach ($maps as $sources => $destination) {
        $mapDone = false;
        foreach ($maps[$source]["values"] as $from => $to) {
            if ($seed >= $from && $seed <= ($from + ($to["range"] - 1)) && $mapDone === false) {
                $diff = $seed - $from;
                $seed = $to["map"] + $diff;
                $mapDone = true;
            }
        }
        $source = $destination["target"];
        echo "$source = $seed\r\n";
    }
    $locations[] = $seed;
    echo "\r\n";
}
echo "Lowest location: " . min($locations) . "\r\n\r\n";
echo "Time: " . (microtime(true) - $startTime) . " seconds";
// print_r($maps);
exit();
