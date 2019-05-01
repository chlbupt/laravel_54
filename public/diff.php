<?php

$a = [1,2,3];
$b = [2,3,4];
echo 'a[1,2,3] diff b[2,3,4]'. implode('|', array_diff($a, $b));
echo '<br>';
echo 'b[2,3,4] diff a[1,2,3]'. implode('|', array_diff($b, $a));