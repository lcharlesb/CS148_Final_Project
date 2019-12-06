<?php

include 'top.php';

// Initialize variables for each form element
$username = "";
$password = "";
$passwordConfirm = "";

// Initialize error flags for each form element, used for validation
$usernameERROR = false;
$passwordERROR = false;
$passwordConfirmERROR = false;

$errorMsg = array();

// Select possible usernames
$possibleUsernames = '';

$possibleUsernamesQuery = 'SELECT pmkUsername FROM tblUsers';

if ($thisDatabaseReader->querySecurityOk($possibleUsernamesQuery, 0)) {
    $possibleUsernamesQuery = $thisDatabaseReader->sanitizeQuery($possibleUsernamesQuery);
    $possibleUsernames = $thisDatabaseReader->select($possibleUsernamesQuery, '');
}

// Process for when form is submitted
if (isset($_POST["btnSubmit"])) {
    
    // Get values from form
    $username = htmlentities($_POST["fldUsername"], ENT_QUOTES, "UTF-8");      
    $password = htmlentities($_POST["fldPassword"], ENT_QUOTES, "UTF-8");  
    $passwordConfirm = htmlentities($_POST["fldPasswordConfirm"], ENT_QUOTES, "UTF-8");
    $password = hashPassword($password);
    $passwordConfirm = hashPassword($passwordConfirm);
    
    // Validate form elements. If invalid, throw error.
    $login_array_for_validation = array();
    if (is_array($possibleUsernames)) {
        foreach ($possibleUsernames as $record) {
            $login_array_for_validation[] = $record[0];
        }
    }
    
    if ($username == "") {
        $errorMsg[] = "Please enter a username.";
        $usernameERROR = true;
    } else if (in_array($username, $login_array_for_validation)) {
        $errorMsg[] = "Username already in use. Please try again.";
        $usernameERROR = true;
    } else if (!verifyEmail($username)) {
        $errorMsg[] = "Given username is not a valid email. Please try again.";
        $usernameERROR = true;
    }
    
    // If no errors from username, check for errors for password.
    if ($usernameERROR == false) {
        
        if ($password == "") {
            $errorMsg[] = "Please enter a password.";
            $passwordERROR = true;
        } else if ($password != $passwordConfirm) {
            $errorMsg[] = "Passwords do not match. Please try again.";
            $passwordERROR = true;
            $passwordConfirmERROR = true;
        }
        
    }
    
}

?>
<main>
        <?php
        
            // Action after submitting
            if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
                
                // Insert user into tblUsers
                $signupUsersInsertQuery = "INSERT INTO tblUsers (pmkUsername, fldPassword) VALUES (?, ?)";
                $signupUsersInsertResults = "";
                $signupUsersInsertDataRecord = array();
                $signupUsersInsertDataRecord[] = $username;
                $signupUsersInsertDataRecord[] = $password;
                
                if ($thisDatabaseWriter->querySecurityOk($signupUsersInsertQuery, 0)) {
                    $signupUsersInsertQuery = $thisDatabaseWriter->sanitizeQuery($signupUsersInsertQuery);
                    $signupUsersInsertResults = $thisDatabaseWriter->insert($signupUsersInsertQuery, $signupUsersInsertDataRecord);
                }
                
                // Insert user into tblProfile
                $signupProfileInsertQuery = "INSERT INTO tblProfile (fnkUsername) VALUES (?)";
                $signupProfileInsertResults = "";
                $signupProfileInsertDataRecord = array();
                $signupProfileInsertDataRecord[] = $username;
                
                if ($thisDatabaseWriter->querySecurityOk($signupProfileInsertQuery, 0)) {
                    $signupProfileInsertQuery = $thisDatabaseWriter->sanitizeQuery($signupProfileInsertQuery);
                    $signupProfileInsertResults = $thisDatabaseWriter->insert($signupProfileInsertQuery, $signupProfileInsertDataRecord);
                }
                
                // Go to next page
                header("Location: profile-form.php?username=" . $username);

            } else { 
                
                // Display errors in form elements.
                if ($errorMsg) {    
                    print '<div id="errors">' . PHP_EOL;
                    print '<ul>' . PHP_EOL;

                    foreach ($errorMsg as $err) {
                        print '<li>' . $err . '</li>' . PHP_EOL;       
                    }

                     print '</ul>' . PHP_EOL;
                     print '</div>' . PHP_EOL;
                }
        
        ?>
         
        <form action="<?php print PHP_SELF; ?>"
              id="frmRegister"
              class="LogIn"
              method="post">
            
            <fieldset>
                <legend>Create an Account</legend>
                <input <?php if ($usernameERROR){print' required class="mistake"';} ?>autofocus type="text" name="fldUsername" placeholder="Enter username." value="<?php echo $username ?>">
                <br>
                <input <?php if ($passwordERROR){print' required class="mistake"';} ?>type="password" name="fldPassword" placeholder="Enter password.">
                <br>
                <input <?php if ($passwordConfirmERROR){print' required class="mistake"';} ?>type="password" name="fldPasswordConfirm" placeholder="Enter password again.">
                <br>
                <input class="button" name="btnSubmit" tabindex="900" type="submit" value="Submit">
                <br>
                <a href="index.php">Have an account? Login here.</a>
            </fieldset>
        
        </form>
        
        <?php
            } // ends body submit
        ?>

</main>

</body>
</html>

