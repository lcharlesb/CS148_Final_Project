<?php

include 'top.php';

// Initialize variables for each form element
$username = "";
$firstName = "";
$lastName = "";
$gender = "";
$preference = "";
$bio = "";
$interests = array();

// Get username from URL
$username = htmlentities($_GET["username"], ENT_QUOTES, "UTF-8");

// Get profile information based on username
$profileQuery = "SELECT fldFirstName, fldLastName, fldGender, fldPreference, fldBio FROM tblProfile WHERE fnkUsername = ?";
$profileInformation = "";
$profileDataRecord = array();
$profileDataRecord[] = $username;

if ($thisDatabaseReader->querySecurityOk($profileQuery, 1, 0)) {
    $profileQuery = $thisDatabaseReader->sanitizeQuery($profileQuery);
    $profileInformation = $thisDatabaseReader->select($profileQuery, $profileDataRecord);
}

$firstName = $profileInformation[0]["fldFirstName"];
$lastName = $profileInformation[0]["fldLastName"];
$gender = $profileInformation[0]["fldGender"];
$preference = $profileInformation[0]["fldPreference"];
$bio = $profileInformation[0]["fldBio"];

// Get interests based on username
$interestquery = "SELECT fnkInterest FROM tblUsersInterests WHERE pfkUsername = ?";
$interestsInformation = "";
$interestsDataRecord = array();
$interestsDataRecord[] = $username;

if ($thisDatabaseReader->querySecurityOk($interestquery, 1, 0)) {
    $interestquery = $thisDatabaseReader->sanitizeQuery($interestquery);
    $interestsInformation = $thisDatabaseReader->select($interestquery, $interestsDataRecord);
}

foreach($interestsInformation as $interest) {
    $interests[] = $interest[0];
}


?>
<main>
    
    
    <article class="Profile">
        <a id="editProfile" class="button" href="<?php print "profile-form.php?username=" . $username ?>">Edit<img id="editProfileImage" src="images/edit_icon.png" /></a>
        <h2 id="profileName"><?php if($firstName != "") {echo $firstName . " ";} if($lastName != "") {echo $lastName;}?></h2>
        <?php if ($gender != "") {echo "<h4>Gender: </h4><p>" . $gender . "</p><BR>"; } ?>
        <?php if ($preference != "") {echo "<h4>Interested in: </h4><p>" . $preference . "</p><BR>"; } ?>
        <?php if (!empty($interests)) {echo "<h4>Your hobbies include: </h4>"; } ?>
        <?php
            if(!empty($interests)) {
                echo "<ul>";
                foreach($interests as $interest) {
                    echo "<li>" . $interest . "</li>";
                }
                echo "</ul>";
            }
        ?>
        <p id="profileBio"><?php echo $bio; ?></p>
        
    </article>
    
</main>