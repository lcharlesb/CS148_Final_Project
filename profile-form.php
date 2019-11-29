<?php 

print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;

$fnkUsername = (int) htmlentities($_GET["username"], ENT_QUOTES, "UTF-8");
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

print PHP_EOL . '<!-- process after user hits submit -->' . PHP_EOL;

if (isset($_POST['btnSubmit'])) {
    print PHP_EOL . '<!-- Security -->' . PHP_EOL;
    
    $thisURL = DOMAIN . PHP_SELF;
    
    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page.</p>';
        $msg.= '<p>Security breach detected and reported.</p>';
        die($msg);
    }
    print PHP_EOL . '<!-- sanitize data -->' . PHP_EOL;

    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $bio = htmlentities($_POST["txtBio"], ENT_QUOTES, "UTF-8");
    $preference = htmlentities($_POST["radioPreference"], ENT_QUOTES, "UTF-8");

    print PHP_EOL . '<!-- validation -->' . PHP_EOL;

    if(empty($username)){
        $errorMsg[] = "Please enter your username.";
        $usernameERROR = true;
    }
    if(empty($firstName)){
        $errorMsg[] = "Please enter your first name.";
        $firstNameERROR = true;
    }
    if(empty($lastName)){
        $errorMsg[] = "Please enter your last name.";
        $lastNameERROR = true;
    }
    if(empty($bio)){
        $errorMsg[] = "Please enter your preference.";
        $bioERROR = true;
    }
    print PHP_EOL . '<!-- process for when form is passed -->' . PHP_EOL;
    
    if (!$errorMsg) {
        if (DEBUG) {
            print "<p>Form is valid</p>";
        }
        print PHP_EOL . '<!-- save data -->' . PHP_EOL;
    }


}
print PHP_EOL . '<!-- display form -->' . PHP_EOL;
    if ($dataEntered) {

    }else {

        print PHP_EOL . '<!-- display error messages. -->' . PHP_EOL;
        
        if ($errorMsg) {    
           print '<div id="errors">' . PHP_EOL;
           print '<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
           print '<ol>' . PHP_EOL;
           foreach ($errorMsg as $err) {
               print '<li>' . $err . '</li>' . PHP_EOL;       
           }
            print '</ol>' . PHP_EOL;
            print '</div>' . PHP_EOL;
       }
    }
        print PHP_EOL . '<!-- html form -->' . PHP_EOL;
    if(!empty($fnkUsername)){
        print '<h2> Edit Profile </h2>';
    }
    else{
        print '<h2> Profile Information </h2>';
    }
    ?>
    <form action ="<?php if (!empty($fnkUsername)) {print 'profile-form.php?username=' . $fnkUsername; } else if (empty($fnkUsername)) { print PHP_SELF;}?>" method ="post" id="">
    <fieldset class="">
        <label> First Name </label><br> 
            <input autofocus <?php if ($firstNameERROR){print' required class="mistake"';} ?> type= "text" name="txtFirstName" <?php if (!empty($fnkUsername)){print 'value="' . $profileData[0]['fldFirstName'] . '"';} else if (isset($_POST["txtFirstName"])) { print 'value="' . $firstName . '"';}?>><br>
        <label> Last Name </label><br> 
            <input <?php if ($lastNameERROR){print' required class="mistake"';} ?> type= "text" name="txtFirstName" <?php if (!empty($fnkUsername)){print 'value="' . $profileData[0]['fldLastName'] . '"';} else if (isset($_POST["txtLastName"])) { print 'value="' . $lastName . '"';}?>><br>
        <label> Bio </label><br> 
            <input <?php if ($bioERROR){print' required class="mistake"';} ?> type= "text" name="txtFirstName" <?php if (!empty($fnkUsername)){print 'value="' . $profileData[0]['fldBio'] . '"';} else if (isset($_POST["txtBio"])) { print 'value="' . $bio . '"';}?>><br>
       <?php 
       for ($i = 0; $i < len($preference); $i++ ) // for loop to get each trail name after the first index.
       {
        print '<input id  = "radioButton'.$i.'" type="radio" name="radioPreference" value="'. $profileData[$i]['fldPreference'] . '"';
        if ($profileData[0]["fldPreference"] == $profileData[$i]["fldPreference"]){
            print ' checked';
        }
        if ($profileData[$i]["fldPreference"] == $preference) {
            print ' checked';
        }
        
        print '>';
        print '<label for="radioButton'.$i.'"> '. $trailName[$i]['fldPreference'] . '</label><br>';
       }
       ?>