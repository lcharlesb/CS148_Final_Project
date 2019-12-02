<?php 
include 'top.php';

print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;

$fnkUsername = htmlentities($_GET["username"], ENT_QUOTES, "UTF-8");

if($fnkUsername == "" || $fnkUsername == NULL) {
    header("Location: index.php");
}

$username = "";
$firstName = "";
$lastName = "";
$gender = "";
$preference = "";
$bio = "";
$interests = "";


$query  = "SELECT fnkUsername, fldBio, fldFirstName, fldLastName,fldPreference, fldGender ";
$query .= "FROM tblProfile ";
$query .= "WHERE fnkUsername = ?";
$username = array($fnkUsername);
if ($thisDatabaseReader->querySecurityOk($query, 1, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $profileData = $thisDatabaseReader->select($query, $username);
    
}

$query = "SELECT pmkInterest ";
$query .= "FROM tblInterests ";

if ($thisDatabaseReader->querySecurityOk($query, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $allInterests = $thisDatabaseReader->select($query, '');

}
$query = "SELECT fnkInterest ";
$query .= "FROM tblUsersInterests ";
$query .= "WHERE pfkUsername = ? ";

if ($thisDatabaseReader->querySecurityOk($query, 1, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $interestsToCheck = $thisDatabaseReader->select($query, $username);
    
}
$toCheckLength = count($interestsToCheck);
$numOfInter = count ($allInterests);

print PHP_EOL . '<!-- SECTION: 1c form error flags -->' . PHP_EOL;

$usernameERROR = "";
$firstNameERROR = "";
$lastNameERROR = "";
$genderERROR = "";
$preferenceERROR = "";
$bioERROR = "";


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
    $data[]= $firstName;
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $data[] =$lastName;
    $bio = htmlentities($_POST["txtBio"], ENT_QUOTES, "UTF-8");
    $data[] = $bio;
    $gender = htmlentities($_POST["listGender"], ENT_QUOTES, "UTF-8");
    $data [] =$gender;
    $preference = htmlentities($_POST["radioPreference"], ENT_QUOTES, "UTF-8");
    $data[] = $preference;
    
    if (!empty($fnkUsername)){
        $data[] = $fnkUsername;
    }
    for($a = 0; $a < $numOfInter; $a++){
        $interests = htmlentities($_POST["checkbox" . $a], ENT_QUOTES, "UTF-8");
        if (!empty($interests)){
            $interestArray[] = $fnkUsername;
            $count +=1; // count every time the username is added to the interestArray
            $interestArray[] = $interests;  
        } 
    }
    $numOfBoxesSelected = count($interestArray)-$count; // count the number of boxes to check but substract the number of times fnkUsername was added.


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
        $errorMsg[] = "Please enter a bio.";
        $bioERROR = true;
    }
    print PHP_EOL . '<!-- process for when form is passed -->' . PHP_EOL;
    
    if (!$errorMsg) {
        if (DEBUG) {
            print "<p>Form is valid</p>";
        }
        print PHP_EOL . '<!-- save data -->' . PHP_EOL;
        if(!empty($fnkUsername)){
            $query = 'UPDATE tblProfile ';
            $query .= 'SET fldFirstName = ?, ';
            $query .= 'fldLastName = ?, ';
            $query .= 'fldBio = ?, ';
            $query .= 'fldGender = ?, ';
            $query .= 'fldPreference = ? ';
            $query .= 'WHERE fnkUsername = ? ';
            if ($thisDatabaseWriter->querySecurityOk($query, 1, 0)) {
                $query = $thisDatabaseWriter->sanitizeQuery($query);
                $results = $thisDatabaseWriter->update($query, $data);
            }
            $query = 'DELETE FROM tblUsersInterests ';
            $query .= 'WHERE pfkUsername = ?';
            $delete = $thisDatabaseWriter->delete($query, $username);
            
            

            $query = 'INSERT INTO tblUsersInterests(pfkUsername, fnkInterest) ';
            $query .= 'VALUES ';
            for ($b = 0; $b < $numOfBoxesSelected-1; $b++){
                $query .= '(?, ? ),';
            }
            $query .= '(?, ? )';
            if ($thisDatabaseWriter->querySecurityOk($query, 0)) {
                $query = $thisDatabaseWriter->sanitizeQuery($query);
                $results = $thisDatabaseWriter->insert($query, $interestArray);
            }
            
        }
        $dataEntered = true;
        
    }


}
print PHP_EOL . '<!-- display form -->' . PHP_EOL;
    if ($dataEntered) {
        
        header("Location: profile.php?username=" . $fnkUsername);
        
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
    
        print PHP_EOL . '<!-- html form -->' . PHP_EOL;
        if(!empty($fnkUsername)){
            print '<h2 id="ProfileFormHeader"> Edit Profile </h2>';
        }
        else{
            print '<h2 id="ProfileFormHeader"> Profile Information </h2>';
        }
    ?>
    <form action ="<?php if (!empty($fnkUsername)) {print 'profile-form.php?username=' . $fnkUsername; } else if (empty($fnkUsername)) { print $phpSelf;}?>" method ="post" class="ProfileForm" id="frmProfile">
        <fieldset>
            <legend id="ProfileFormPadTop"><b> First Name: </b></legend>
                <input autofocus <?php if ($firstNameERROR){print' required class="mistake"';} ?> type= "text" name="txtFirstName" <?php if (!empty($fnkUsername)){print 'value="' . $profileData[0]['fldFirstName'] . '"';} else if (isset($_POST["txtFirstName"])) { print 'value="' . $firstName . '"';}?>>
        </fieldset>
        <fieldset>
            <legend><b> Last Name: </b></legend>
            <input <?php if ($lastNameERROR){print' required class="mistake"';} ?> type= "text" name="txtLastName" <?php if (!empty($fnkUsername)){print 'value="' . $profileData[0]['fldLastName'] . '"';} else if (isset($_POST["txtLastName"])) { print 'value="' . $lastName . '"';}?>>  
        </fieldset>
        <fieldset>
            <legend><b> Gender: </b></legend>
            <select name ="listGender" tabindex="300" class="ProfileFormMarginLeft" id="ProfileFormGender">
                <option value="Male" <?php if($_POST["listGender"]=="Male"|| $profileData[0]["fldGender"] == "Male"){print 'selected';}?>>Male </option>
                <option value="Female" <?php if($_POST["listGender"]=="Female"|| $profileData[0]["fldGender"] == "Female"){print 'selected';}?>> Female</option>
                <option value="Other" <?php if($_POST["listGender"]=="Other"|| $profileData[0]["fldGender"] == "Other"){print 'selected';}?>> Other </option> 
            </select>
        </fieldset>
        <fieldset id="ProfileFormRadioButtons">
            <legend><b> Interested in: </b></legend>
            <fieldset class="ProfileFormRadioButtonWithLabel ProfileFormMarginLeft">
                <input id = "radioButton4" type="radio" name="radioPreference" value="Male" <?php if($_POST["radioPreferece"]=="Male"|| $profileData[0]["fldPreference"] == "Male"){print 'checked';}?> checked>
                <label for= "radioButton4">Male</label>
            </fieldset>
            <fieldset class="ProfileFormRadioButtonWithLabel ProfileFormMarginLeft">
                <input id = "radioButton5" type="radio" name="radioPreference" value="Female" <?php if($_POST["radioPreferece"]=="Female"|| $profileData[0]["fldPreference"] == "Female"){print 'checked';}?>>
                <label for= "radioButton5">Female</label>
            </fieldset>
            <fieldset class="ProfileFormRadioButtonWithLabel ProfileFormMarginLeft">
                <input id = "radioButton6" type="radio" name="radioPreference" value="Other"<?php if($_POST["radioPreferece"]=="Other"|| $profileData[0]["fldPreference"] == "Other"){print 'checked';}?>>
                <label for= "radioButton6">Other</label>
            </fieldset>  
        </fieldset>
        <fieldset>
            <?php 
                print '<legend><b> Your hobbies include: </b></legend>';
                for ($i = 0; $i < $numOfInter; $i++){
                    print '<fieldset class="ProfileFormHobbies">';
                    print '<input type="checkbox" name="checkbox' . $i . '" value="' . $allInterests[$i][0] . '"';

                    if(!empty($fnkUsername)){
                        for ($z = 0; $z < $toCheckLength; $z++){
                        if (in_array($interestsToCheck[$z][0],$allInterests[$i], true)){
                            print ' checked' ;
                            }
                        }
                    }
                    else if (empty($fnkUsername)){
                        for ($z = 0; $z < $numOfInter; $z++){
                        if (in_array($interestArray[$z],$allInterests[$i], true)){
                            print ' checked' ;
                            }
                        }
                    }

                    print '>' . $allInterests[$i][0];
                    print '</fieldset>';
                }
            ?>
        </fieldset>
        <fieldset>
            <legend><b> Bio: </b></legend>
            <textarea <?php if ($bioERROR){print' required class="mistake"';} ?> name="txtBio" id="ProfileFormBio" rows="10" cols="40"><?php if (!empty($fnkUsername)){echo $profileData[0]['fldBio'];} else if (isset($_POST["txtBio"])) { echo $bio;}?></textarea>
        </fieldset>
        <fieldset>
            <button type="submit" id="btnSubmit" name="btnSubmit" value="Submit" tabindex="900" class="button">Submit</button>
        </fieldset>
    </form>
    <?php
    } 
    ?> 