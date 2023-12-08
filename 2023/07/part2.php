<?php
echo "<pre>";

$games = [];
$handle = fopen("./input.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // Typically, we'll parse our data out with regex
        preg_match('/(\w{5}) (\d+)/', $line, $filtered);
        // And add them to an array
        $games[] = [
            "hand" => $filtered[1],
            "bid" => $filtered[2],
        ];
    }
    fclose($handle);
}


// Magical usort
usort($games, function($a, $b) {
    // Card Values
    $cards = [
        "2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8,
        "9" => 9, "T" => 10, "J" => 1, "Q" => 12, "K" => 13, "A" => 14,
    ];                       ////////
    
    // Rank our games, give them a fixed value
    $hands = [];
    foreach([$a, $b] as $id => $play) {
        // Make life easier by converting all cards to number values
        $splitHand = str_split($play["hand"]);
        foreach ($splitHand as $c => $card) {
            $splitHand[$c] = $cards[$card];
        }
        
        // This is the special part
        $count = array_count_values($splitHand);
        arsort($count, SORT_NUMERIC);

        // Because we now have the 'J' wildcard, and have converted score to 1...
        if (isset($count[1])) {
            $numberOfJ = $count[1];
            $keys = array_keys($count);
            // If count == 1, then it must be all J, so do nothing, otherwise:
            if (count($count) > 1) {
                if ($keys[0] != 1) {
                    $topResult = $keys[0];
                } else {
                    $topResult = $keys[1];
                }
                // Remove our 'J's
                unset($count[1]);
                // And add them to our other top card
                $count[$topResult] += $numberOfJ;
            }
        }

        if (count($count) == 5) {
            // 5 cards means all are different
            $rank = 1; // High Card
        } elseif (count($count) == 4) {
            // 4 count means that only card is the same as another
            $rank = 2; // One Pair 
        } elseif (count($count) == 3) {
            if($count[array_key_first($count)] == 2) {
                $rank = 3; // Two Pair
            } else {
                $rank = 4; // Three of a kind
            }
        } elseif (count($count) == 2) {
            if($count[array_key_first($count)] == 3) {
                $rank = 5; // Full house (3 + 2
            } else {
                $rank = 6; // Four of a Kind
            }
        } elseif (count($count) == 1) {
            // All cards are the same
            $rank = 7; // Five of a kind
        }
        $hands[$id] = $play;
        $hands[$id]["rank"] = $rank;
        $hands[$id]["split"] = $splitHand;
        echo "Hand: " . $play["hand"] . " | Rank: $rank\r\n";
        print_r($count);

    }

    // print_r($hands);
    echo "\r\n-----\r\n";

    // If the ranked value is the same, sort by Card value
    if ($hands[0]["rank"] == $hands[1]["rank"]) {
        for ($x = 0; $x <= 4; $x++) {
            if ($hands[0]["split"][$x] > $hands[1]["split"][$x]) {
                return 1;
            } elseif ($hands[0]["split"][$x] < $hands[1]["split"][$x]) {
                return -1;
            }
        }
    }

    // Assuming we have different ranks, the highest wins
    if ($hands[0]["rank"] > $hands[1]["rank"]) {
        return 1;
    } elseif ($hands[0]["rank"] < $hands[1]["rank"]) {
        return -1;
    }
    return 0;
});

// Now to calculate the winnings per bid
foreach ($games as $rank => $game) {
    $winnings[] = ($rank + 1) * $game["bid"];
}


$sum = array_sum($winnings);
echo $sum;
exit();
