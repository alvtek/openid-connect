<?php

declare(strict_types=1);


// Bitwise addition! Yay! (just don't expect to deal with ints bigger than 64 bits)

$x = '165665498518848488466587';
$y = '665456545459656456546545';

echo bcmul($y, $x);