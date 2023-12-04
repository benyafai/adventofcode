<?php
print("<pre>");
$sum = 0;
$lineNumber = 0;
$tracker= [];
$handle = fopen("./input.txt", "r");

// First we'll parse the lines and grab out our data
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        preg_match_all("/\d+/", $line, $numbers, PREG_OFFSET_CAPTURE);
        preg_match_all("/[^0-9.\s]/", $line, $symbols, PREG_OFFSET_CAPTURE);
        $tracker[] = [
            "numbers" => $numbers[0],
            "symbols" => $symbols[0],
        ];
    }
    fclose($handle);
}

// Now we'll run through our parsed data to see if we have adjacent symbols.
foreach ($tracker as $lineNumber => $line) {
    foreach ($line["symbols"] as $details) {
        if ($details[0] == "*") {
            $multiply = [];
            $position = $details[1];
            echo "*: $position\r\n";

            // Current Line
            foreach ($tracker[$lineNumber]["numbers"] as $number) {
                if ($position >= $number[1] - 1 && $position <= $number[1] + strlen($number[0])) {
                    $multiply[] = $number[0];
                    echo "  C: " . $number[0] . "\r\n";
                }
            }

            // Previous Line
            if (isset($tracker[$lineNumber - 1])) {
                foreach ($tracker[$lineNumber - 1]["numbers"] as $number) {
                    if ($position >= $number[1] - 1 && $position <= $number[1] + strlen($number[0])) {
                        $multiply[] = $number[0];
                        echo "  P: " . $number[0] . "\r\n";
                    }
                }
            }

            // Next Line
            if (isset($tracker[$lineNumber + 1])) {
                foreach ($tracker[$lineNumber + 1]["numbers"] as $number) {
                    if ($position >= $number[1] - 1 && $position <= $number[1] + strlen($number[0])) {
                        $multiply[] = $number[0];
                        echo "  N: " . $number[0] . "\r\n";
                    }
                }
            }

            if (count($multiply) == 2) {
                $sum += $multiply[0] * $multiply[1];
            }
        }
    }
}

echo "\r\n\r\nSum: $sum";
exit();