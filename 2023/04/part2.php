<?php
print("<pre>");
$sum = 0;
$tracker = [];

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
        
        // Finally calculate our points
        if ($matches > 0) {
            // If we haven't seen this scratchcard before, set our initial quantity of 1
            // However if we have, and it has won, we add an additional winning card
            if (!isset($tracker[$gameNumber])) {
                $tracker[$gameNumber] = 1   ;
            } else {
                $tracker[$gameNumber]++;
            }
            // If we have many of this card (from previous games) get our multiple            }
            $multiple = $tracker[$gameNumber];

            // print_r($matches);
            for ($x = 1; $x <= count($matches); $x++) {
                // If it's a new game then set our existing win to 0
                if (!isset($tracker[$gameNumber + $x])) {
                    $tracker[$gameNumber + $x] = 0;
                }
                // Increment our wins by the number of cards we have played
                $tracker[$gameNumber + $x] += 1 * $multiple;
    
            }
        }

        // echo "Game: $gameNumber - Matches: " . count($matches) . " - Multiple: $multiple\r\n";
        // print_r($tracker);
        // echo "----------------\r\n";
        
    }
    fclose($handle);
}
print_r($tracker);
foreach ($tracker as $tr) {
    $sum += $tr;
}
echo "\r\n\r\nSum: $sum";
exit();
