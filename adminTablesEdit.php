
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
            print '<th> pmkInterest <th>';
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
        print PHP_EOL . '<!-- SECTION: 1b form variables -->' . PHP_EOL;
        


    }
    
    else if($table == "tblUsers"){
        print PHP_EOL . '<!-- $_POST results if debug -->' . PHP_EOL;
        if (DEBUG){ 
            print '<p>Post Array:</p><pre>';
            print_r($_POST);
            print '</pre>';
        }
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