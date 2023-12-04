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
    foreach ($line["numbers"] as $details) {
        $number = $details[0];
        $start = $details[1];
        $end = ($start + (strlen($number) - 1));
        $addNumber = false;
        echo "$number: $start>$end\r\n";

        // Current Line
        foreach ($tracker[$lineNumber]["symbols"] as $symbol) {
            if ($symbol[1] >= $start - 1 && $symbol[1] <= $end + 1) {
                $addNumber = true;
                echo "  C: " . $symbol[1] . "\r\n";
            }
        }
        
        // Previous line
        if (isset($tracker[$lineNumber - 1])) {
            foreach ($tracker[$lineNumber - 1]["symbols"] as $symbol) {
                if ($symbol[1] >= $start - 1 && $symbol[1] <= $end + 1) {
                    $addNumber = true;
                    echo "  P: " . $symbol[1] . "\r\n";
                }
            }
        }
        
        // Next Line
        if (isset($tracker[$lineNumber + 1])) {
            foreach ($tracker[$lineNumber + 1]["symbols"] as $symbol) {
                if ($symbol[1] >= $start - 1 && $symbol[1] <= $end + 1) {
                    $addNumber = true;
                    echo "  N: " . $symbol[1] . "\r\n";
                }
            }
        }

        if ($addNumber) {
            $sum += $number;
        }
    }
}



echo "\r\n\r\nSum: $sum";
exit();