<?php
$a = 0;
$b = &$a;
$a = 5;
//expected 5 + 5
$c = $a + $b;
?>
----------------------------
{"type":"integer","value":10}
