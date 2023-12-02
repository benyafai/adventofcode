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
        $maxPerColour = [ "red" => 0, "green" => 0, "blue" => 0];
        foreach (explode(";", $gameRound) as $setsOfCubes) {
            preg_match_all('/(\d+)\s(\w+)/', $setsOfCubes, $thisGame);
            list($thisGame, $thisDiceCount, $thisDiceColour) = $thisGame;
            for ($x = 0; $x < count($thisGame); $x++) {
                if ($thisDiceCount[$x] > $maxPerColour[$thisDiceColour[$x]]) {
                    $maxPerColour[$thisDiceColour[$x]] = $thisDiceCount[$x];
                }
            }
        }
        $power = ($maxPerColour["red"] * $maxPerColour["green"] * $maxPerColour["blue"]); 
        echo $gameID . ": Red: " . $maxPerColour["red"] . " * Green: " . $maxPerColour["green"] . " * Blue: " . $maxPerColour["blue"] . " = $power\r\n\r\n";
        $sum += $power;
    }
    fclose($handle);
}
echo $sum;
exit();