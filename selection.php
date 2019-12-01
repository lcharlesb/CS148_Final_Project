<?php

include 'top.php';

// Initialize variables for each form element
$currUsername = "";

$currentSelectionUserNumber = 0;

$selectionUsername = "";
$selectionFirstName = "";
$selectionLastName = "";
$selectionGender = "";
$selectionPreference = "";
$selectionBio = "";
$selectionInterests = array();

// Get username from URL
$currUsername = htmlentities($_GET["username"], ENT_QUOTES, "UTF-8");

// Get a list of all users
$usersQuery = "SELECT pfkUsername FROM tblUsers";
$usersArrayFromQuery = "";
$users = array();

if ($thisDatabaseReader->querySecurityOk($usersQuery, 0, 0)) {
    $usersQuery = $thisDatabaseReader->sanitizeQuery($usersQuery);
    $usersArrayFromQuery = $thisDatabaseReader->select($usersQuery, '');
}

foreach($usersArrayFromQuery as $user) {
    $users[] = $user[0];
}

// Get preference and gender of current user
$preferenceQuery = "SELECT fldPreference, fldGender FROM tblProfile WHERE fnkUsername = ?";
$currInterest = "";
$currGender = "";
$preferenceDataRecord = array();
$preferenceDataRecord[] = $currUsername;
$preferenceArrayFromQuery = "";

if ($thisDatabaseReader->querySecurityOk($preferenceQuery, 1, 0)) {
    $preferenceQuery = $thisDatabaseReader->sanitizeQuery($preferenceQuery);
    $preferenceArrayFromQuery = $thisDatabaseReader->select($preferenceQuery, $preferenceDataRecord);
}

$currInterest = $preferenceArrayFromQuery[0]['fldPreference'];
$currGender = $preferenceArrayFromQuery[0]['fldGender'];

// Get list of usernames who have a gender that matches currInterest
$selectionUsernamesQuery = "SELECT fnkUsername FROM tblProfile WHERE fldGender = ? AND fldPreference = ? AND fnkUsername != ?";
$selectionUsernames = array();
$selectionUsernamesDataRecord = array();
$selectionUsernamesDataRecord[] = $currInterest;
$selectionUsernamesDataRecord[] = $currGender;
$selectionUsernamesDataRecord[] = $currUsername;
$selectionUsernamesArrayFromQuery = "";

if ($thisDatabaseReader->querySecurityOk($selectionUsernamesQuery, 1, 3)) {
    $selectionUsernamesQuery = $thisDatabaseReader->sanitizeQuery($selectionUsernamesQuery);
    $selectionUsernamesArrayFromQuery = $thisDatabaseReader->select($selectionUsernamesQuery, $selectionUsernamesDataRecord);
}

foreach($selectionUsernamesArrayFromQuery as $selectionUser) {
    $selectionUsernames[] = $selectionUser[0];
}

// Get info on first $selectionUsernames user
$firstSelectionUserQuery = "SELECT fldFirstName, fldLastName, fldGender, fldPreference, fldBio FROM tblProfile WHERE fnkUsername = ?";
$firstSelectionUserQueryResults = "";
$firstSelectionUserDataRecord = array();
$firstSelectionUserDataRecord[] = $selectionUsernames[$currentSelectionUserNumber];

if ($thisDatabaseReader->querySecurityOk($firstSelectionUserQuery, 1, 0)) {
    $firstSelectionUserQuery = $thisDatabaseReader->sanitizeQuery($firstSelectionUserQuery);
    $firstSelectionUserQueryResults = $thisDatabaseReader->select($firstSelectionUserQuery, $firstSelectionUserDataRecord);
}

$selectionUsername = $selectionUsernames[$currentSelectionUserNumber];
$selectionFirstName = $firstSelectionUserQueryResults[0]["fldFirstName"];
$selectionLastName = $firstSelectionUserQueryResults[0]["fldLastName"];
$selectionGender = $firstSelectionUserQueryResults[0]["fldGender"];
$selectionPreference = $firstSelectionUserQueryResults[0]["fldPreference"];
$selectionBio = $firstSelectionUserQueryResults[0]["fldBio"];

