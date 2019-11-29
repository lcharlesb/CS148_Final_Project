<?php
print PHP_EOL . "<!-- BEGIN include hash-function -->" . PHP_EOL;

function hashPassword ($password) {
    
    // Hash password
    $hashedPassword = "";
    $passwordLength = strlen($password);
    $passwordAsArray = str_split($password);
    
    foreach($passwordAsArray as $character) {
        $newChar = "";
        if ($character == "a") {
            $newChar = str_replace("a", "z", $character);
        } else if ($character == "b"){
            $newChar = str_replace('b', 'y', $character);
        } else if ($character == "c"){
            $newChar = str_replace('c', 'x', $character);
        } else if ($character == "d"){
            $newChar = str_replace('d', 'w', $character);
        } else if ($character == "e"){
            $newChar = str_replace('e', 'v', $character);
        } else if ($character == "f"){
            $newChar = str_replace('f', 'u', $character);
        } else if ($character == "g"){
            $newChar = str_replace('g', 't', $character);
        } else if ($character == "h"){
            $newChar = str_replace('h', 's', $character);
        } else if ($character == "i"){
            $newChar = str_replace('i', 'r', $character);
        } else if ($character == "j"){
            $newChar = str_replace('j', 'q', $character);
        } else if ($character == "k"){
            $newChar = str_replace('k', 'p', $character);
        } else if ($character == "l"){
            $newChar = str_replace('l', 'o', $character);
        } else if ($character == "m"){
            $newChar = str_replace('m', 'n', $character);
        } else if ($character == "n"){
            $newChar = str_replace('n', 'm', $character);
        } else if ($character == "o"){
            $newChar = str_replace('o', 'l', $character);
        } else if ($character == "p"){
            $newChar = str_replace('p', 'k', $character);
        } else if ($character == "q"){
            $newChar = str_replace('q', 'j', $character);
        } else if ($character == "r"){
            $newChar = str_replace('r', 'i', $character);
        } else if ($character == "s"){
            $newChar = str_replace('s', 'h', $character);
        } else if ($character == "t"){
            $newChar = str_replace('t', 'g', $character);
        } else if ($character == "u"){
            $newChar = str_replace('u', 'f', $character);
        } else if ($character == "v"){
            $newChar = str_replace('v', 'e', $character);
        } else if ($character == "w"){
            $newChar = str_replace('w', 'd', $character);
        } else if ($character == "x"){
            $newChar = str_replace('x', 'c', $character);
        } else if ($character == "y"){
            $newChar = str_replace('y', 'b', $character);
        } else if ($character == "z"){
            $newChar = str_replace('z', 'a', $character);
        } else if ($character == "A") {
            $newChar = str_replace("A", "Z", $character);
        } else if ($character == "B"){
            $newChar = str_replace('B', 'Y', $character);
        } else if ($character == "C"){
            $newChar = str_replace('C', 'X', $character);
        } else if ($character == "D"){
            $newChar = str_replace('D', 'W', $character);
        } else if ($character == "E"){
            $newChar = str_replace('E', 'V', $character);
        } else if ($character == "F"){
            $newChar = str_replace('F', 'U', $character);
        } else if ($character == "G"){
            $newChar = str_replace('G', 'T', $character);
        } else if ($character == "H"){
            $newChar = str_replace('H', 'S', $character);
        } else if ($character == "I"){
            $newChar = str_replace('I', 'R', $character);
        } else if ($character == "J"){
            $newChar = str_replace('J', 'Q', $character);
        } else if ($character == "K"){
            $newChar = str_replace('K', 'P', $character);
        } else if ($character == "L"){
            $newChar = str_replace('L', 'O', $character);
        } else if ($character == "M"){
            $newChar = str_replace('M', 'N', $character);
        } else if ($character == "N"){
            $newChar = str_replace('N', 'M', $character);
        } else if ($character == "O"){
            $newChar = str_replace('O', 'L', $character);
        } else if ($character == "P"){
            $newChar = str_replace('P', 'K', $character);
        } else if ($character == "Q"){
            $newChar = str_replace('Q', 'J', $character);
        } else if ($character == "R"){
            $newChar = str_replace('R', 'I', $character);
        } else if ($character == "S"){
            $newChar = str_replace('S', 'H', $character);
        } else if ($character == "T"){
            $newChar = str_replace('T', 'G', $character);
        } else if ($character == "U"){
            $newChar = str_replace('U', 'F', $character);
        } else if ($character == "V"){
            $newChar = str_replace('V', 'E', $character);
        } else if ($character == "W"){
            $newChar = str_replace('W', 'D', $character);
        } else if ($character == "X"){
            $newChar = str_replace('X', 'C', $character);
        } else if ($character == "Y"){
            $newChar = str_replace('Y', 'B', $character);
        } else if ($character == "Z"){
            $newChar = str_replace('Z', 'A', $character);
        } else {
            $newChar = $character;
        }
        
        $hashedPassword .= $newChar;
        
    }
    
    return $hashedPassword;
    
}

print PHP_EOL . "<!-- BEGIN include hash-function -->" . PHP_EOL;
?>
