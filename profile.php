<?php
include 'top.php';

// Print array if debug is set to true
if ($debug){ 
    print '<p>Post Array:</p><pre>';
    print_r($_POST);
    print '</pre>';

}

// Initialize variables for each form element
$username = "";
$firstName = "";
$lastName = "";
$gender = "";
$preference = "";
$bio = "";
$interests = "";
$profileArray="";

// Get values from tblProfile
$fnkUsername = (int) htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");

$records = '';

$profilequery = 'SELECT fldFirstName, fldLastName, fldGender, fldPreference, fldBio, ';
$profilequery .= ' FROM tblProfile WHERE fnkUsername = ?';

$interestquery = 'SELECT fldInterest FROM tblUsersInterests WHERE fnkUsername = ?'

//$profileArray= array();
?>
<html>    
<main>
    <h2>test</h2>
</main>
</html>