// Get selectionInterests based on username
$selectionInterestsQuery = "SELECT fnkInterest FROM tblUsersInterests WHERE pfkUsername = ?";
$selectionInterestsQueryResults = "";
$selectionInterestsDataRecord = array();
$selectionInterestsDataRecord[] = $selectionUsername;

if ($thisDatabaseReader->querySecurityOk($selectionInterestsQuery, 1, 0)) {
    $selectionInterestsQuery = $thisDatabaseReader->sanitizeQuery($selectionInterestsQuery);
    $selectionInterestsQueryResults = $thisDatabaseReader->select($selectionInterestsQuery, $selectionInterestsDataRecord);
}

foreach($selectionInterestsQueryResults as $interest) {
    $selectionInterests[] = $interest[0];
}

// Increment $currentSelectionUserNumber
$currentSelectionUserNumber++;

// Process for when "Yes" button is pressed
if (isset($_POST["btnYes"])) {
    
    // Enter values into tblUsersMatches
    $yesAMatchQuery = "INSERT INTO tblUserMatches (pfkUsername, fnkOtherUsername, fldMatch) VALUES (?, ?, ?)";
    $yesAMatchQueryResults = array();
    $yesAMatchQueryDataRecord = array();
    $yesAMatchQueryDataRecord[] = $currUsername;
    $yesAMatchQueryDataRecord[] = $selectionUsername;
    $yesAMatchQueryDataRecord[] = '0';
    
    if ($thisDatabaseWriter->querySecurityOk($yesAMatchQuery, 0)) {
        $yesAMatchQuery = $thisDatabaseWriter->sanitizeQuery($yesAMatchQuery);
        $yesAMatchQueryResults = $thisDatabaseWriter->insert($yesAMatchQuery, $yesAMatchQueryDataRecord);
    }
    
    // Get info on next $selectionUsernames user
    $nextSelectionUserQuery = "SELECT fldFirstName, fldLastName, fldGender, fldPreference, fldBio FROM tblProfile WHERE fnkUsername = ?";
    $nextSelectionUserQueryResults = "";
    $nextSelectionUserDataRecord = array();
    $nextSelectionUserDataRecord[] = $selectionUsernames[$currentSelectionUserNumber];

    if ($thisDatabaseReader->querySecurityOk($nextSelectionUserQuery, 1, 0)) {
        $nextSelectionUserQuery = $thisDatabaseReader->sanitizeQuery($nextSelectionUserQuery);
        $nextSelectionUserQueryResults = $thisDatabaseReader->select($nextSelectionUserQuery, $nextSelectionUserDataRecord);
    }

    $selectionUsername = $selectionUsernames[$currentSelectionUserNumber];
    $selectionFirstName = $nextSelectionUserQueryResults[0]["fldFirstName"];
    $selectionLastName = $nextSelectionUserQueryResults[0]["fldLastName"];
    $selectionGender = $nextSelectionUserQueryResults[0]["fldGender"];
    $selectionPreference = $nextSelectionUserQueryResults[0]["fldPreference"];
    $selectionBio = $nextSelectionUserQueryResults[0]["fldBio"];

    // Get selectionInterests based on username
    $selectionInterestsQuery = "SELECT fnkInterest FROM tblUsersInterests WHERE pfkUsername = ?";
    $selectionInterestsQueryResults = "";
    $selectionInterestsDataRecord = array();
    $selectionInterestsDataRecord[] = $selectionUsername;

    if ($thisDatabaseReader->querySecurityOk($selectionInterestsQuery, 1, 0)) {
        $selectionInterestsQuery = $thisDatabaseReader->sanitizeQuery($selectionInterestsQuery);
        $selectionInterestsQueryResults = $thisDatabaseReader->select($selectionInterestsQuery, $selectionInterestsDataRecord);
    }

    foreach($selectionInterestsQueryResults as $interest) {
        $selectionInterests[] = $interest[0];
    }

    // Increment $currentSelectionUserNumber
    $currentSelectionUserNumber++;
    
}

