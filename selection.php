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

if($currUsername == "" || $currUsername == NULL) {
    header("Location: index.php");
}

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

// Get list of users that the currUser has already made a decision on.
$previousSelectionsQuery = "SELECT fnkOtherUsername FROM tblUserMatches WHERE pfkUsername = ?";
$previousSelections = array();
$previousSelectionsDataRecord = array();
$previousSelectionsDataRecord[] = $currUsername;
$previousSelectionsArrayFromQuery = array();

if ($thisDatabaseReader->querySecurityOk($previousSelectionsQuery, 1, 0)) {
    $previousSelectionsQuery = $thisDatabaseReader->sanitizeQuery($previousSelectionsQuery);
    $previousSelectionsArrayFromQuery = $thisDatabaseReader->select($previousSelectionsQuery, $previousSelectionsDataRecord);
}

foreach($previousSelectionsArrayFromQuery as $previousSelect) {
    $previousSelections[] = $previousSelect[0];
}

// Add selectionUsers to $selectionUsernames if they are not in $previousSelecitons
foreach($selectionUsernamesArrayFromQuery as $selectionUser) {
    if(!in_array($selectionUser[0], $previousSelections)) {
        $selectionUsernames[] = $selectionUser[0];
    }
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
    $yesAMatchQueryDataRecord[] = '1';
    
    if ($thisDatabaseWriter->querySecurityOk($yesAMatchQuery, 0)) {
        $yesAMatchQuery = $thisDatabaseWriter->sanitizeQuery($yesAMatchQuery);
        $yesAMatchQueryResults = $thisDatabaseWriter->insert($yesAMatchQuery, $yesAMatchQueryDataRecord);
    }
    
    // Find if user has corresponding match, and if so send email
    $correspondingMatchQuery = "SELECT fldMatch FROM tblUserMatches WHERE pfkUsername = ? AND fnkOtherUsername = ?";
    $correspondingMatchQueryResults = array();
    $correspondingMatchQueryDataRecord = array();
    $correspondingMatchQueryDataRecord[] = $selectionUsername;
    $correspondingMatchQueryDataRecord[] = $currUsername;
    
    if ($thisDatabaseReader->querySecurityOk($correspondingMatchQuery, 1, 1)) {
        $correspondingMatchQuery = $thisDatabaseReader->sanitizeQuery($correspondingMatchQuery);
        $correspondingMatchQueryResults = $thisDatabaseReader->select($correspondingMatchQuery, $correspondingMatchQueryDataRecord);
    }
    
    if($correspondingMatchQueryResults[0]['fldMatch'] == '1' || $correspondingMatchQueryResults[0]['fldMatch'] == 1) {
        
        // Get the user's names
        $getUserNamesQuery = "SELECT fldFirstName, fldLastName FROM tblProfile WHERE fnkUsername = ? OR fnkUsername = ?";
        $getUserNamesQueryResults = array();
        $getUserNamesQueryDataRecord = array();
        $getUserNamesQueryDataRecord[] = $selectionUsername;
        $getUserNamesQueryDataRecord[] = $currUsername;
        
        if ($thisDatabaseReader->querySecurityOk($getUserNamesQuery, 1, 1)) {
            $getUserNamesQuery = $thisDatabaseReader->sanitizeQuery($getUserNamesQuery);
            $getUserNamesQueryResults = $thisDatabaseReader->select($getUserNamesQuery, $getUserNamesQueryDataRecord);
        }
        
        $selectionUsernameRealName = $getUserNamesQueryResults[0]['fldFirstName'] . " " . $getUserNamesQueryResults[0]['fldLastName'];
        $currUsernameRealName = $getUserNamesQueryResults[1]['fldFirstName'] . " " . $getUserNamesQueryResults[1]['fldLastName'];
        
        // Email first user about match.
        $message = '<h2 style="font-style:italic" >You matched with the following user on GitTogether: </h2>';
       
        $message .= '<p>Name: ' . $selectionUsernameRealName . '</p>';
        $message .= '<p>Email: ' . $selectionUsername . '</p>';

        $to = $currUsername; 
        $cc = '';
        $bcc = '';

        $from = 'Git Together <match@gittogether.com>';

        $subject = 'Git Together Match!';
       
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
        
        // Email second user about match.
        $message = '<h2 style="font-style:italic" >You matched with the following user on GitTogether: </h2>';
       
        $message .= '<p>Name: ' . $currUsernameRealName . '</p>';
        $message .= '<p>Email: ' . $currUsername . '</p>';

        $to = $selectionUsername; 
        $cc = '';
        $bcc = '';

        $from = 'Git Together <match@gittogether.com>';

        $subject = 'Git Together Match!';
       
        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
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
    
} else if (isset($_POST["SelectionToProfileButton"])) {
    
    header("Location: profile.php?username=" . $currUsername);
    
} else if (isset($_POST["LogOutButton"])) {
    
    header("Location: index.php");
    
}

?>
<main>
    
    <form action="" method="post">
        <button name="SelectionToProfileButton" id="SelectionToProfileButton">Your Profile</button>
        <button name="LogOutButton" id="SelectionToProfileButton">Log Out</button>
    </form>
        
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
        <form action="" method="post" id="SelectionButtonsForm">
            <button class="selectionButtons" id="btnNo" name="btnNo">No.</button>
            <button class="selectionButtons" id="btnYes" name="btnYes">Yes!</button>
        
        </form>
        
        <?php
        
            } else {
                
                // Get list of users that the currUser has matched with.
                $matches = array();
                
                $potentialMatchesQuery = "SELECT fnkOtherUsername FROM tblUserMatches WHERE pfkUsername = ? AND fldMatch = ?";
                $potentialMatches = array();
                $potentialMatchesDataRecord = array();
                $potentialMatchesDataRecord[] = $currUsername;
                $potentialMatchesDataRecord[] = '1';
                $potentialMatchesArrayFromQuery = array();

                if ($thisDatabaseReader->querySecurityOk($potentialMatchesQuery, 1, 1)) {
                    $potentialMatchesQuery = $thisDatabaseReader->sanitizeQuery($potentialMatchesQuery);
                    $potentialMatchesArrayFromQuery = $thisDatabaseReader->select($potentialMatchesQuery, $potentialMatchesDataRecord);
                }
                
                foreach($potentialMatchesArrayFromQuery as $potentialMatch) {
                    $potentialMatches[] = $potentialMatch[0];
                }
                
                foreach($potentialMatches as $potentialMatch) {
                    
                    $checkMatchQuery = "SELECT fldMatch FROM tblUserMatches WHERE pfkUsername = ? AND fnkOtherUsername = ?";
                    $checkMatch = array();
                    $checkMatchDataRecord = array();
                    $checkMatchDataRecord[] = $potentialMatch;
                    $checkMatchDataRecord[] = $currUsername;
                    
                    if ($thisDatabaseReader->querySecurityOk($checkMatchQuery, 1, 1)) {
                        $checkMatchQuery = $thisDatabaseReader->sanitizeQuery($checkMatchQuery);
                        $checkMatch = $thisDatabaseReader->select($checkMatchQuery, $checkMatchDataRecord);
                    }
                    
                    if($checkMatch[0]['fldMatch'] == '1' || $checkMatch[0]['fldMatch'] == 1) {
                        $matches[] = $potentialMatch;
                    }
                    
                }
                
                // Get first and last names for each of the matches
                $matchesRealNames = array();
                
                foreach($matches as $match) {
                    
                    $getRealNameQuery = "SELECT fldFirstName, fldLastName FROM tblProfile WHERE fnkUsername = ?";
                    $getRealNameDataRecord = array();
                    $getRealNameDataRecord[] = $match;
                    $getRealNameQueryResults = array();
                    
                    if ($thisDatabaseReader->querySecurityOk($getRealNameQuery, 1, 0)) {
                        $getRealNameQuery = $thisDatabaseReader->sanitizeQuery($getRealNameQuery);
                        $getRealNameQueryResults = $thisDatabaseReader->select($getRealNameQuery, $getRealNameDataRecord);
                    }
                    
                    $matchesRealNames[] = $getRealNameQueryResults[0]["fldFirstName"] . " " . $getRealNameQueryResults[0]["fldLastName"];
                    
                }
                
                print("<h2>There are no more available profiles to match with.</h2>");
                
                if(!empty($matchesRealNames)) {
                    print("<h2>You've matched with the following people: </h2>");
                    print("<ul>");

                    foreach($matchesRealNames as $match) {
                        print("<li>" . $match . "</li>");
                    }

                    print("</ul>");
                } else {
                    print("<h2>You have not yet matched with anyone.</h2>");
                }
                
                
            }
            
        ?>
        
    </article>
    
</main>