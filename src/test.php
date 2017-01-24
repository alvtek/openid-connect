<?php

declare(strict_types=1);


// Bitwise addition! Yay! (just don't expect to deal with ints bigger than 64 bits)

function add(int $a, int $b)
{
    $and = $a & $b;
    $xor = $a ^ $b;
    
    if ($and > 0) {
        $and = $and << 1;
        if ($xor > 0) {
            return add($and, $xor);
        }
    }

    $total = $and | $xor;
    
    if ($total < $a || $total < $b) {
        throw new ErrorException("Reached system integer limit!");
    }
    
    return $total;
}

var_dump(add((int)$argv[1], (int)$argv[2]));