// Process for when "No" button is pressed
if (isset($_POST["btnNo"])) {
    
    // Enter values into tblUsersMatches
    $notAMatchQuery = "INSERT INTO tblUserMatches (pfkUsername, fnkOtherUsername, fldMatch) VALUES (?, ?, ?)";
    $notAMatchQueryResults = array();
    $notAMatchQueryDataRecord = array();
    $notAMatchQueryDataRecord[] = $currUsername;
    $notAMatchQueryDataRecord[] = $selectionUsername;
    $notAMatchQueryDataRecord[] = '0';
    
    if ($thisDatabaseWriter->querySecurityOk($notAMatchQuery, 0)) {
        $notAMatchQuery = $thisDatabaseWriter->sanitizeQuery($notAMatchQuery);
        $notAMatchQueryResults = $thisDatabaseWriter->insert($notAMatchQuery, $notAMatchQueryDataRecord);
    }
    
    // Get info on next $selectionUsernames user
    $nextSelectionUserQuery = "SELECT fldFirstName, fldLastName, fldGender, fldPreference, fldBio FROM tblProfile WHERE fnkUsername = ?";
    $nextSelectionUserQueryResults = "";
    $nextSelectionUserDataRecord = array();
    $nextSelectionUserDataRecord[] = $selectionUsernames[$currentSelectionUserNumber];

    if ($thisDatabaseReader->querySecurityOk($nextSelectionUserQuery, 1, 0)) {
        $nextSelectionUserQuery = $thisDatabaseReader->sanitizeQuery($nextSelectionUserQuery);
        $nextSelectionUserQueryResults = $thisDatabaseReader->select($nextSelectionUserQuery, $nextSelectionUserDataRecord);
    }

    $selectionUsername = $selectionUsernames[$currentSelectionUserNumber];
    $selectionFirstName = $nextSelectionUserQueryResults[0]["fldFirstName"];
    $selectionLastName = $nextSelectionUserQueryResults[0]["fldLastName"];
    $selectionGender = $nextSelectionUserQueryResults[0]["fldGender"];
    $selectionPreference = $nextSelectionUserQueryResults[0]["fldPreference"];
    $selectionBio = $nextSelectionUserQueryResults[0]["fldBio"];

    // Get selectionInterests based on username
    $selectionInterestsQuery = "SELECT fnkInterest FROM tblUsersInterests WHERE pfkUsername = ?";
    $selectionInterestsQueryResults = "";
    $selectionInterestsDataRecord = array();
    $selectionInterestsDataRecord[] = $selectionUsername;

    if ($thisDatabaseReader->querySecurityOk($selectionInterestsQuery, 1, 0)) {
        $selectionInterestsQuery = $thisDatabaseReader->sanitizeQuery($selectionInterestsQuery);
        $selectionInterestsQueryResults = $thisDatabaseReader->select($selectionInterestsQuery, $selectionInterestsDataRecord);
    }

    foreach($selectionInterestsQueryResults as $interest) {
        $selectionInterests[] = $interest[0];
    }

    // Increment $currentSelectionUserNumber
    $currentSelectionUserNumber++;
    
}

?>
<main>
    
    <article class="Profile">
        
        <?php
            // Display form if $selectionUsername is not null
            if ($selectionUsername != "") {
                
        ?>
        
        <h2 id="profileName"><?php if($selectionFirstName != "") {echo $selectionFirstName . " ";} if($selectionLastName != "") {echo $selectionLastName;}?></h2>
        <?php if ($selectionGender != "") {echo "<h4>Gender: </h4><p>" . $selectionGender . "</p><BR>"; } ?>
        <?php if ($selectionPreference != "") {echo "<h4>Interested in: </h4><p>" . $selectionPreference . "</p><BR>"; } ?>
        <?php if (!empty($selectionInterests)) {echo "<h4>Their hobbies include: </h4>"; } ?>
        <?php
            if(!empty($selectionInterests)) {
                echo "<ul>";
                foreach($selectionInterests as $interest) {
                    echo "<li>" . $interest . "</li>";
                }
                echo "</ul>";
            }
        ?>
        <p id="profileBio"><?php echo $selectionBio; ?></p>
        <form action="" method="post">
            <button class="selectionButtons" id="btnYes" name="btnYes">Yes</button>
        <button class="selectionButtons" id="btnNo" name="btnNo">No</button>
        </form>
        
        <?php
        
            } else {
                
                print("<h2>There are no more available matches.</h2>");
                
            }
            
        ?>
        
    </article>
    
</main>