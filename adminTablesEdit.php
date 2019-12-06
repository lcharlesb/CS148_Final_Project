
<?php

include 'top.php';

$table = htmlentities($_GET["table"], ENT_QUOTES, "UTF-8");
if ($isAdmin){
    
    if($table == "tblInterests"){
        
        print PHP_EOL . '<!-- $_POST results if debug -->' . PHP_EOL;
        if (DEBUG){ 
            print '<p>Post Array:</p><pre>';
            print_r($_POST);
            print '</pre>';
        }
        print PHP_EOL . '<!-- form variables -->' . PHP_EOL;
        $newInterest = '';
        $query = 'SELECT pmkInterest ';
        $query .= 'FROM tblInterests';
        
        if ($thisDatabaseReader->querySecurityOk($query, 0)) {
            $query = $thisDatabaseReader->sanitizeQuery($query);
            $interests = $thisDatabaseReader->select($query);
            
        }
        $numOfInterests = count($interests);

        print PHP_EOL . '<!-- error flags -->' . PHP_EOL;
        $newInterestERROR = false;

        print PHP_EOL . '<!-- debugging setup -->' . PHP_EOL;

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

            $newInterest = htmlentities($_POST["txtInterest"], ENT_QUOTES, "UTF-8");
            
            print PHP_EOL . '<!-- validation -->' . PHP_EOL;
            
            if (empty($newInterest)) {
                $errorMsg[] = "Please enter an interest.";
                $newInterestERROR = true;
            }
            else{
            // check if it already exists.
            foreach($interests as $interest){
                if($newInterest == $interest[0]){
                    $errorMsg[] = "Please enter a new interest (no duplicates).";
                    $newInterestERROR = true;
                    }
                }
            }
            
            print PHP_EOL . '<!-- process for when form is passed -->' . PHP_EOL;
    
            if (!$errorMsg) {
                if (DEBUG) {
                    print "<p>Form is valid</p>";
                }
                
                print PHP_EOL . '<!-- save data -->' . PHP_EOL;
                $query = 'INSERT INTO tblInterests(pmkInterest) ';
                $query .= 'VALUES (?)';
                $newInterest = array($newInterest);
                
                if ($thisDatabaseWriter->querySecurityOk($query, 0)) {
                    $query = $thisDatabaseWriter->sanitizeQuery($query);
                    $results = $thisDatabaseWriter->insert($query, $newInterest);
                }
                $dataEntered = true;
            }
            
        } // end of btnSubmit
        print PHP_EOL . '<!-- display form -->' . PHP_EOL;
        if ($dataEntered) { // closing of if marked with: end body submit
            print "<h2>Record Saved</h2> ";
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
            print '<article id="adminTablesArticle">';
            print '<h2>' . $table . '</h2>';
            print '<table class="admin-table">';
            print '<tr>';
            print '<th> pmkInterest </th>';
            print '</tr>';
            
            $td = '';
            foreach($interests as $interest){
                $td .= '<tr><td>' . $interest[0] . '</td></tr>';
            }
            print $td;
            print '</table>';
            print '<br>';
            print '<form method ="post">';
            print '<label> New Interest: </label><br>';
            print '<input type= text name="txtInterest"></input>';
            print '<input type="submit" id="" name="btnSubmit" value="Submit" tabindex="900" class="button">';
            print '</form>';
        }
    }
    else if($table == "tblProfile"){
        print PHP_EOL . '<!-- $_POST results if debug -->' . PHP_EOL;
        if (DEBUG){ 
            print '<p>Post Array:</p><pre>';
            print_r($_POST);
            print '</pre>';
        }
        print PHP_EOL . '<!-- form variables -->' . PHP_EOL;
        $pmkProfileId = '';

        $query = 'SELECT pmkProfileId, fnkUsername, fldFirstName, fldLastname,fldGender, fldPreference, fldBio ';
        $query .= 'FROM tblProfile';
        
        if ($thisDatabaseReader->querySecurityOk($query, 0)) {
            $query = $thisDatabaseReader->sanitizeQuery($query);
            $profiles = $thisDatabaseReader->select($query);
            
        }
        $profileCount = count($profiles);

        print PHP_EOL . '<!-- error flags -->' . PHP_EOL;

        $pmkProfileIdERROR = false;

        print PHP_EOL . '<!-- debugging setup -->' . PHP_EOL;

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

            
            for($a = 0; $a < $profileCount; $a++){
                $pmkProfileId = (int) htmlentities($_POST["checkbox" . $a], ENT_QUOTES, "UTF-8");
                if (!empty($pmkProfileId)){
                    $pmkArray[] = $pmkProfileId;  
                }
                
            }
            print PHP_EOL . '<!-- validation -->' . PHP_EOL;
            
            
            print PHP_EOL . '<!-- process for when form is passed -->' . PHP_EOL;
    
            if (!$errorMsg) {
                if (DEBUG) {
                    print "<p>Form is valid</p>";
                }
                
                print PHP_EOL . '<!-- save data -->' . PHP_EOL;
                $query = 'DELETE FROM tblProfile ';
                $query .= 'WHERE pmkProfileId IN (';
                for($b = 1; $b < $profileCount-1;$b++){
                    $query.= '?, ';
                }
                $query .= '?)';
                
                $delete = $thisDatabaseWriter->delete($query, $pmkArray);
                
                $dataEntered = true;
            } 

        } // end of buttonSubmit
        print PHP_EOL . '<!-- display form -->' . PHP_EOL;
        if ($dataEntered) { // closing of if marked with: end body submit
            print "<h2>Record Saved</h2> ";
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
            print '<article id="adminTablesArticle">';
            print '<h2>' . $table . '</h2>';
            print '<form method ="post">';
            print '<table class="admin-table">';
            print '<tr>';
            print '<th></th><th>pmkProfileId</th><th>fnkUsername</th><th>fldFirstName</th><th>fldLastName</th><th>fldGender</th><th>fldPreference</th><th>fldBio</th>';
            print '</tr>';
            $td = '';
            
            for($i = 0; $i < $profileCount; $i++){
                $td .= '<tr><td><input type="checkbox" name="checkbox' . $i . '" value="'. $profiles[$i][0] .'"></input></td><td>' . $profiles[$i][0] . '</td><td>' . $profiles[$i][1] . '</td><td>' . $profiles[$i][2] . '</td><td>' . $profiles[$i][3] . '</td><td>' . $profiles[$i][4] . '</td><td>' . $profiles[$i][5] . '</td><td>' . $profiles[$i][6] . '</td></tr>';
            }
            print $td;
            print '</table>';
            print '<br>';
            
            print '<input type="submit" id="" name="btnSubmit" value="Delete" tabindex="900" class="button">';
            print '</form>';


        }
    } // end of if
    else if($table == "tblUsers"){
        print PHP_EOL . '<!-- $_POST results if debug -->' . PHP_EOL;
        if (DEBUG){ 
            print '<p>Post Array:</p><pre>';
            print_r($_POST);
            print '</pre>';
        }
        print PHP_EOL . '<!-- form variables -->' . PHP_EOL;
        $pmkUsername = '';

        $query = 'SELECT pmkUsername, fldPassword, fldAdmin ';
        $query .= 'FROM tblUsers';
        
        if ($thisDatabaseReader->querySecurityOk($query, 0)) {
            $query = $thisDatabaseReader->sanitizeQuery($query);
            $users = $thisDatabaseReader->select($query);
            
        }
        $userCount = count($users);
    }
    print '</article>';
}
else{
    print '<p>You are not allowed in this page.</p>';
}
?>
</main>

</body>
</html>