<?php
print PHP_EOL . "<!-- BEGIN include hash-function -->" . PHP_EOL;

function hashPassword ($password) {
    
    // Hash password
    $hashedPassword = "";
    $passwordLength = strlen($password);
    $passwordAsArray = str_split($password);    
    
    for ($i=0; $i<$passwordLength; $i++) {
        str_replace('a', 'z', $passwordAsArray[$i]);
        str_replace('b', 'y', $passwordAsArray[$i]);
        str_replace('c', 'x', $passwordAsArray[$i]);
        str_replace('d', 'w', $passwordAsArray[$i]);
        str_replace('e', 'v', $passwordAsArray[$i]);
        str_replace('f', 'u', $passwordAsArray[$i]);
        str_replace('g', 't', $passwordAsArray[$i]);
        str_replace('h', 's', $passwordAsArray[$i]);
        str_replace('i', 'r', $passwordAsArray[$i]);
        str_replace('j', 'q', $passwordAsArray[$i]);
        str_replace('k', 'p', $passwordAsArray[$i]);
        str_replace('l', 'o', $passwordAsArray[$i]);
        str_replace('m', 'n', $passwordAsArray[$i]);
        str_replace('n', 'm', $passwordAsArray[$i]);
        str_replace('o', 'l', $passwordAsArray[$i]);
        str_replace('p', 'k', $passwordAsArray[$i]);
        str_replace('q', 'j', $passwordAsArray[$i]);
        str_replace('r', 'i', $passwordAsArray[$i]);
        str_replace('s', 'h', $passwordAsArray[$i]);
        str_replace('t', 'g', $passwordAsArray[$i]);
        str_replace('u', 'f', $passwordAsArray[$i]);
        str_replace('v', 'e', $passwordAsArray[$i]);
        str_replace('w', 'd', $passwordAsArray[$i]);
        str_replace('x', 'c', $passwordAsArray[$i]);
        str_replace('y', 'b', $passwordAsArray[$i]);
        str_replace('z', 'a', $passwordAsArray[$i]);
        $hashedPassword .= $passwordAsArray[$i];
    }
    
    return $hashedPassword;
    
}

print PHP_EOL . "<!-- BEGIN include hash-function -->" . PHP_EOL;
?>
