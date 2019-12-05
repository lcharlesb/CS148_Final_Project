<?php 
$user = htmlentities($_GET["username"], ENT_QUOTES, "UTF-8");
$user_array = array($user);
// query to check all admins for the page.
$query = 'SELECT fldAdmin ';
$query .= 'FROM tblUsers ';
$query .= 'WHERE pmkUsername = ?';

if ($thisDatabaseReader->querySecurityOk($query, 1, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $adminList = $thisDatabaseReader->select($query, $user_array);   
}
    if($adminList[0][0] == 1){
        $isAdmin = true;
    }
    else{
        $isAdmin = false;
    }
    


?>