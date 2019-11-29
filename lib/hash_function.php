<?php
print PHP_EOL . "<!-- BEGIN include hash-function -->" . PHP_EOL;

function hash ($password) {
    // Hash password
    $hashedPassword = "";
    $passwordLength = strlen($password);
    $passwordAsArray = str_split($password);
    
    for ($i=0; $i<$passwordLength; $i++) {
        $passwordAsArray[$i].replace("a","z").replace("b","y").replace("c","x").replace("d","w").replace("e","v").replace("f","u").replace("g","t").replace("h","s").replace("i","r").replace("j","q").replace("k","p").replace("l","o").replace("m","n").replace("n","m").replace("o","l").replace("p","k").replace("q","j").replace("r","i").replace("s","h").replace("t","g").replace("u","f").replace("v","e").replace("w","d").replace("x","c").replace("y","b").replace("z","a");
        $hashedPassword .= $passwordAsArray[$i];
    }
    
    return $hashedPassword;
    
}

print PHP_EOL . "<!-- BEGIN include hash-function -->" . PHP_EOL;
?>
