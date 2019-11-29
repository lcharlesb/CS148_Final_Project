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

$possibleUsernamesQuery = 'SELECT pfkUsername FROM tblUsers';

if ($thisDatabaseReader->querySecurityOk($possibleUsernamesQuery, 0)) {
    $possibleUsernamesQuery = $thisDatabaseReader->sanitizeQuery($possibleUsernamesQuery);
    $possibleUsernames = $thisDatabaseReader->select($possibleUsernamesQuery, '');
}

// Process for when form is submitted
if (isset($_POST["btnSubmit"])) {
    
    // Get values from form
    $username = htmlentities($_POST["fldUsername"], ENT_QUOTES, "UTF-8");      
    $password = htmlentities($_POST["fldPassword"], ENT_QUOTES, "UTF-8");  
    $password = hash($password);

    // Validate form elements. If invalid, throw error.
    $login_array_for_validation = array();
    if (is_array($possibleUsernames)) {
        foreach ($possibleUsernames as $record) {
            $login_array_for_validation[] = $possibleUsernames['pfkUsername'];
        }
    }
    
    if ($username == "") {
        $errorMsg[] = "Please enter a username.";
        $usernameERROR = true;
    } else if (!in_array($username, $login_array_for_validation)) {
        $errorMsg[] = "Invalid username. Please try again.";
        $usernameERROR = true;
    }
    
    // If no errors from username, check for errors for password.
    if ($usernameERROR == false) {
        
        $queriedPassword = '';
        $queriedPasswordDataRecord[] = array();
        $queriedPasswordDataRecord[] = $username;
        $queriedPasswordQuery = 'SELECT fldPassword, fldAdmin FROM tblUsers WHERE pfkUsername = ?';
        
        if ($thisDatabaseReader->querySecurityOk($queriedPasswordQuery, 0)) {
            $queriedPasswordQuery = $thisDatabaseReader->sanitizeQuery($queriedPasswordQuery);
            $queriedPassword = $thisDatabaseReader->select($queriedPasswordQuery, $queriedPasswordDataRecord);
        }
        
        if ($queriedPassword['fldPassword'] == '' || $queriedPassword['fldPassword'] == NULL) {
            $errorMsg[] = "Invalid password. Please try again.";
            $passwordERROR = true;
        }
        
        if ($passwordERROR == false) {
            if ($queriedPassword['fldAdmin'] == 1 || $queriedPassword['fldAdmin'] == '1') {
                $isAdmin = true;
            }
        }
        
    }
    
}

?>
<main>
    
    <article>
        
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
              method="post">
            
            <fieldset>
                <input type="text" name="fldUsername" placeholder="Username">
            </fieldset>
            <fieldset>
                <legend>Password</legend>
                <input type="text" name="fldPassword" placeholder="Password">
            </fieldset>
            <fieldset>
                <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Submit">
            </fieldset>
            <fieldset>
                <a href="signup.php">New here? Signup.</a>
            </fieldset>
        
        </form>
        
        <?php
            } // ends body submit
        ?>
        
    </article>
    
</main>

<?php include 'footer.php'; ?>

</body>
</html>