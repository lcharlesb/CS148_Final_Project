<?php 

print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;

$username = "";
$firstName = "";
$lastName = "";
$gender = "";
$preference = "";
$bio = "";
$interests = "";

print PHP_EOL . '<!-- SECTION: 1c form error flags -->' . PHP_EOL;

$usernameERROR = "";
$firstNameERROR = "";
$lastNameERROR = "";
$genderERROR = "";
$preferenceERROR = "";
$bioERROR = "";
$interestsERROR = "";

print PHP_EOL . '<!-- SECTION: 1d misc variables -->' . PHP_EOL;

$errorMsg = array();
$dataEntered = false;