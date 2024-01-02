<?php
echo "<pre>";

$nodes = [];
$handle = fopen("./input.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // Parse our file first!
        if (!isset($instructions)) {
            preg_match('/([LR]+)/', $line, $instructions);
        } elseif (trim($line) != "") {
            preg_match('/(\w{3}) = \((\w{3}), (\w{3})\)/', $line, $nodeArray);
            $nodes[$nodeArray[1]] = [
                "L" => $nodeArray[2],
                "R" => $nodeArray[3],
            ];
        }
    }
    fclose($handle);
}

// initial trackers
$steps = $nextInstruction = 0;
$atZZZ = false;
// We always start at AAA
$step = "AAA";
// Split our string of LLR to an array of ['L', 'L', 'R']
$instructions = str_split($instructions[0]);

while (!$atZZZ) {
    // Increment our steps counts
    $steps++;
    
    // Determine the direction we are going
    $dir = $instructions[$nextInstruction];
    // Get the next step in that direction
    $step = $nodes[$step][$dir];
    
    // And if it is ZZZ then we are done
    if ($step == "ZZZ") {
        $atZZZ = true;
    }

    // If we are not at ZZZ then we need our next instruction
    $nextInstruction++;
    // And if we have ran out of instructions, then start at the beginning of our list
    if ($nextInstruction >= count($instructions)) {
        $nextInstruction = 0;
    }
}

// How many steps did it take us?
echo $steps; 
exit();
