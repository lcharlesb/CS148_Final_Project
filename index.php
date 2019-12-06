<?php

include 'top.php';

// Initialize variables for each form element
$username = "";
$password = "";

// Initialize error flags for each form element, used for validation
$usernameERROR = false;
$passwordERROR = false;

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
    $password = hashPassword($password);

    // Validate form elements. If invalid, throw error.
    $login_array_for_validation = array();
    if (is_array($possibleUsernames)) {
        foreach ($possibleUsernames as $record) {
            $login_array_for_validation[] = $record[0];
        }
    }
    
    if ($username == "") {
        $errorMsg[] = "Please enter a username (email).";
        $usernameERROR = true;
    } else if (!in_array($username, $login_array_for_validation)) {
        $errorMsg[] = "Invalid username (email). Please try again.";
        $usernameERROR = true;
    } else if (!verifyEmail($username)) {
        $errorMsg[] = "Given username is not a valid email. Please try again.";
        $usernameERROR = true;
    }
    
    // If no errors from username, check for errors for password.
    if ($usernameERROR == false) {
        
        $queriedPassword = '';
        $queriedPasswordDataRecord = array();
        $queriedPasswordDataRecord[] = $username;
        $queriedPasswordQuery = 'SELECT fldPassword, fldAdmin FROM tblUsers WHERE pmkUsername = ?';
        
        if ($thisDatabaseReader->querySecurityOk($queriedPasswordQuery, 1, 0)) {
            $queriedPasswordQuery = $thisDatabaseReader->sanitizeQuery($queriedPasswordQuery);
            $queriedPassword = $thisDatabaseReader->select($queriedPasswordQuery, $queriedPasswordDataRecord);
        }
        
        $passwordToCheck = $queriedPassword[0]['fldPassword'];
        
        if ($password == "") {
            $errorMsg[] = "Please enter a password.";
            $passwordERROR = true;
        } else if ($passwordToCheck != $password) {
            $errorMsg[] = "Invalid password. Please try again.";
            $passwordERROR = true;
        }
        
    }
    
}

?>
<main>
    
        
        <?php
            // Action after submitting
            if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit

                // Go to next page
                header("Location: selection.php?username=" . $username);

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
        
        <form action="<?php print $phpSelf; ?>"
              id="frmRegister"
              class="LogIn"
              method="post">
            
            <fieldset>
                <legend>Log In</legend>
                <input <?php if ($usernameERROR){print' required class="mistake"';} ?>autofocus type="text" name="fldUsername" placeholder="Username" value="<?php echo $username ?>">
                <br>
                <input <?php if ($passwordERROR){print' required class="mistake"';} ?>type="password" name="fldPassword" placeholder="Password">
                <br>
                <input class="button" name="btnSubmit" tabindex="900" type="submit" value="Submit">
                <br>
                <a href="signup.php">New here? Signup.</a>
            </fieldset>
        
        </form>
        
        <?php
            } // ends body submit
        ?>
        
    
</main>

</body>
</html>