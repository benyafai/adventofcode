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

// We need to find all of our paths that end with A
$paths = [];
foreach ($nodes as $start => $directions) {
    if (substr($start, -1) == "A") {
        $paths[] = $start;
    }
}

// initial trackers
$steps = $nextInstruction = 0;
$atZZZ = false;
// Split our string of LLR to an array of ['L', 'L', 'R']
$instructions = str_split($instructions[0]);

while (!$atZZZ) {
    // Increment our steps counts
    $steps++;
    
    // Determine the direction we are going
    $dir = $instructions[$nextInstruction];

    $zCount = 0;
    // Get the next step in that direction
    foreach ($paths as $id => $step) {
        $paths[$id] = $nodes[$step][$dir];
        if (str_ends_with($paths[$id], "Z")) {
            $zCount++;
        } 
    }
    
    // And if all paths end with Z then we are done
    if ($zCount == count($paths)) {
        $atZZZ = true;
    }

    // If we are not all at ..Z then we need our next instruction
    $nextInstruction++;
    // And if we have ran out of instructions, then start at the beginning of our list
    if ($nextInstruction >= count($instructions)) {
        $nextInstruction = 0;
    }
}

// How many steps did it take us?
echo $steps; 
exit();

