<?php
print("<pre>");
$sum = 0;
$handle = fopen("./input.txt", "r");

if ($handle) {
    while (($line = fgets($handle)) !== false) {
        echo "$line";
        preg_match('/^Game (\d+): (.*)/', $line, $gameDetails);
        $gameID = $gameDetails[1];
        $gameRound = $gameDetails[2];
        $maxPerColour = [ "red" => 12, "green" => 13, "blue" => 14];
        $possibleGame = true;
        foreach (explode(";", $gameRound) as $setsOfCubes) {
            preg_match_all('/(\d+)\s(\w+)/', $setsOfCubes, $thisGame);
            list($thisGame, $thisDiceCount, $thisDiceColour) = $thisGame;
            for ($x = 0; $x < count($thisGame); $x++) {
                if ($thisDiceCount[$x] > $maxPerColour[$thisDiceColour[$x]]) {
                    $possibleGame = false;
                }
                echo $gameID . "-$x: " . $thisDiceColour[$x] . ": " . $thisDiceCount[$x] . " (max: " . $maxPerColour[$thisDiceColour[$x]] .") = $possibleGame;\r\n";
            }
        }
        echo "\r\n";
        if ($possibleGame) {
            $sum += $gameID;
        }
    }
    fclose($handle);
}
echo $sum;
exit();