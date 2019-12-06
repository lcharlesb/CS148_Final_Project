<?php

include 'top.php';

print '<a href="selection.php?username='. $_GET["username"] .'"><button class="adminTables">Go Back</button></a>';
print '<br>';

if ($isAdmin){

$user = htmlentities($_GET["username"], ENT_QUOTES, "UTF-8");
$add = (int)htmlentities($_GET["add"], ENT_QUOTES, "UTF-8");
print '<article id="adminTablesArticle">';

$query = 'SHOW TABLES';
$tableNames = '';
if ($thisDatabaseReader->querySecurityOk($query, 0)) {
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $tableNames = $thisDatabaseReader->select($query);

}

foreach($tableNames as $table){
    
    if($table[0] == 'tblInterests' || $table[0]== 'tblUsersInterests'){
        print '<h2>' . $table[0] . '</h2>';
    }
    else{
        print '<h2>' . $table[0] . '<a href="adminTablesEdit.php?username=' . $user .'&table='. $table[0] .'"> [Edit] </a></h2>';
    }
    
    print '<table class="admin-table">';
    
    //Count the number of columns in each table.
    $query = "SELECT count(*) ";
    $query .= "FROM information_schema.columns ";
    $query .= "WHERE table_name = ?";

    $tblName = $table[0];
    $table_array = array($tblName);
    if ($thisDatabaseReader->querySecurityOk($query, 1)) {
        $query = $thisDatabaseReader->sanitizeQuery($query);
        $result = $thisDatabaseReader->select($query,$table_array);
        
        
    }
    $numOfCols = (int) $result[0][0];// num of columns.


    // get the names of the colums in each table.
    $query = 'SHOW COLUMNS FROM ' . $table[0];
    $query = $thisDatabaseReader->sanitizeQuery($query);
    $columns = $thisDatabaseReader->select($query);
    
    $query = 'SELECT ';
    for($z = 1; $z <= $numOfCols; $z++){
        if($z == $numOfCols){
            $query.= $columns[$z-1]["Field"] . " ";  
        }
        else{
            $query.= $columns[$z-1]["Field"] . ", ";
        }
    }
    $query .= 'FROM ' . $table[0];

    if ($thisDatabaseReader->querySecurityOk($query, 0)) {
        $query = $thisDatabaseReader->sanitizeQuery($query);
        $tableInfo = $thisDatabaseReader->select($query);
        
    }
   
    if (!empty($tableInfo)){
        $th = '<tr>';
        $td = '<tr>';
        $count1 = 0;
        // for loop for headings of the table (i = 0; i < numOfCols; i++)
        foreach($columns as $column){
       
                $th .= '<th>' . $column["Field"] . "</th>";
        } 
        $th .= "</tr>";
        print $th;
        // for every row in the table
        foreach($tableInfo as $row){
            print '<tr>';
            // print each column value for the row.
            for($i = 0; $i < $numOfCols; $i++){
                
                    print '<td>' . $row[$i] . '</td>';
                
            }
            print '</tr>';
        }
        if($table[0] == 'tblInterests' ){
            print '<tr>';
            print '<td><a href="adminTablesEdit.php?username='. $user . '&table='. $table[0] .'"> [Add Row] </a></td>';
            print '</tr>';
        }
            
        
        
    }
    else{
        print '<h2>Empty Table</h2>';
    }
    
    
    print '</table>';
   

}
} //admin bracket
else {
    print '<h2>You are not allowed in this page.</h2>';
    
}