<?php

function noIterate($strArr)
{
    [$N, $K] = $strArr;

    $kFreq = [];
    for ($i = 0; $i < strlen($K); $i++) {
        $char = $K[$i];
        $kFreq[$char] = ($kFreq[$char] ?? 0) + 1;
    }

    $required = count($kFreq);
    $formed = 0;
    $windowCounts = [];

    $left = 0;
    $minLen = PHP_INT_MAX;
    $minWindow = "";

    for ($right = 0; $right < strlen($N); $right++) {
        $char = $N[$right];
        $windowCounts[$char] = ($windowCounts[$char] ?? 0) + 1;

        if (isset($kFreq[$char]) && $windowCounts[$char] === $kFreq[$char]) {
            $formed++;
        }

        while ($left <= $right && $formed === $required) {
            if (($right - $left + 1) < $minLen) {
                $minLen = $right - $left + 1;
                $minWindow = substr($N, $left, $minLen);
            }

            $leftChar = $N[$left];
            $windowCounts[$leftChar]--;
            if (isset($kFreq[$leftChar]) && $windowCounts[$leftChar] < $kFreq[$leftChar]) {
                $formed--;
            }

            $left++;
        }
    }

    return $minWindow;
}

// keep this function call here
echo noIterate(["ahffaksfajeeubsne", "jefaa"]);
