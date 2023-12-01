<?php

$numList = [
    "one" => 1, "two" => 2, "three" => 3, "four" => 4, "five" => 5, "six" => 6, "seven" => 7, "eight" => 8, "nine" => 9,
];
$sum = 0;
$handle = fopen("./input.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $first = $last = $line;

        // It's tricky because we have strings like 'oneight' and 'twone'
        // The first element in our $numList is the string 'one' - which sets our 'twone' as 'tw1'
        // But the actual result we want is '2ne' because the 'two' comes first.
        $firstNum = '';
        $firstVal = $firstPos = strlen($first) + 9;
        foreach($numList as $num => $val) {
            // Find the number from reverse, keep track of the 'first' number in the string
            $pos = strpos($first, $num);
            if ($pos !== false && $pos < $firstPos) {
                $firstPos = $pos;
                $firstVal = $val;
                $firstNum = $num;
            }
        }
        // If we have a string, then we want to replace that with the actual value
        if ($firstVal < strlen($first) + 9) {
            $first = substr_replace($first, $firstVal, $firstPos, strlen($firstNum));
        }
        // Then we filter out non digits
        $first = preg_replace('/[^0-9]/', '$1', $first);         
        // Then get our first digit
        $first = substr($first, 0, 1);


        // Then, the same again in reverse, to tackle difficult strings
        // oneight was parsed as 1ight but we need it to be on8
        $lastNum = '';
        $lastVal = $lastPos = -1;
        foreach($numList as $num => $val) {
            // Find the number from reverse, keep track of the 'last' number in the string
            $pos = strrpos($last, $num, -1);
            if ($pos !== false && $pos > $lastPos) {
                $lastPos = $pos;
                $lastVal = $val;
                $lastNum = $num;
            }
        }
        // If we have a string, then we want to replace that with the actual value
        if ($lastVal >= 0) {
            $last = substr_replace($last, $lastVal, $lastPos, strlen($lastNum));
        }
        // Then we filter out non digits
        $last = preg_replace('/[^0-9]/', '$1', $last);
        // Then get our last digit
        $last = strval(substr($last, -1));

        $sum += (int) ($first.$last);
    }
    fclose($handle);
}
echo $sum;
