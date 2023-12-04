<?php
print("<pre>");
$sum = 0;

$handle = fopen("./input.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $matches = [];
        // First, grab our game number and details
        preg_match('/Card\s+(\d+): (.*)/', $line, $game);
        $gameNumber = $game[1];
        $gameDetails = $game[2];
        // Then explode the winning numbers from our playable numbers
        list($winners, $myNumbers) = explode(" | ", $game[2]);
        // Then grab each number out into an array
        preg_match_all('/(\d+)/', $winners, $winners);
        preg_match_all('/(\d+)/', $myNumbers, $myNumbers);

        // Then check to see how many matches we have
        foreach($myNumbers[0] as $myNum) {
            if (in_array($myNum, $winners[0])) {
                $matches[] = $myNum;
            }
        }

        // Finally calculate our points.
        print_r($matches);
        if (count($matches) == 1) {
            echo "1 point\r\n";
            $sum += 1;
        } elseif (count($matches) > 1) {
            echo pow(2, (count($matches) - 1)) . "points\r\n";
            $sum += pow(2, (count($matches) - 1));
        }
    }
    fclose($handle);
}
echo "\r\n\r\nSum: $sum";
exit();